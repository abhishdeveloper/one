<?php
class Admin extends Controller {
    private $userModel;
    private $contentModel;
    private $db;

    public function __construct() {
        Security::startSecureSession();

        // Ensure user is logged in and is an admin
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
            header('Location: ' . URLROOT . '/auth/login');
            exit;
        }

        $this->userModel = $this->model('UserModel');
        $this->contentModel = $this->model('ContentModel');
        $this->db = new Database(); // for direct queries
    }

    public function index() {
        $this->db->query("SELECT COUNT(*) as count FROM users WHERE role = 'client'");
        $clientCount = $this->db->single()->count;

        $this->db->query("SELECT COUNT(*) as count FROM client_services WHERE status = 'active'");
        $activeServicesCount = $this->db->single()->count;

        $this->db->query("SELECT COUNT(*) as count FROM tickets WHERE status = 'open'");
        $openTicketsCount = $this->db->single()->count;

        $data = [
            'settings' => $this->contentModel->getSettings(),
            'clientCount' => $clientCount,
            'activeServicesCount' => $activeServicesCount,
            'openTicketsCount' => $openTicketsCount
        ];

        $this->view('admin/index', $data);
    }

    public function users() {
        $this->db->query("SELECT * FROM users ORDER BY created_at DESC");
        $users = $this->db->resultSet();

        $data = [
            'settings' => $this->contentModel->getSettings(),
            'users' => $users
        ];

        $this->view('admin/users', $data);
    }

    public function chat() {
        $this->db->query("SELECT id, name, email FROM users WHERE role = 'client' ORDER BY name ASC");
        $clients = $this->db->resultSet();
        $data = [
            'settings' => $this->contentModel->getSettings(),
            'clients' => $clients
        ];
        $this->view('admin/chat', $data);
    }

    public function auditLogs() {
        $this->db->query("SELECT cl.*, u.name, u.email FROM client_logs cl JOIN users u ON cl.user_id = u.id ORDER BY cl.created_at DESC");
        $logs = $this->db->resultSet();
        $data = [
            'settings' => $this->contentModel->getSettings(),
            'logs' => $logs
        ];
        $this->view('admin/audit_logs', $data);
    }

    public function settings() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Verify CSRF
            if (!isset($_POST['csrf_token']) || !Security::verifyCsrfToken($_POST['csrf_token'])) {
                die('CSRF token validation failed');
            }

            // Sanitize and update settings
            $clientId = filter_input(INPUT_POST, 'google_client_id', FILTER_UNSAFE_RAW);
            $clientSecret = filter_input(INPUT_POST, 'google_client_secret', FILTER_UNSAFE_RAW);

            $this->db->query("UPDATE settings SET setting_value = :value WHERE setting_key = 'google_client_id'");
            $this->db->bind(':value', $clientId);
            $this->db->execute();

            $this->db->query("UPDATE settings SET setting_value = :value WHERE setting_key = 'google_client_secret'");
            $this->db->bind(':value', $clientSecret);
            $this->db->execute();

            // Settings loops
            $updateSettings = ['smtp_host', 'smtp_port', 'smtp_user', 'smtp_pass', 'smtp_from_email', 'smtp_from_name', 'upi_id', 'announcement_active', 'announcement_text'];
            foreach ($updateSettings as $key) {
                if (isset($_POST[$key])) {
                    $val = filter_input(INPUT_POST, $key, FILTER_UNSAFE_RAW);
                    $this->db->query("UPDATE settings SET setting_value = :value WHERE setting_key = :key");
                    $this->db->bind(':value', $val);
                    $this->db->bind(':key', $key);
                    $this->db->execute();
                }
            }

            $_SESSION['flash_message'] = 'Settings updated successfully';
            header('Location: ' . URLROOT . '/admin/settings');
            exit;
        }

        $data = [
            'settings' => $this->contentModel->getSettings()
        ];

        $this->view('admin/settings', $data);
    }

    public function deleteUser($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (!isset($_POST['csrf_token']) || !Security::verifyCsrfToken($_POST['csrf_token'])) {
                die('CSRF validation failed');
            }

            // Prevent self-deletion
            if ($id == $_SESSION['user_id']) {
                $_SESSION['flash_message'] = 'You cannot delete yourself.';
                header('Location: ' . URLROOT . '/admin/users');
                exit;
            }

            $this->db->query("DELETE FROM users WHERE id = :id");
            $this->db->bind(':id', $id);
            if ($this->db->execute()) {
                $_SESSION['flash_message'] = 'User deleted successfully';
            } else {
                $_SESSION['flash_message'] = 'Something went wrong';
            }
            header('Location: ' . URLROOT . '/admin/users');
        } else {
            header('Location: ' . URLROOT . '/admin/users');
        }
    }

    public function services() {
        $services = $this->contentModel->getServices();
        $data = [
            'settings' => $this->contentModel->getSettings(),
            'services' => $services
        ];
        $this->view('admin/services', $data);
    }

    public function addService() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (!isset($_POST['csrf_token']) || !Security::verifyCsrfToken($_POST['csrf_token'])) {
                die('CSRF validation failed');
            }

            $title = filter_input(INPUT_POST, 'title', FILTER_UNSAFE_RAW);
            $category = filter_input(INPUT_POST, 'category', FILTER_UNSAFE_RAW);
            $description = filter_input(INPUT_POST, 'description', FILTER_UNSAFE_RAW);
            $price = filter_input(INPUT_POST, 'price', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $billing_cycle = filter_input(INPUT_POST, 'billing_cycle', FILTER_UNSAFE_RAW);
            $features = filter_input(INPUT_POST, 'features', FILTER_UNSAFE_RAW);
            $sort_order = filter_input(INPUT_POST, 'sort_order', FILTER_SANITIZE_NUMBER_INT);

            $this->db->query("INSERT INTO services (title, category, description, price, billing_cycle, features, sort_order) VALUES (:title, :category, :description, :price, :billing_cycle, :features, :sort_order)");
            $this->db->bind(':title', $title);
            $this->db->bind(':category', $category);
            $this->db->bind(':description', $description);
            $this->db->bind(':price', $price);
            $this->db->bind(':billing_cycle', $billing_cycle);
            $this->db->bind(':features', $features);
            $this->db->bind(':sort_order', $sort_order);

            if ($this->db->execute()) {
                $_SESSION['flash_message'] = 'Service added successfully.';
                header('Location: ' . URLROOT . '/admin/services');
                exit;
            }
        }
        $data = ['settings' => $this->contentModel->getSettings()];
        $this->view('admin/service_form', $data);
    }

    public function editService($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (!isset($_POST['csrf_token']) || !Security::verifyCsrfToken($_POST['csrf_token'])) {
                die('CSRF validation failed');
            }

            $title = filter_input(INPUT_POST, 'title', FILTER_UNSAFE_RAW);
            $category = filter_input(INPUT_POST, 'category', FILTER_UNSAFE_RAW);
            $description = filter_input(INPUT_POST, 'description', FILTER_UNSAFE_RAW);
            $price = filter_input(INPUT_POST, 'price', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $billing_cycle = filter_input(INPUT_POST, 'billing_cycle', FILTER_UNSAFE_RAW);
            $features = filter_input(INPUT_POST, 'features', FILTER_UNSAFE_RAW);
            $sort_order = filter_input(INPUT_POST, 'sort_order', FILTER_SANITIZE_NUMBER_INT);

            $this->db->query("UPDATE services SET title=:title, category=:category, description=:description, price=:price, billing_cycle=:billing_cycle, features=:features, sort_order=:sort_order WHERE id=:id");
            $this->db->bind(':id', $id);
            $this->db->bind(':title', $title);
            $this->db->bind(':category', $category);
            $this->db->bind(':description', $description);
            $this->db->bind(':price', $price);
            $this->db->bind(':billing_cycle', $billing_cycle);
            $this->db->bind(':features', $features);
            $this->db->bind(':sort_order', $sort_order);

            if ($this->db->execute()) {
                $_SESSION['flash_message'] = 'Service updated successfully.';
                header('Location: ' . URLROOT . '/admin/services');
                exit;
            }
        }

        $this->db->query("SELECT * FROM services WHERE id = :id");
        $this->db->bind(':id', $id);
        $service = $this->db->single();

        $data = [
            'settings' => $this->contentModel->getSettings(),
            'service' => $service
        ];
        $this->view('admin/service_form', $data);
    }

    public function deleteService($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (!isset($_POST['csrf_token']) || !Security::verifyCsrfToken($_POST['csrf_token'])) {
                die('CSRF validation failed');
            }
            $this->db->query("DELETE FROM services WHERE id = :id");
            $this->db->bind(':id', $id);
            if ($this->db->execute()) {
                $_SESSION['flash_message'] = 'Service deleted successfully';
            }
        }
        header('Location: ' . URLROOT . '/admin/services');
        exit;
    }

    public function invoices() {
        $this->db->query("SELECT i.*, u.name as client_name, u.email as client_email, s.title as service_name FROM invoices i JOIN users u ON i.user_id = u.id LEFT JOIN client_services cs ON i.client_service_id = cs.id LEFT JOIN services s ON cs.service_id = s.id ORDER BY i.created_at DESC");
        $invoices = $this->db->resultSet();

        $this->db->query("SELECT id, name, email FROM users WHERE role = 'client' ORDER BY name ASC");
        $clients = $this->db->resultSet();

        $data = [
            'settings' => $this->contentModel->getSettings(),
            'invoices' => $invoices,
            'clients' => $clients
        ];

        $this->view('admin/invoices', $data);
    }

    public function createInvoice() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (!isset($_POST['csrf_token']) || !Security::verifyCsrfToken($_POST['csrf_token'])) {
                die('CSRF validation failed');
            }

            $userId = filter_input(INPUT_POST, 'user_id', FILTER_SANITIZE_NUMBER_INT);
            $amount = filter_input(INPUT_POST, 'amount', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $description = filter_input(INPUT_POST, 'description', FILTER_UNSAFE_RAW); // we'll use a dummy service or just rely on invoice having no client_service_id for custom

            if (empty($userId) || empty($amount)) {
                $_SESSION['flash_message'] = 'User and Amount are required.';
            } else {
                $this->db->query("INSERT INTO invoices (user_id, amount, due_date) VALUES (:user_id, :amount, DATE_ADD(NOW(), INTERVAL 7 DAY))");
                $this->db->bind(':user_id', $userId);
                $this->db->bind(':amount', $amount);

                if ($this->db->execute()) {
                    $_SESSION['flash_message'] = 'Custom invoice created successfully.';
                } else {
                    $_SESSION['flash_message'] = 'Something went wrong creating the invoice.';
                }
            }
        }
        header('Location: ' . URLROOT . '/admin/invoices');
        exit;
    }

    public function verifyPayment($id, $action) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (!isset($_POST['csrf_token']) || !Security::verifyCsrfToken($_POST['csrf_token'])) {
                die('CSRF validation failed');
            }

            if ($action == 'approve') {
                $this->db->query("UPDATE invoices SET status = 'paid', paid_date = NOW(), payment_method = 'UPI' WHERE id = :id");
                $this->db->bind(':id', $id);
                $this->db->execute();

                // Activate service if linked
                $this->db->query("SELECT client_service_id FROM invoices WHERE id = :id");
                $this->db->bind(':id', $id);
                $inv = $this->db->single();
                if ($inv && $inv->client_service_id) {
                    $this->db->query("UPDATE client_services SET status = 'active', next_due_date = DATE_ADD(NOW(), INTERVAL 1 MONTH) WHERE id = :cs_id");
                    $this->db->bind(':cs_id', $inv->client_service_id);
                    $this->db->execute();
                }

                $_SESSION['flash_message'] = 'Payment verified and invoice marked as paid.';
            } elseif ($action == 'reject') {
                $this->db->query("UPDATE invoices SET status = 'unpaid', utr_number = NULL WHERE id = :id");
                $this->db->bind(':id', $id);
                $this->db->execute();
                $_SESSION['flash_message'] = 'Payment rejected. Invoice returned to unpaid status.';
            }
        }
        header('Location: ' . URLROOT . '/admin/invoices');
        exit;
    }

    public function tickets() {
        if ($_SESSION['user_role'] != 'admin') {
            header('Location: ' . URLROOT . '/auth/login');
            exit;
        }

        $this->db->query("
            SELECT t.*, u.name as client_name, u.email as client_email, s.title as client_service_title
            FROM tickets t
            JOIN users u ON t.user_id = u.id
            LEFT JOIN client_services cs ON t.client_service_id = cs.id
            LEFT JOIN services s ON cs.service_id = s.id
            ORDER BY t.updated_at DESC
        ");
        $tickets = $this->db->resultSet();

        $data = [
            'tickets' => $tickets
        ];

        $this->view('admin/tickets', $data);
    }

    public function viewTicket($id) {
        if ($_SESSION['user_role'] != 'admin') {
            header('Location: ' . URLROOT . '/auth/login');
            exit;
        }

        $this->db->query("
            SELECT t.*, u.name as client_name, u.email as client_email, s.title as client_service_title
            FROM tickets t
            JOIN users u ON t.user_id = u.id
            LEFT JOIN client_services cs ON t.client_service_id = cs.id
            LEFT JOIN services s ON cs.service_id = s.id
            WHERE t.id = :id
        ");
        $this->db->bind(':id', $id);
        $ticket = $this->db->single();

        if (!$ticket) {
            header('Location: ' . URLROOT . '/admin/tickets');
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
            'ticket' => $ticket,
            'replies' => $replies
        ];

        $this->view('admin/ticket_view', $data);
    }

    public function replyTicket($id) {
        if ($_SESSION['user_role'] != 'admin' || $_SERVER['REQUEST_METHOD'] != 'POST') {
            header('Location: ' . URLROOT . '/auth/login');
            exit;
        }

        if (!isset($_POST['csrf_token']) || !Security::verifyCsrfToken($_POST['csrf_token'])) {
            die('CSRF validation failed');
        }

        $message = filter_input(INPUT_POST, 'message', FILTER_UNSAFE_RAW);

        if (!empty($message)) {
            $this->db->query("INSERT INTO ticket_replies (ticket_id, user_id, message) VALUES (:ticket_id, :user_id, :message)");
            $this->db->bind(':ticket_id', $id);
            $this->db->bind(':user_id', $_SESSION['user_id']);
            $this->db->bind(':message', $message);
            $this->db->execute();

            // Update ticket's updated_at timestamp to bubble it to the top
            $this->db->query("UPDATE tickets SET updated_at = CURRENT_TIMESTAMP WHERE id = :id");
            $this->db->bind(':id', $id);
            $this->db->execute();

            $_SESSION['flash_message'] = 'Reply posted successfully.';
        }

        header('Location: ' . URLROOT . '/admin/viewTicket/' . $id);
        exit;
    }

    public function updateTicketStatus($id) {
        if ($_SESSION['user_role'] != 'admin' || $_SERVER['REQUEST_METHOD'] != 'POST') {
            header('Location: ' . URLROOT . '/auth/login');
            exit;
        }

        if (!isset($_POST['csrf_token']) || !Security::verifyCsrfToken($_POST['csrf_token'])) {
            die('CSRF validation failed');
        }

        $status = $_POST['status'];
        $allowedStatuses = ['open', 'in_progress', 'resolved', 'closed'];

        if (in_array($status, $allowedStatuses)) {
            $this->db->query("UPDATE tickets SET status = :status, updated_at = CURRENT_TIMESTAMP WHERE id = :id");
            $this->db->bind(':status', $status);
            $this->db->bind(':id', $id);
            $this->db->execute();
            $_SESSION['flash_message'] = 'Ticket status updated.';
        }

        header('Location: ' . URLROOT . '/admin/viewTicket/' . $id);
        exit;
    }
}
