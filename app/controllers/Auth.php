<?php
class Auth extends Controller {
    private $userModel;
    private $contentModel;

    public function __construct() {
        $this->userModel = $this->model('UserModel');
        $this->contentModel = $this->model('ContentModel');
        Security::startSecureSession();
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (!isset($_POST['csrf_token']) || !Security::verifyCsrfToken($_POST['csrf_token'])) {
                die('CSRF token validation failed');
            }

            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'name' => trim($_POST['name']),
                'email' => trim($_POST['email']),
                'password' => trim($_POST['password']),
                'confirm_password' => trim($_POST['confirm_password']),
                'name_err' => '',
                'email_err' => '',
                'password_err' => '',
                'confirm_password_err' => '',
                'settings' => $this->contentModel->getSettings()
            ];

            if (empty($data['email'])) {
                $data['email_err'] = 'Please enter email';
            } else {
                if ($this->userModel->findUserByEmail($data['email'])) {
                    $data['email_err'] = 'Email is already taken';
                }
            }

            if (empty($data['name'])) {
                $data['name_err'] = 'Please enter name';
            }

            if (empty($data['password'])) {
                $data['password_err'] = 'Please enter password';
            } elseif (strlen($data['password']) < 6) {
                $data['password_err'] = 'Password must be at least 6 characters';
            }

            if (empty($data['confirm_password'])) {
                $data['confirm_password_err'] = 'Please confirm password';
            } else {
                if ($data['password'] != $data['confirm_password']) {
                    $data['confirm_password_err'] = 'Passwords do not match';
                }
            }

            if (empty($data['email_err']) && empty($data['name_err']) && empty($data['password_err']) && empty($data['confirm_password_err'])) {
                $data['password'] = Security::hashPassword($data['password']);

                if ($this->userModel->register($data)) {
                    // Send Welcome Email
                    require_once '../core/Mail.php';
                    $mailer = new Mail();
                    $mailer->sendWelcomeEmail($data['email'], $data['name']);

                    $_SESSION['flash_message'] = 'Registration successful! You can now log in.';
                    header('Location: ' . URLROOT . '/auth/login');
                    return;
                } else {
                    die('Something went wrong');
                }
            } else {
                $this->view('auth/register', $data);
            }
        } else {
            $data = [
                'name' => '',
                'email' => '',
                'password' => '',
                'confirm_password' => '',
                'name_err' => '',
                'email_err' => '',
                'password_err' => '',
                'confirm_password_err' => '',
                'settings' => $this->contentModel->getSettings()
            ];

            $this->view('auth/register', $data);
        }
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (!isset($_POST['csrf_token']) || !Security::verifyCsrfToken($_POST['csrf_token'])) {
                die('CSRF token validation failed');
            }

            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'email' => trim($_POST['email']),
                'password' => trim($_POST['password']),
                'email_err' => '',
                'password_err' => '',
                'settings' => $this->contentModel->getSettings()
            ];

            if (empty($data['email'])) {
                $data['email_err'] = 'Please enter email';
            }

            if (empty($data['password'])) {
                $data['password_err'] = 'Please enter password';
            }

            if ($this->userModel->findUserByEmail($data['email'])) {
                // User found
            } else {
                $data['email_err'] = 'No user found';
            }

            if (empty($data['email_err']) && empty($data['password_err'])) {
                $user = $this->userModel->getUserByEmail($data['email']);

                if ($user && $user->password && Security::verifyPassword($data['password'], $user->password)) {
                    $this->createUserSession($user);
                } else {
                    $data['password_err'] = 'Password incorrect';
                    $this->view('auth/login', $data);
                }
            } else {
                $this->view('auth/login', $data);
            }
        } else {
            $data = [
                'email' => '',
                'password' => '',
                'email_err' => '',
                'password_err' => '',
                'settings' => $this->contentModel->getSettings()
            ];

            $this->view('auth/login', $data);
        }
    }

    public function google() {
        $settings = $this->contentModel->getSettings();
        $clientId = $settings['google_client_id'] ?? '';
        $redirectUri = $settings['google_redirect_uri'] ?? '';

        if (empty($clientId) || empty($redirectUri)) {
            die('Google OAuth is not configured properly in the admin settings.');
        }

        $authUrl = "https://accounts.google.com/o/oauth2/v2/auth?" . http_build_query([
            'client_id' => $clientId,
            'redirect_uri' => $redirectUri,
            'response_type' => 'code',
            'scope' => 'email profile',
            'access_type' => 'online',
            'prompt' => 'consent'
        ]);

        header('Location: ' . filter_var($authUrl, FILTER_SANITIZE_URL));
        return;
    }

    public function googleCallback() {
        if (!isset($_GET['code'])) {
            header('Location: ' . URLROOT . '/auth/login');
            return;
        }

        $settings = $this->contentModel->getSettings();
        $clientId = $settings['google_client_id'] ?? '';
        $clientSecret = $settings['google_client_secret'] ?? '';
        $redirectUri = $settings['google_redirect_uri'] ?? '';

        $tokenUrl = 'https://oauth2.googleapis.com/token';
        $postData = [
            'code' => $_GET['code'],
            'client_id' => $clientId,
            'client_secret' => $clientSecret,
            'redirect_uri' => $redirectUri,
            'grant_type' => 'authorization_code'
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $tokenUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        curl_close($ch);

        $tokenData = json_decode($response, true);

        if (isset($tokenData['error'])) {
            die('Error fetching token: ' . $tokenData['error_description']);
        }

        $accessToken = $tokenData['access_token'];

        $userInfoUrl = 'https://www.googleapis.com/oauth2/v2/userinfo';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $userInfoUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Bearer ' . $accessToken]);

        $response = curl_exec($ch);
        curl_close($ch);

        $googleUser = json_decode($response, true);

        if (!isset($googleUser['email'])) {
            die('Error fetching user info from Google');
        }

        $userData = [
            'name' => $googleUser['name'],
            'email' => $googleUser['email'],
            'google_id' => $googleUser['id'],
            'avatar' => $googleUser['picture'] ?? null
        ];

        $user = $this->userModel->registerOrUpdateGoogleUser($userData);

        if ($user) {
            $this->createUserSession($user);
        } else {
            die('Something went wrong during Google Auth');
        }
    }

    public function createUserSession($user) {
        $_SESSION['user_id'] = $user->id;
        $_SESSION['user_email'] = $user->email;
        $_SESSION['user_name'] = $user->name;
        $_SESSION['user_role'] = $user->role;

        if ($user->role == 'admin') {
            header('Location: ' . URLROOT . '/admin/index');
        } else {
            $this->userModel->logAction($user->id, 'User logged in.');
            header('Location: ' . URLROOT . '/client/index');
        }
        return;
    }

    public function logout() {
        unset($_SESSION['user_id']);
        unset($_SESSION['user_email']);
        unset($_SESSION['user_name']);
        unset($_SESSION['user_role']);
        session_destroy();
        header('Location: ' . URLROOT . '/auth/login');
        return;
    }
}
