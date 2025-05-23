<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../vendor/autoload.php';

class Mailer {
    private $mail;

    public function __construct() {
        $this->mail = new PHPMailer(true);
        $config = require __DIR__ . '/../config/config.php';

        // SMTP Config
        $this->mail->isSMTP();
        $this->mail->Host       = $config['SMTP_HOST']; // Your SMTP Host
        $this->mail->SMTPAuth   = $config['SMTP_AUTH'];
        $this->mail->Username   = $config['SMTP_USERNAME']; // Your Gmail
        $this->mail->Password   = $config['SMTP_SECRET'];      // App Password (not your Gmail password)
        $this->mail->SMTPSecure = $config['SMTP_SECURE'];
        $this->mail->Port       = $config['SMTP_PORT'];

        // Sender Info
        $this->mail->setFrom('brandonichami@gmail.com', 'Elearning Platform');
        $this->mail->isHTML(true);
    }

    public function send($to, $subject, $body) {
        try {
            $this->mail->clearAddresses(); // Clear previous recipients
            $this->mail->addAddress($to);
            $this->mail->Subject = $subject;
            $this->mail->Body    = $body;

            $this->mail->send();
            return true;
        } catch (Exception $e) {
            error_log('Mailer Error: ' . $this->mail->ErrorInfo);
            return false;
        }
    }

    public function getError() {
        return $this->mail->ErrorInfo;
    }

}
