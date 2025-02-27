<?php
// backend/api/registrations.php
header("Content-Type: application/json");
// CORS (za lokalni rad)
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

$mysqli = new mysqli("localhost", "phptestuser", "phptestpass", "phptest");
if ($mysqli->connect_errno) {
    echo json_encode(["error" => "Ne mogu da se povežem sa bazom: " . $mysqli->connect_error]);
    exit;
}

// Upit za sve prijave, možeš prilagoditi redosled i filtriranje
$result = $mysqli->query("SELECT * FROM registrations ORDER BY created_at DESC");
$registrations = [];

while ($row = $result->fetch_assoc()) {
    $registrations[] = $row;
}

echo json_encode($registrations);

$result->free();
$mysqli->close();
