<?php
// backend/sendConfirmation.php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

function generateQRIPSCode(array $payload) {
    $apiUrl = "https://nbs.rs/QRcode/api/qr/v1/gen?lang=sr_RS_Latn";
    
    $ch = curl_init($apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
    
    // Za testiranje SSL sertifikata (ne preporučuje se u produkciji)
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    
    $response = curl_exec($ch);
    if ($response === false) {
        error_log("CURL greška: " . curl_error($ch));
    }
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($httpCode === 200) {
        return base64_encode($response);
    } else {
        error_log("QR API greška, HTTP kod: " . $httpCode . ". Response: " . $response);
        return null;
    }
}

function sendConfirmationEmail($to, $full_name, $broj_vozacke_dozvole, $svrha_uplate, $iznos) {
    // Učitaj email template
    $template = file_get_contents(__DIR__ . '/email_template.html');
    
    // Pripremi payload za QR kôd prema smernicama
    $qrPayload = [
        "K" => "PR",
        "V" => "01",
        "C" => "1",
        "R" => "845000000040484987", // Koristi test račun iz dokumentacije ako je dostupan
        "N" => "JP EPS BEOGRAD\r\nBALKANSKA 13", // Primer iz dokumentacije
        "I" => "RSD3596,13", // Primer iz dokumentacije
        "P" => "MRĐO MAČKATOVIĆ\r\nŽUPSKA 13\r\nBEOGRAD 6", // Primer
        "SF" => "189",
        "S" => "UPLATA PO RAČUNU ZA EL. ENERGIJU",
        "RO" => "97163220000111111111000"
    ];
    
    
    $qrCodeBase64 = generateQRIPSCode($qrPayload);
    if (!$qrCodeBase64) {
        $qrImgTag = "<p>QR kôd nije generisan.</p>";
    } else {
        $qrImgTag = '<img src="data:image/png;base64,' . $qrCodeBase64 . '" alt="QR IPS kod" style="max-width:150px;">';
    }
    
    // Zameni placeholder-e u email template-u
    $template = str_replace('{{full_name}}', htmlspecialchars($full_name), $template);
    $template = str_replace('{{broj_vozacke_dozvole}}', htmlspecialchars($broj_vozacke_dozvole), $template);
    $template = str_replace('{{svrha_uplate}}', htmlspecialchars($svrha_uplate), $template);
    $template = str_replace('{{iznos}}', htmlspecialchars($iznos), $template);
    $template = str_replace('{{qr_code}}', $qrImgTag, $template);
    
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        // SMTP podešavanja – prilagodi prema svom serveru
        // $mail->Host       = 'milosu.com';
        // $mail->Port       = 587;
        // $mail->SMTPAuth   = true;
        // $mail->SMTPSecure = 'tls';
        // $mail->Username   = 'contact@milosu.com';
        // $mail->Password   = 'sifra';

        // Ako testirate lokalno sa MailHog, koristite:
        $mail->Host = 'mailhog';
        $mail->Port = 1025;
        $mail->SMTPAuth = false;
        $mail->SMTPSecure = false;
        
        $mail->setFrom('contact@milosu.com', 'MK Spark');
        $mail->addAddress($to, $full_name);
        
        $mail->isHTML(true);
        $mail->Subject = 'MKSpark Detalji o prijavi';
        $mail->Body    = $template;
        
        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Email nije poslat: " . $mail->ErrorInfo);
        return false;
    }
}
