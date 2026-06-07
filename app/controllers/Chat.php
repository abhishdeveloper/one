<?php
class Chat extends Controller {
    private $db;

    public function __construct() {
        Security::startSecureSession();
        if (!isset($_SESSION['user_id'])) {
            die(json_encode(['error' => 'Unauthorized']));
        }
        $this->db = new Database();
    }

    public function getMessages() {
        $userId = $_SESSION['user_id'];
        // If client, fetch messages with admin (assuming admin is user ID 1 for simplicity in this MVP)
        // If admin, they would pass a client_id via GET, otherwise default to all or error
        $adminId = 1;

        $otherUserId = $_SESSION['user_role'] == 'admin' ? (isset($_GET['client_id']) ? intval($_GET['client_id']) : 0) : $adminId;

        if ($otherUserId == 0) {
            echo json_encode([]);
            return;
        }

        $this->db->query("SELECT * FROM chat_messages WHERE (sender_id = :u1 AND receiver_id = :u2) OR (sender_id = :u2 AND receiver_id = :u1) ORDER BY created_at ASC");
        $this->db->bind(':u1', $userId);
        $this->db->bind(':u2', $otherUserId);
        $messages = $this->db->resultSet();

        // Mark as read
        $this->db->query("UPDATE chat_messages SET is_read = 1 WHERE receiver_id = :u1 AND sender_id = :u2 AND is_read = 0");
        $this->db->bind(':u1', $userId);
        $this->db->bind(':u2', $otherUserId);
        $this->db->execute();

        echo json_encode($messages);
    }

    public function sendMessage() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $message = filter_input(INPUT_POST, 'message', FILTER_UNSAFE_RAW);
            $userId = $_SESSION['user_id'];
            $adminId = 1;

            $receiverId = $_SESSION['user_role'] == 'admin' ? (isset($_POST['client_id']) ? intval($_POST['client_id']) : 0) : $adminId;

            if (empty($message) || $receiverId == 0) {
                echo json_encode(['error' => 'Invalid data']);
                return;
            }

            $this->db->query("INSERT INTO chat_messages (sender_id, receiver_id, message) VALUES (:sender, :receiver, :message)");
            $this->db->bind(':sender', $userId);
            $this->db->bind(':receiver', $receiverId);
            $this->db->bind(':message', $message);

            if ($this->db->execute()) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['error' => 'Failed to save message']);
            }
        }
    }
}
