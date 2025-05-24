<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';
$config = require "../private/mail_config.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = htmlspecialchars($_POST['name'] ?? '');
    $email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
    $message =  htmlspecialchars($_POST['message'] ?? '');

    if (!$email || !$message) {
        http_response_code(400);
        echo 'Invalid Input';
        exit;
    }

    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = $config['smtp_host']
        $mail->SMTPAuth = true;
        $mail->Username = $config['smtp_user'];
        $mail->Password = $config['smtp_pass'];
        $mail->SMTPSecure = $config['smtp_secure'];
        $mail->Port = $config['smtp_port'];
        $mail->setFrom($config['from_email'], $config['from_name']);
        $mail->addAddress($config['from_email']);
        $mail->Subject = "NEW CONTACT FROM CONTACT";
        $mail->Body = "name: $name\nEmail: $email\nMessage:\n$message";
        $mail->send();
        echo "<alert>Message sent</alert>";
    } catch (Exception $e) {
        http_response_code(500);
        echo "Mailer Error: " . $mail->ErrorInfo;
    }
}
