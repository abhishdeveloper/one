<?php
class Client extends Controller {
    private $contentModel;
    private $db;

    public function __construct() {
        Security::startSecureSession();

        // Ensure user is logged in
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . URLROOT . '/auth/login');
            exit;
        }

        $this->contentModel = $this->model('ContentModel');
        $this->db = new Database();
    }

    public function index() {
        // Fetch active services
        $this->db->query("SELECT cs.*, s.title, s.icon FROM client_services cs JOIN services s ON cs.service_id = s.id WHERE cs.user_id = :user_id");
        $this->db->bind(':user_id', $_SESSION['user_id']);
        $services = $this->db->resultSet();

        // Fetch unpaid invoices
        $this->db->query("SELECT * FROM invoices WHERE user_id = :user_id AND status = 'unpaid'");
        $this->db->bind(':user_id', $_SESSION['user_id']);
        $unpaidInvoices = $this->db->resultSet();
        $totalDue = array_sum(array_column($unpaidInvoices, 'amount'));

        $data = [
            'settings' => $this->contentModel->getSettings(),
            'services' => $services,
            'unpaidInvoices' => $unpaidInvoices,
            'totalDue' => $totalDue
        ];

        $this->view('client/index', $data);
    }

    public function services() {
        $this->db->query("SELECT cs.*, s.title, s.description FROM client_services cs JOIN services s ON cs.service_id = s.id WHERE cs.user_id = :user_id");
        $this->db->bind(':user_id', $_SESSION['user_id']);
        $services = $this->db->resultSet();

        $data = [
            'settings' => $this->contentModel->getSettings(),
            'services' => $services
        ];

        $this->view('client/services', $data);
    }

    public function buy() {
        $services = $this->contentModel->getServices();
        $data = [
            'settings' => $this->contentModel->getSettings(),
            'available_services' => $services
        ];

        $this->view('client/buy', $data);
    }

    public function checkout($id) {
        $this->db->query("SELECT * FROM services WHERE id = :id");
        $this->db->bind(':id', $id);
        $serviceDef = $this->db->single();

        if (!$serviceDef) {
            header('Location: ' . URLROOT . '/client/buy');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (!isset($_POST['csrf_token']) || !Security::verifyCsrfToken($_POST['csrf_token'])) {
                die('CSRF validation failed');
            }

            $domain = filter_input(INPUT_POST, 'domain_name', FILTER_UNSAFE_RAW);
            $price = $serviceDef->price; // Use DB dynamic price

            $this->db->query("INSERT INTO client_services (user_id, service_id, domain_name, price, billing_cycle) VALUES (:user_id, :service_id, :domain, :price, :billing_cycle)");
            $this->db->bind(':user_id', $_SESSION['user_id']);
            $this->db->bind(':service_id', $serviceDef->id);
            $this->db->bind(':domain', $domain);
            $this->db->bind(':price', $price);
            $this->db->bind(':billing_cycle', $serviceDef->billing_cycle);
            $this->db->execute();

            $clientServiceId = $this->db->dbh->lastInsertId();

            $this->db->query("INSERT INTO invoices (user_id, client_service_id, amount, due_date) VALUES (:user_id, :cs_id, :amount, DATE_ADD(NOW(), INTERVAL 7 DAY))");
            $this->db->bind(':user_id', $_SESSION['user_id']);
            $this->db->bind(':cs_id', $clientServiceId);
            $this->db->bind(':amount', $price);
            $this->db->execute();

            // Send Email Notification
            require_once '../core/Mail.php';
            $mailer = new Mail();
            $mailer->sendServicePurchaseEmail($_SESSION['user_email'], $_SESSION['user_name'], $serviceDef->title, $domain);

            // Notify Admin (Assume ID 1 is primary admin for now)
            $this->db->query("SELECT email, name FROM users WHERE role = 'admin' LIMIT 1");
            $admin = $this->db->single();
            if ($admin) {
                $mailer->sendServicePurchaseEmail($admin->email, $admin->name . ' (Admin Alert)', $serviceDef->title, $domain);
            }

            // Log Action
            $this->model('UserModel')->logAction($_SESSION['user_id'], 'Purchased new service: ' . $serviceDef->title);

            $_SESSION['flash_message'] = 'Service requested and invoice generated successfully.';
            header('Location: ' . URLROOT . '/client/invoices');
            exit;
        }

        $data = [
            'settings' => $this->contentModel->getSettings(),
            'service' => $serviceDef
        ];

        $this->view('client/checkout', $data);
    }

    public function invoices() {
        $this->db->query("SELECT i.*, s.title as service_name FROM invoices i LEFT JOIN client_services cs ON i.client_service_id = cs.id LEFT JOIN services s ON cs.service_id = s.id WHERE i.user_id = :user_id ORDER BY i.created_at DESC");
        $this->db->bind(':user_id', $_SESSION['user_id']);
        $invoices = $this->db->resultSet();

        $data = [
            'settings' => $this->contentModel->getSettings(),
            'invoices' => $invoices
        ];

        $this->view('client/invoices', $data);
    }

    public function pay($id) {
        // Fetch invoice details
        $this->db->query("SELECT i.*, s.title as service_name FROM invoices i LEFT JOIN client_services cs ON i.client_service_id = cs.id LEFT JOIN services s ON cs.service_id = s.id WHERE i.id = :id AND i.user_id = :user_id AND i.status = 'unpaid'");
        $this->db->bind(':id', $id);
        $this->db->bind(':user_id', $_SESSION['user_id']);
        $invoice = $this->db->single();

        if (!$invoice) {
            header('Location: ' . URLROOT . '/client/invoices');
            exit;
        }

        $data = [
            'settings' => $this->contentModel->getSettings(),
            'invoice' => $invoice
        ];

        $this->view('client/pay', $data);
    }

    public function submitUtr($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (!isset($_POST['csrf_token']) || !Security::verifyCsrfToken($_POST['csrf_token'])) {
                die('CSRF validation failed');
            }

            $utrNumber = filter_input(INPUT_POST, 'utr_number', FILTER_UNSAFE_RAW);

            if (empty($utrNumber)) {
                $_SESSION['flash_message'] = 'Please enter your Transaction / UTR Number.';
                header('Location: ' . URLROOT . '/client/pay/' . $id);
                exit;
            }

            $this->db->query("UPDATE invoices SET status = 'pending_verification', utr_number = :utr WHERE id = :id AND user_id = :user_id");
            $this->db->bind(':id', $id);
            $this->db->bind(':user_id', $_SESSION['user_id']);
            $this->db->bind(':utr', $utrNumber);

            if ($this->db->execute()) {
                $this->model('UserModel')->logAction($_SESSION['user_id'], 'Submitted UTR for Invoice #' . $id);
                $_SESSION['flash_message'] = 'Payment reference submitted. Your payment is now pending manual verification by the admin.';
            } else {
                $_SESSION['flash_message'] = 'Failed to submit payment reference.';
            }

            header('Location: ' . URLROOT . '/client/invoices');
            exit;
        }
    }

    public function tickets() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (!isset($_POST['csrf_token']) || !Security::verifyCsrfToken($_POST['csrf_token'])) {
                die('CSRF validation failed');
            }

            $subject = filter_input(INPUT_POST, 'subject', FILTER_UNSAFE_RAW);
            $message = filter_input(INPUT_POST, 'message', FILTER_UNSAFE_RAW);
            $service_id = !empty($_POST['client_service_id']) ? $_POST['client_service_id'] : null;

            $this->db->query("INSERT INTO tickets (user_id, client_service_id, subject, message) VALUES (:user_id, :service_id, :subject, :message)");
            $this->db->bind(':user_id', $_SESSION['user_id']);
            $this->db->bind(':service_id', $service_id);
            $this->db->bind(':subject', $subject);
            $this->db->bind(':message', $message);

            if ($this->db->execute()) {
                $ticketId = $this->db->dbh->lastInsertId();

                // Send Email Notification
                require_once '../core/Mail.php';
                $mailer = new Mail();
                $mailer->sendNewTicketEmail($_SESSION['user_email'], $_SESSION['user_name'], $ticketId, $subject);

                // Notify Admin
                $this->db->query("SELECT email, name FROM users WHERE role = 'admin' LIMIT 1");
                $admin = $this->db->single();
                if ($admin) {
                    $mailer->sendNewTicketEmail($admin->email, $admin->name . ' (Admin Alert)', $ticketId, $subject);
                }

                $this->model('UserModel')->logAction($_SESSION['user_id'], 'Opened support ticket #' . $ticketId);

                $_SESSION['flash_message'] = 'Ticket generated successfully.';
                header('Location: ' . URLROOT . '/client/tickets');
                exit;
            }
        }

        $this->db->query("SELECT * FROM tickets WHERE user_id = :user_id ORDER BY updated_at DESC");
        $this->db->bind(':user_id', $_SESSION['user_id']);
        $tickets = $this->db->resultSet();

        $this->db->query("SELECT cs.id, s.title FROM client_services cs JOIN services s ON cs.service_id = s.id WHERE cs.user_id = :user_id AND cs.status = 'active'");
        $this->db->bind(':user_id', $_SESSION['user_id']);
        $active_services = $this->db->resultSet();

        $data = [
            'settings' => $this->contentModel->getSettings(),
            'tickets' => $tickets,
            'active_services' => $active_services
        ];

        $this->view('client/tickets', $data);
    }

    public function viewTicket($id) {
        $this->db->query("SELECT * FROM tickets WHERE id = :id AND user_id = :user_id");
        $this->db->bind(':id', $id);
        $this->db->bind(':user_id', $_SESSION['user_id']);
        $ticket = $this->db->single();

        if (!$ticket) {
            header('Location: ' . URLROOT . '/client/tickets');
            exit;
        }

        $this->db->query("
            SELECT tr.*, u.name as user_name, u.role
            FROM ticket_replies tr
            JOIN users u ON tr.user_id = u.id
            WHERE tr.ticket_id = :ticket_id
            ORDER BY tr.created_at ASC
        ");
        $this->db->bind(':ticket_id', $id);
        $replies = $this->db->resultSet();

        $data = [
            'settings' => $this->contentModel->getSettings(),
            'ticket' => $ticket,
            'replies' => $replies
        ];

        $this->view('client/ticket_view', $data);
    }

    public function replyTicket($id) {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            header('Location: ' . URLROOT . '/client/tickets');
            exit;
        }

        if (!isset($_POST['csrf_token']) || !Security::verifyCsrfToken($_POST['csrf_token'])) {
            die('CSRF validation failed');
        }

        // Verify ticket belongs to user
        $this->db->query("SELECT id FROM tickets WHERE id = :id AND user_id = :user_id");
        $this->db->bind(':id', $id);
        $this->db->bind(':user_id', $_SESSION['user_id']);
        if (!$this->db->single()) {
            header('Location: ' . URLROOT . '/client/tickets');
            exit;
        }

        $message = filter_input(INPUT_POST, 'message', FILTER_UNSAFE_RAW);

        if (!empty($message)) {
            $this->db->query("INSERT INTO ticket_replies (ticket_id, user_id, message) VALUES (:ticket_id, :user_id, :message)");
            $this->db->bind(':ticket_id', $id);
            $this->db->bind(':user_id', $_SESSION['user_id']);
            $this->db->bind(':message', $message);
            $this->db->execute();

            // Bubble ticket to top and mark open if it was resolved
            $this->db->query("UPDATE tickets SET updated_at = CURRENT_TIMESTAMP, status = 'open' WHERE id = :id AND status != 'closed'");
            $this->db->bind(':id', $id);
            $this->db->execute();

            $_SESSION['flash_message'] = 'Reply sent successfully.';
        }

        header('Location: ' . URLROOT . '/client/viewTicket/' . $id);
        exit;
    }
}
