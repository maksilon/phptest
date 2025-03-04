<?php
// backend/api/updateRegistration.php

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

$id = $data['id'] ?? 0;
$full_name = $data['full_name'] ?? '';
$datum_rodjenja = $data['datum_rodjenja'] ?? '';
$kontakt_telefon = $data['kontakt_telefon'] ?? '';
$email = $data['email'] ?? '';
$motocikl_i_zapremina = $data['motocikl_i_zapremina'] ?? '';
$vozacka_dozvola_vazi_do = $data['vozacka_dozvola_vazi_do'] ?? '';
$startni_broj = $data['startni_broj'] ?? 0;
$takmicarska_licenca = $data['takmicarska_licenca'] ?? 0;
$grupa = $data['grupa'] ?? '';

if (!$id || !$full_name || !$datum_rodjenja || !$kontakt_telefon || !$email || !$motocikl_i_zapremina || !$vozacka_dozvola_vazi_do || !$startni_broj || !$grupa) {
    echo json_encode(["error" => "Sva obavezna polja moraju biti popunjena."]);
    exit;
}

$stmt = $mysqli->prepare("
    UPDATE registrations SET 
      full_name = ?,
      datum_rodjenja = ?,
      kontakt_telefon = ?,
      email = ?,
      motocikl_i_zapremina = ?,
      vozacka_dozvola_vazi_do = ?,
      startni_broj = ?,
      takmicarska_licenca = ?,
      grupa = ?
    WHERE id = ?
");
$stmt->bind_param(
    "ssssssiiis",
    $full_name,
    $datum_rodjenja,
    $kontakt_telefon,
    $email,
    $motocikl_i_zapremina,
    $vozacka_dozvola_vazi_do,
    $startni_broj,
    $takmicarska_licenca,
    $grupa,
    $id
);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Podaci ažurirani."]);
} else {
    echo json_encode(["error" => "Greška pri ažuriranju: " . $stmt->error]);
}

$stmt->close();
$mysqli->close();
