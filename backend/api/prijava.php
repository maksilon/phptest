<?php
// backend/api/prijava.php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

$mysqli = new mysqli("db", "phptestuser", "phptestpass", "phptest");
if ($mysqli->connect_errno) {
    echo json_encode(["error" => "Ne mogu da se povežem na bazu: " . $mysqli->connect_error]);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

$full_name              = $data['full_name']              ?? '';
$datum_rodjenja         = $data['datum_rodjenja']         ?? '';
$kontakt_telefon        = $data['kontakt_telefon']        ?? '';
$email                  = $data['email']                  ?? '';
$motocikl_i_zapremina   = $data['motocikl_i_zapremina']   ?? '';
$broj_vozacke_dozvole   = $data['broj_vozacke_dozvole']   ?? '';
$vozacka_dozvola_vazi_do= $data['vozacka_dozvola_vazi_do'] ?? '';
$termin_id              = $data['termin_id']              ?? 0;
$startni_broj           = $data['startni_broj']           ?? 0;
$takmicarska_licenca    = $data['takmicarska_licenca']    ?? 0;
$grupa                  = $data['grupa']                  ?? '';

if (!$full_name || !$datum_rodjenja || !$kontakt_telefon || !$email || !$motocikl_i_zapremina || !$broj_vozacke_dozvole || !$vozacka_dozvola_vazi_do || !$termin_id || !$startni_broj || !$grupa) {
    echo json_encode(["error" => "Sva obavezna polja moraju biti popunjena."]);
    exit;
}

$stmt = $mysqli->prepare("
    INSERT INTO registrations (
      full_name, datum_rodjenja, kontakt_telefon, email,
      motocikl_i_zapremina, broj_vozacke_dozvole, vozacka_dozvola_vazi_do,
      termin_id, startni_broj, takmicarska_licenca, grupa
    )
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
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
    $res = $mysqli->query("SELECT naziv, iznos FROM termini WHERE id = $termin_id");
    $row = $res->fetch_assoc();
    $iznos = $row['iznos'] ?? 'Nepoznat iznos';
    $svrha_uplate = $row['naziv'] ?? 'Prijava';

    require_once __DIR__ . '/../sendConfirmation.php';
    sendConfirmationEmail($email, $full_name, $broj_vozacke_dozvole, $svrha_uplate, $iznos);

    echo json_encode(["success" => true, "message" => "Prijava uspešno zabeležena i email poslat."]);
} else {
    echo json_encode(["error" => "Greška prilikom prijave: " . $stmt->error]);
}

$stmt->close();
$mysqli->close();
