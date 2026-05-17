<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once APPROOT . '/core/libs/PHPMailer/Exception.php';
require_once APPROOT . '/core/libs/PHPMailer/PHPMailer.php';
require_once APPROOT . '/core/libs/PHPMailer/SMTP.php';

class Mail {
    private $mailer;
    private $contentModel;
    private $settings;

    public function __construct() {
        $this->contentModel = new ContentModel();
        $this->settings = $this->contentModel->getSettings();

        $this->mailer = new PHPMailer(true);

        // Server settings
        // If settings aren't empty, try SMTP, otherwise fallback to standard PHP mail()
        if (!empty($this->settings['smtp_host']) && !empty($this->settings['smtp_user'])) {
            $this->mailer->isSMTP();
            $this->mailer->Host       = $this->settings['smtp_host'];
            $this->mailer->SMTPAuth   = true;
            $this->mailer->Username   = $this->settings['smtp_user'];
            $this->mailer->Password   = $this->settings['smtp_pass'];
            $this->mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $this->mailer->Port       = $this->settings['smtp_port'];
        }

        // Default Sender
        $fromEmail = !empty($this->settings['smtp_from_email']) ? $this->settings['smtp_from_email'] : 'noreply@' . $_SERVER['HTTP_HOST'];
        $fromName = !empty($this->settings['smtp_from_name']) ? $this->settings['smtp_from_name'] : SITENAME;

        $this->mailer->setFrom($fromEmail, $fromName);
    }

    private function send($to, $subject, $htmlBody, $plainTextBody) {
        try {
            $this->mailer->clearAddresses();
            $this->mailer->addAddress($to);
            $this->mailer->isHTML(true);
            $this->mailer->Subject = $subject;
            $this->mailer->Body    = $htmlBody;
            $this->mailer->AltBody = $plainTextBody;

            $this->mailer->send();
            return true;
        } catch (Exception $e) {
            error_log("Message could not be sent. Mailer Error: {$this->mailer->ErrorInfo}");
            return false;
        }
    }

    private function getBaseTemplate($title, $content) {
        $siteName = $this->settings['site_name'] ?? SITENAME;
        return "
        <!DOCTYPE html>
        <html>
        <head>
            <style>
                body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #0f172a; color: #f8fafc; margin: 0; padding: 0; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background-color: #1e293b; padding: 20px; text-align: center; border-bottom: 2px solid #3b82f6; }
                .header h1 { margin: 0; color: #f8fafc; }
                .content { background-color: #1e293b; padding: 30px; line-height: 1.6; }
                .footer { background-color: #0f172a; padding: 20px; text-align: center; color: #94a3b8; font-size: 12px; }
                .btn { display: inline-block; padding: 10px 20px; background-color: #3b82f6; color: #ffffff; text-decoration: none; border-radius: 4px; margin-top: 20px; }
                h2 { color: #3b82f6; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h1>{$siteName}</h1>
                </div>
                <div class='content'>
                    <h2>{$title}</h2>
                    {$content}
                </div>
                <div class='footer'>
                    &copy; " . date('Y') . " {$siteName}. All rights reserved.<br>
                    This is an automated message, please do not reply.
                </div>
            </div>
        </body>
        </html>
        ";
    }

    public function sendWelcomeEmail($to, $name) {
        $siteName = $this->settings['site_name'] ?? SITENAME;
        $subject = "Welcome to {$siteName}!";

        $htmlContent = "
            <p>Hi {$name},</p>
            <p>Welcome to {$siteName}! Your account has been successfully created.</p>
            <p>You can now log in to your dashboard to purchase new services and manage your hosting infrastructure.</p>
            <center><a href='" . URLROOT . "/index.php?url=auth/login' class='btn'>Go to Dashboard</a></center>
        ";

        $plainTextContent = "Hi {$name},\n\nWelcome to {$siteName}! Your account has been successfully created.\nYou can now log in to your dashboard at " . URLROOT . "/index.php?url=auth/login";

        return $this->send($to, $subject, $this->getBaseTemplate('Welcome Aboard', $htmlContent), $plainTextContent);
    }

    public function sendServicePurchaseEmail($to, $name, $serviceName, $domain) {
        $siteName = $this->settings['site_name'] ?? SITENAME;
        $subject = "New Service Requested: {$serviceName}";

        $htmlContent = "
            <p>Hi {$name},</p>
            <p>We have received your request for <strong>{$serviceName}</strong> (Domain/Target: {$domain}).</p>
            <p>An invoice has been generated in your dashboard. Once payment is received, your service will be automatically activated.</p>
            <center><a href='" . URLROOT . "/index.php?url=client/invoices' class='btn'>View Invoices</a></center>
        ";

        $plainTextContent = "Hi {$name},\n\nWe have received your request for {$serviceName} ({$domain}).\nAn invoice has been generated in your dashboard. Once payment is received, your service will be automatically activated.\nView invoices: " . URLROOT . "/index.php?url=client/invoices";

        return $this->send($to, $subject, $this->getBaseTemplate('Service Requested', $htmlContent), $plainTextContent);
    }

    public function sendNewTicketEmail($to, $name, $ticketId, $subjectLine) {
        $siteName = $this->settings['site_name'] ?? SITENAME;
        $subject = "Support Ticket Created [#{$ticketId}]";

        $htmlContent = "
            <p>Hi {$name},</p>
            <p>A new support ticket has been created on your account.</p>
            <p><strong>Subject:</strong> {$subjectLine}</p>
            <p>Our team will review your request and respond shortly. You can track the status of this ticket in your dashboard.</p>
            <center><a href='" . URLROOT . "/index.php?url=client/tickets' class='btn'>View Tickets</a></center>
        ";

        $plainTextContent = "Hi {$name},\n\nA new support ticket has been created on your account.\nSubject: {$subjectLine}\nOur team will review your request and respond shortly.\nView tickets: " . URLROOT . "/index.php?url=client/tickets";

        return $this->send($to, $subject, $this->getBaseTemplate('Support Ticket Created', $htmlContent), $plainTextContent);
    }
}
