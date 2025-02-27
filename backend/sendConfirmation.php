<?php
// backend/sendConfirmation.php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Ako koristiš Composer

function sendConfirmationEmail($to, $full_name) {
    $mail = new PHPMailer(true);
    try {
        // SMTP podešavanja – prilagodi prema svom SMTP serveru
        $mail->isSMTP();
        $mail->Host = 'smtp.example.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'your_email@example.com';
        $mail->Password = 'your_password';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;
        
        $mail->setFrom('your_email@example.com', 'Moto Trke');
        $mail->addAddress($to, $full_name);
        
        // Email sadržaj
        $mail->isHTML(true);
        $mail->Subject = 'Potvrda uplate za Moto trke';
        $mail->Body    = 'Poštovani ' . htmlspecialchars($full_name) . ',<br><br>Vaša uplata je potvrđena. Hvala na prijavi!';
        
        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Email nije poslat: {$mail->ErrorInfo}");
        return false;
    }
}
