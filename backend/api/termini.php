<?php
// backend/api/termini.php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

$mysqli = new mysqli("db", "phptestuser", "phptestpass", "phptest");
if ($mysqli->connect_errno) {
    echo json_encode(["error" => "Ne mogu da se povežem sa bazom: " . $mysqli->connect_error]);
    exit;
}

$query = "SELECT * FROM termini";
$result = $mysqli->query($query);

$termini = [];
while ($row = $result->fetch_assoc()) {
    $termini[] = $row;
}

echo json_encode($termini);

$result->free();
$mysqli->close();
