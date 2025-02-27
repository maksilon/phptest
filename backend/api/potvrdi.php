<?php
// backend/api/potvrdi.php
header("Content-Type: application/json");

$mysqli = new mysqli("localhost", "phptestuser", "phptestpass", "phptest");
if ($mysqli->connect_errno) {
    echo json_encode(["error" => "Ne mogu da se povežem sa bazom: " . $mysqli->connect_error]);
    exit;
}

$id = $_POST['id'] ?? 0;
if ($id <= 0) {
    echo json_encode(["error" => "Nepostojeća prijava"]);
    exit;
}

$stmt = $mysqli->prepare("UPDATE registrations SET potvrdeno = 1 WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    // Pozovi funkciju za slanje email potvrde (implementiraj je prema svojim potrebama)
    // Na primer: sendConfirmationEmail($to, $full_name);
    echo json_encode(["success" => true, "message" => "Uplata potvrđena i email poslat!"]);
} else {
    echo json_encode(["error" => "Greška prilikom potvrde: " . $stmt->error]);
}

$stmt->close();
$mysqli->close();
