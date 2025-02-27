<?php
// backend/api/termini.php
header("Content-Type: application/json");

$mysqli = new mysqli("localhost", "phptestuser", "phptestpass", "phptest");
if ($mysqli->connect_errno) {
    echo json_encode(["error" => "Ne mogu da se povežem sa bazom: " . $mysqli->connect_error]);
    exit;
}

$result = $mysqli->query("SELECT id, naziv, opis, datum FROM termini ORDER BY datum ASC");
$termini = [];

while ($row = $result->fetch_assoc()) {
    $termini[] = $row;
}

echo json_encode($termini);

$result->free();
$mysqli->close();
