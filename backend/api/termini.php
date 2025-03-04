<?php
// backend/api/termini.php

header("Content-Type: application/json");

// CORS zaglavlja
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Ako je OPTIONS, vrati 200 i prekini
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Konektovanje na bazu
$mysqli = new mysqli("db", "phptestuser", "phptestpass", "phptest");
if ($mysqli->connect_errno) {
    echo json_encode(["error" => "Ne mogu da se povežem na bazu: " . $mysqli->connect_error]);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    // Vraćamo sve termine iz tabele termini
    $query = "SELECT * FROM termini";
    $result = $mysqli->query($query);
    $termini = [];
    while ($row = $result->fetch_assoc()) {
        $termini[] = $row;
    }
    echo json_encode($termini);
    $result->free();

} elseif ($method === 'POST') {
    // Primamo podatke iz JSON-a
    $data = json_decode(file_get_contents('php://input'), true);
    $naziv = $data['naziv'] ?? '';
    $datum = $data['datum'] ?? '';
    $iznos = $data['iznos'] ?? '';

    if (!$naziv || !$datum || !$iznos) {
        echo json_encode(["error" => "Sva polja su obavezna (naziv, datum, iznos)."]);
        exit;
    }

    $datum = str_replace("T", " ", $datum);
    if (strlen($datum) == 16) {
        $datum .= ":00";
    }

    $stmt = $mysqli->prepare("INSERT INTO termini (naziv, datum, iznos) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $naziv, $datum, $iznos);

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Termin kreiran."]);
    } else {
        echo json_encode(["error" => "Greška prilikom kreiranja termina: " . $stmt->error]);
    }
    $stmt->close();

} else {
    http_response_code(405);
    echo json_encode(["error" => "Method Not Allowed"]);
}

$mysqli->close();
