<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

$mysqli = new mysqli("db", "phptestuser", "phptestpass", "phptest");
if ($mysqli->connect_errno) {
    echo json_encode(["error" => "Ne mogu da se povežem sa bazom: " . $mysqli->connect_error]);
    exit;
}

// Umesto $_POST, učitavamo JSON telo:
$data = json_decode(file_get_contents('php://input'), true);

$naziv = $data['naziv'] ?? '';
$opis = $data['opis'] ?? '';
$datum = $data['datum'] ?? '';

if (!$naziv || !$datum) {
    echo json_encode(["error" => "Naziv i datum su obavezni"]);
    exit;
}

// Konverzija formata datuma (ako koristiš <input type="datetime-local">)
$datum = str_replace("T", " ", $datum);
if (strlen($datum) === 16) {
    $datum .= ":00";
}

$stmt = $mysqli->prepare("INSERT INTO termini (naziv, opis, datum) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $naziv, $opis, $datum);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Termin dodat"]);
} else {
    echo json_encode(["error" => "Greška prilikom dodavanja termina: " . $stmt->error]);
}

$stmt->close();
$mysqli->close();
