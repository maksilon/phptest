<?php
// backend/api/prijava.php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

// Povezivanje sa bazom
$mysqli = new mysqli("db", "phptestuser", "phptestpass", "phptest");
if ($mysqli->connect_errno) {
    echo json_encode(["error" => "Ne mogu da se povežem sa bazom: " . $mysqli->connect_error]);
    exit;
}

// Učitavamo JSON telo zahteva
$data = json_decode(file_get_contents('php://input'), true);

// Dodela promenljivih (osnovna validacija)
$full_name            = $data['full_name']            ?? '';
$datum_rodjenja       = $data['datum_rodjenja']       ?? '';
$kontakt_telefon      = $data['kontakt_telefon']      ?? '';
$email                = $data['email']                ?? '';
$motocikl_i_zapremina = $data['motocikl_i_zapremina'] ?? '';
$broj_vozacke_dozvole = $data['broj_vozacke_dozvole'] ?? '';
$vozacka_dozvola_vazi_do = $data['vozacka_dozvola_vazi_do'] ?? '';
$termin_id            = $data['termin_id']            ?? 0;
$startni_broj         = $data['startni_broj']         ?? 0;
$takmicarska_licenca  = $data['takmicarska_licenca']  ?? 0;
$grupa                = $data['grupa']                ?? '';

// Primer dodatne validacije:
if (!$full_name || !$datum_rodjenja || !$kontakt_telefon || !$email) {
    echo json_encode(["error" => "Obavezna polja nisu popunjena."]);
    exit;
}

// Ubacivanje podataka u tabelu 'registrations'
$stmt = $mysqli->prepare("
    INSERT INTO registrations (
        full_name, datum_rodjenja, kontakt_telefon, email, 
        motocikl_i_zapremina, broj_vozacke_dozvole, vozacka_dozvola_vazi_do, 
        termin_id, startni_broj, takmicarska_licenca, grupa
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
");
$stmt->bind_param(
    "ssssssssiis",
    $full_name,
    $datum_rodjenja,
    $kontakt_telefon,
    $email,
    $motocikl_i_zapremina,
    $broj_vozacke_dozvole,
    $vozacka_dozvola_vazi_do,
    $termin_id,
    $startni_broj,
    $takmicarska_licenca,
    $grupa
);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Prijava uspešno zabeležena"]);
} else {
    echo json_encode(["error" => "Greška prilikom prijave: " . $stmt->error]);
}

$stmt->close();
$mysqli->close();
