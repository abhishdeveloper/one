<?php

class Security {

    /**
     * Start a secure session if not already started
     */
    public static function startSecureSession() {
        if (session_status() === PHP_SESSION_NONE) {
            // Set session cookie parameters for security
            session_set_cookie_params([
                'lifetime' => 3600,
                'path' => '/',
                'domain' => '', // Set to your domain in production
                'secure' => isset($_SERVER['HTTPS']), // True if HTTPS
                'httponly' => true,
                'samesite' => 'Strict'
            ]);
            session_start();

            // Regenerate session ID periodically to prevent session fixation
            if (!isset($_SESSION['last_regeneration'])) {
                self::regenerateSession();
            } else {
                $interval = 60 * 30; // 30 minutes
                if (time() - $_SESSION['last_regeneration'] >= $interval) {
                    self::regenerateSession();
                }
            }
        }
    }

    private static function regenerateSession() {
        session_regenerate_id(true);
        $_SESSION['last_regeneration'] = time();
    }

    /**
     * Generate a CSRF token
     * @return string token
     */
    public static function generateCsrfToken() {
        self::startSecureSession();
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    /**
     * Verify a CSRF token
     * @param string $token
     * @return bool
     */
    public static function verifyCsrfToken($token) {
        self::startSecureSession();
        if (isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token)) {
            return true;
        }
        return false;
    }

    /**
     * Sanitize output to prevent XSS (Cross-Site Scripting)
     * @param string $data
     * @return string
     */
    public static function sanitizeOutput($data) {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $data[$key] = self::sanitizeOutput($value);
            }
            return $data;
        }
        return htmlspecialchars((string)$data, ENT_QUOTES, 'UTF-8');
    }

    /**
     * Hash a password using Argon2id (Bank grade)
     * @param string $password
     * @return string
     */
    public static function hashPassword($password) {
        // PASSWORD_ARGON2ID is available in PHP 7.3+
        if (defined('PASSWORD_ARGON2ID')) {
            $options = [
                'memory_cost' => 65536, // 64MB
                'time_cost' => 4,
                'threads' => 2
            ];
            return password_hash($password, PASSWORD_ARGON2ID, $options);
        } else {
            // Fallback to bcrypt if Argon2 is not available
            return password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
        }
    }

    /**
     * Verify a password against a hash
     * @param string $password
     * @param string $hash
     * @return bool
     */
    public static function verifyPassword($password, $hash) {
        return password_verify($password, $hash);
    }

    /**
     * Get the real IP address of the client
     * @return string
     */
    public static function getClientIp() {
        $ipaddress = '';
        if (isset($_SERVER['HTTP_CLIENT_IP']))
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_X_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        else if(isset($_SERVER['REMOTE_ADDR']))
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }
}
