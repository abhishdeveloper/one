<?php
class Admin extends Controller {
    private $userModel;
    private $contentModel;
    private $db;

    public function __construct() {
        Security::startSecureSession();

        // Ensure user is logged in and is an admin
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
            header('Location: ' . URLROOT . '/index.php?url=auth/login');
            return;
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

    public function settings() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Verify CSRF
            if (!isset($_POST['csrf_token']) || !Security::verifyCsrfToken($_POST['csrf_token'])) {
                die('CSRF token validation failed');
            }

            // Sanitize and update settings
            $clientId = filter_input(INPUT_POST, 'google_client_id', FILTER_SANITIZE_STRING);
            $clientSecret = filter_input(INPUT_POST, 'google_client_secret', FILTER_SANITIZE_STRING);

            $this->db->query("UPDATE settings SET setting_value = :value WHERE setting_key = 'google_client_id'");
            $this->db->bind(':value', $clientId);
            $this->db->execute();

            $this->db->query("UPDATE settings SET setting_value = :value WHERE setting_key = 'google_client_secret'");
            $this->db->bind(':value', $clientSecret);
            $this->db->execute();

            // SMTP Settings
            $smtpSettings = ['smtp_host', 'smtp_port', 'smtp_user', 'smtp_pass', 'smtp_from_email', 'smtp_from_name'];
            foreach ($smtpSettings as $key) {
                if (isset($_POST[$key])) {
                    $val = filter_input(INPUT_POST, $key, FILTER_SANITIZE_STRING);
                    $this->db->query("UPDATE settings SET setting_value = :value WHERE setting_key = :key");
                    $this->db->bind(':value', $val);
                    $this->db->bind(':key', $key);
                    $this->db->execute();
                }
            }

            $_SESSION['flash_message'] = 'Settings updated successfully';
            header('Location: ' . URLROOT . '/index.php?url=admin/settings');
            return;
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
                header('Location: ' . URLROOT . '/index.php?url=admin/users');
                return;
            }

            $this->db->query("DELETE FROM users WHERE id = :id");
            $this->db->bind(':id', $id);
            if ($this->db->execute()) {
                $_SESSION['flash_message'] = 'User deleted successfully';
            } else {
                $_SESSION['flash_message'] = 'Something went wrong';
            }
            header('Location: ' . URLROOT . '/index.php?url=admin/users');
        } else {
            header('Location: ' . URLROOT . '/index.php?url=admin/users');
        }
    }
}
