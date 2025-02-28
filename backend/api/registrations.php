<?php
// backend/api/registrations.php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

$mysqli = new mysqli("db", "phptestuser", "phptestpass", "phptest");
if ($mysqli->connect_errno) {
    echo json_encode(["error" => "Ne mogu da se povežem sa bazom: " . $mysqli->connect_error]);
    exit;
}

// Vraćamo samo odabrane kolone (možeš dodati i ostale ako želiš)
$query = "SELECT id, full_name, email, potvrdeno FROM registrations";
$result = $mysqli->query($query);

$registrations = [];
while ($row = $result->fetch_assoc()) {
    // Ako su bitna polja popunjena, dodajemo red u niz
    if (!empty($row['full_name'])) {
        $registrations[] = $row;
    }
}

echo json_encode($registrations);

$result->free();
$mysqli->close();
