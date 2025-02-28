<?php
// backend/api/adminResetConfirm.php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

// $_GET['token'] ili $_POST['token']
$token = $_GET['token'] ?? '';
$newPassword = $_GET['new_password'] ?? ''; // ili iz POST-a

$mysqli = new mysqli("db", "phptestuser", "phptestpass", "phptest");
if ($mysqli->connect_errno) {
    echo json_encode(["error" => "Ne mogu da se povežem na bazu: " . $mysqli->connect_error]);
    exit;
}

// Pronađi admina sa tim reset_token
$stmt = $mysqli->prepare("SELECT id FROM admins WHERE reset_token = ?");
$stmt->bind_param("s", $token);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(["error" => "Nevažeći token"]);
    exit;
}

$admin = $result->fetch_assoc();

// Heširanje nove lozinke (u praksi obavezno)
$hashed = $newPassword; // password_hash($newPassword, PASSWORD_BCRYPT);

// Resetuj lozinku i obriši token
$update = $mysqli->prepare("UPDATE admins SET password = ?, reset_token = NULL WHERE id = ?");
$update->bind_param("si", $hashed, $admin['id']);
$update->execute();

echo json_encode(["success" => true, "message" => "Lozinka uspešno resetovana"]);

$stmt->close();
$update->close();
$mysqli->close();
