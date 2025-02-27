<?php
// backend/api/potvrdi.php
header("Content-Type: application/json");
// CORS
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

$mysqli = new mysqli("localhost", "phptestuser", "phptestpass", "phptest");
if ($mysqli->connect_errno) {
    echo json_encode(["error" => "Ne mogu da se povežem sa bazom: " . $mysqli->connect_error]);
    exit;
}

$id = $_POST['id'] ?? 0;
if ($id <= 0) {
    echo json_encode(["error" => "Nepostojeća ili nevalidna prijava"]);
    exit;
}

// Setovanje potvrdeno = 1
$stmt = $mysqli->prepare("UPDATE registrations SET potvrdeno = 1 WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    // (Opciono) Slanje email potvrde:
    // sendConfirmationEmail($id); // ovde bi pozvao funkciju koja dohvaća email iz baze i šalje potvrdu
    // nakon $stmt->execute() i pre echo json_encode([...]):

// Dohvatanje email adrese drajvera
$query = $mysqli->prepare("SELECT email, full_name FROM registrations WHERE id = ?");
$query->bind_param("i", $id);
$query->execute();
$res = $query->get_result();
if ($row = $res->fetch_assoc()) {
    $email = $row['email'];
    $full_name = $row['full_name'];
    sendConfirmationEmail($email, $full_name); // implementirano npr. u sendConfirmation.php
}

    echo json_encode(["success" => true, "message" => "Uplata potvrđena. Email poslat (ako je implementirano)."]);
} else {
    echo json_encode(["error" => "Greška prilikom potvrde uplate: " . $stmt->error]);
}

$stmt->close();
$mysqli->close();
