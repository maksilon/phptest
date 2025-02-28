<?php
// backend/api/adminLogin.php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

$mysqli = new mysqli("db", "phptestuser", "phptestpass", "phptest");
if ($mysqli->connect_errno) {
    echo json_encode(["error" => "Ne mogu da se povežem na bazu: " . $mysqli->connect_error]);
    exit;
}

// Učitavamo JSON input (Axios šalje JSON)
$data = json_decode(file_get_contents('php://input'), true);
$username = $data['username'] ?? '';
$password = $data['password'] ?? '';

if (!$username || !$password) {
    echo json_encode(["error" => "Nedostaje username ili password"]);
    exit;
}

// Dohvati admina po username
$stmt = $mysqli->prepare("SELECT id, name, email, password FROM admins WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(["error" => "Pogrešan username ili password"]);
    exit;
}

$admin = $result->fetch_assoc();
// $admin['password'] je heširana lozinka u bazi

// Provera lozinke
// Ako koristiš password_hash/password_verify:
$hashedPassword = $admin['password'];
// if (!password_verify($password, $hashedPassword)) {
//     echo json_encode(["error" => "Pogrešan username ili password"]);
//     exit;
// }

// Ako (za test) još uvek ne koristiš heširanje, samo poredi direktno (nebezbedno!):
if ($password !== $hashedPassword) {
    echo json_encode(["error" => "Pogrešan username ili password"]);
    exit;
}

// Ako je lozinka ispravna, generiši token
// Za primer, koristićemo neki random string. U praksi bi se koristio JWT ili slično.
$token = bin2hex(random_bytes(16));

// Možeš čuvati token u bazi ili ga samo vratiti frontendu
echo json_encode([
    "success" => true,
    "message" => "Uspešna prijava",
    "admin" => [
      "id" => $admin['id'],
      "name" => $admin['name'],
      "email" => $admin['email'],
      // Možeš dodati i "username" => $username
    ],
    "token" => $token
]);

$stmt->close();
$mysqli->close();
