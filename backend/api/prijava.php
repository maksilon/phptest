<?php
// backend/api/prijava.php
header("Content-Type: application/json");
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Povezivanje sa bazom
$mysqli = new mysqli("localhost", "phptestuser", "phptestpass", "phptest");
if ($mysqli->connect_errno) {
    echo json_encode(["error" => "Ne mogu da se povežem sa bazom: " . $mysqli->connect_error]);
    exit;
}

// Preuzimanje podataka iz POST zahteva
$full_name = $_POST['full_name'] ?? '';
$datum_rodjenja = $_POST['datum_rodjenja'] ?? '';
$kontakt_telefon = $_POST['kontakt_telefon'] ?? '';
$email = $_POST['email'] ?? '';
$motocikl_i_zapremina = $_POST['motocikl_i_zapremina'] ?? '';
$broj_vozacke_dozvole = $_POST['broj_vozacke_dozvole'] ?? '';
$vozacka_dozvola_vazi_do = $_POST['vozacka_dozvola_vazi_do'] ?? '';
$termin_id = $_POST['termin_id'] ?? 0;
$startni_broj = $_POST['startni_broj'] ?? 0;
$takmicarska_licenca = isset($_POST['takmicarska_licenca']) && $_POST['takmicarska_licenca'] == '1' ? 1 : 0;
$grupa = $_POST['grupa'] ?? '';

// Priprema SQL upita
$stmt = $mysqli->prepare("INSERT INTO registrations (full_name, datum_rodjenja, kontakt_telefon, email, motocikl_i_zapremina, broj_vozacke_dozvole, vozacka_dozvola_vazi_do, termin_id, startni_broj, takmicarska_licenca, grupa) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssssssiis", $full_name, $datum_rodjenja, $kontakt_telefon, $email, $motocikl_i_zapremina, $broj_vozacke_dozvole, $vozacka_dozvola_vazi_do, $termin_id, $startni_broj, $takmicarska_licenca, $grupa);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Prijava uspešna!"]);
} else {
    echo json_encode(["error" => "Greška prilikom prijave: " . $stmt->error]);
}

$stmt->close();
$mysqli->close();
