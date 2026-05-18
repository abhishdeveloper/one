<?php
class UserModel {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    // Register user
    public function register($data) {
        $this->db->query('INSERT INTO users (name, email, password) VALUES(:name, :email, :password)');
        // Bind values
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':password', $data['password']);

        // Execute
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    // Register or Update User via Google Auth
    public function registerOrUpdateGoogleUser($data) {
        $this->db->query('SELECT * FROM users WHERE email = :email');
        $this->db->bind(':email', $data['email']);
        $row = $this->db->single();

        if ($row) {
            // Update existing user with Google ID and Avatar if not present
            $this->db->query('UPDATE users SET google_id = :google_id, avatar = :avatar WHERE email = :email');
            $this->db->bind(':google_id', $data['google_id']);
            $this->db->bind(':avatar', $data['avatar']);
            $this->db->bind(':email', $data['email']);
            $this->db->execute();
            return $row;
        } else {
            // Create new Google user
            $this->db->query('INSERT INTO users (name, email, google_id, avatar) VALUES(:name, :email, :google_id, :avatar)');
            $this->db->bind(':name', $data['name']);
            $this->db->bind(':email', $data['email']);
            $this->db->bind(':google_id', $data['google_id']);
            $this->db->bind(':avatar', $data['avatar']);

            if ($this->db->execute()) {
                // Fetch the newly created user
                $this->db->query('SELECT * FROM users WHERE email = :email');
                $this->db->bind(':email', $data['email']);
                return $this->db->single();
            } else {
                return false;
            }
        }
    }

    // Find user by email
    public function findUserByEmail($email) {
        $this->db->query('SELECT * FROM users WHERE email = :email');
        $this->db->bind(':email', $email);

        $row = $this->db->single();

        // Check row
        if ($this->db->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }

    // Get user by email
    public function getUserByEmail($email) {
        $this->db->query('SELECT * FROM users WHERE email = :email');
        $this->db->bind(':email', $email);

        return $this->db->single();
    }

    // Get user by ID
    public function getUserById($id) {
        $this->db->query('SELECT * FROM users WHERE id = :id');
        $this->db->bind(':id', $id);

        return $this->db->single();
    }

    // Log Client Action
    public function logAction($userId, $action) {
        $ip = Security::getClientIp();
        $this->db->query('INSERT INTO client_logs (user_id, action, ip_address) VALUES (:user_id, :action, :ip_address)');
        $this->db->bind(':user_id', $userId);
        $this->db->bind(':action', $action);
        $this->db->bind(':ip_address', $ip);
        $this->db->execute();
    }
}
