<?php
// backend/api/adminReset.php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

$mysqli = new mysqli("db", "phptestuser", "phptestpass", "phptest");
if ($mysqli->connect_errno) {
    echo json_encode(["error" => "Ne mogu da se povežem na bazu: " . $mysqli->connect_error]);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$email = $data['email'] ?? '';

if (!$email) {
    echo json_encode(["error" => "Email je obavezan"]);
    exit;
}

// Provera da li admin sa tim emailom postoji
$stmt = $mysqli->prepare("SELECT id FROM admins WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(["error" => "Ne postoji admin sa datim email-om"]);
    exit;
}

$admin = $result->fetch_assoc();

// Generišemo token
$token = bin2hex(random_bytes(16));

// Upis tokena u bazu
$update = $mysqli->prepare("UPDATE admins SET reset_token = ? WHERE id = ?");
$update->bind_param("si", $token, $admin['id']);
$update->execute();

// (Opciono) Pošalji email sa linkom za reset (npr. /adminResetConfirm?token=$token)
// Ovde možeš koristiti PHPMailer ili mail() funkciju
// mail($email, "Reset lozinke", "Kliknite ovde: http://localhost:8080/api/adminResetConfirm.php?token=$token");

echo json_encode(["success" => true, "message" => "Link za reset lozinke poslat na email"]);

$stmt->close();
$update->close();
$mysqli->close();
