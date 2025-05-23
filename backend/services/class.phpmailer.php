<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../vendor/autoload.php';

class Mailer {
    private $mail;

    public function __construct() {
        $this->mail = new PHPMailer(true);

        // SMTP Config
        $this->mail->isSMTP();
        $this->mail->Host       = 'smtp.gmail.com';
        $this->mail->SMTPAuth   = true;
        $this->mail->Username   = 'brandonichami@gmail.com'; // Your Gmail
        $this->mail->Password   = 'wogjspdqecidsqvb';      // App Password (not your Gmail password)
        $this->mail->SMTPSecure = 'tls';
        $this->mail->Port       = 587;

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
