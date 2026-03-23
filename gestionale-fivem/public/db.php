<?php
// ============ SECURITY HEADERS ============
// Imposta gli header di sicurezza prima di qualsiasi output
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('X-XSS-Protection: 1; mode=block');
header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline'; style-src 'self' 'unsafe-inline'; img-src 'self' data:; font-src 'self'; connect-src 'self'");
header('Referrer-Policy: strict-origin-when-cross-origin');
header('Permissions-Policy: geolocation=(), microphone=(), camera=()');

// Connessione al database
$conn = new mysqli("localhost", "root", "", "fivem_gestionale");

// Controllo errori di connessione
if ($conn->connect_error) {
    die("Errore di connessione: " . $conn->connect_error);
}

// Imposta il charset per vedere correttamente accenti e caratteri speciali
$conn->set_charset("utf8mb4");

// Carica le funzioni di sicurezza
require_once 'functions.php';
?>