<?php
class Client extends Controller {
    private $contentModel;
    private $db;

    public function __construct() {
        Security::startSecureSession();

        // Ensure user is logged in
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . URLROOT . '/index.php?url=auth/login');
            return;
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
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (!isset($_POST['csrf_token']) || !Security::verifyCsrfToken($_POST['csrf_token'])) {
                die('CSRF validation failed');
            }

            $serviceId = filter_input(INPUT_POST, 'service_id', FILTER_SANITIZE_NUMBER_INT);
            $domain = filter_input(INPUT_POST, 'domain_name', FILTER_SANITIZE_STRING);

            $this->db->query("SELECT * FROM services WHERE id = :id");
            $this->db->bind(':id', $serviceId);
            $serviceDef = $this->db->single();

            if ($serviceDef) {
                $price = 19.99;

                $this->db->query("INSERT INTO client_services (user_id, service_id, domain_name, price) VALUES (:user_id, :service_id, :domain, :price)");
                $this->db->bind(':user_id', $_SESSION['user_id']);
                $this->db->bind(':service_id', $serviceId);
                $this->db->bind(':domain', $domain);
                $this->db->bind(':price', $price);
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

                $_SESSION['flash_message'] = 'Service requested and invoice generated successfully.';
                header('Location: ' . URLROOT . '/index.php?url=client/invoices');
                return;
            }
        }

        $services = $this->contentModel->getServices();
        $data = [
            'settings' => $this->contentModel->getSettings(),
            'available_services' => $services
        ];

        $this->view('client/buy', $data);
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
        $this->db->query("UPDATE invoices SET status = 'paid', paid_date = NOW(), payment_method = 'Simulated Gateway' WHERE id = :id AND user_id = :user_id");
        $this->db->bind(':id', $id);
        $this->db->bind(':user_id', $_SESSION['user_id']);

        if ($this->db->execute()) {
            $this->db->query("SELECT client_service_id FROM invoices WHERE id = :id");
            $this->db->bind(':id', $id);
            $inv = $this->db->single();
            if ($inv && $inv->client_service_id) {
                $this->db->query("UPDATE client_services SET status = 'active', next_due_date = DATE_ADD(NOW(), INTERVAL 1 MONTH) WHERE id = :cs_id");
                $this->db->bind(':cs_id', $inv->client_service_id);
                $this->db->execute();
            }
            $_SESSION['flash_message'] = 'Payment successful!';
        }

        header('Location: ' . URLROOT . '/index.php?url=client/invoices');
    }

    public function tickets() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (!isset($_POST['csrf_token']) || !Security::verifyCsrfToken($_POST['csrf_token'])) {
                die('CSRF validation failed');
            }

            $subject = filter_input(INPUT_POST, 'subject', FILTER_SANITIZE_STRING);
            $message = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_STRING);
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

                $_SESSION['flash_message'] = 'Ticket generated successfully.';
                header('Location: ' . URLROOT . '/index.php?url=client/tickets');
                return;
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
}
