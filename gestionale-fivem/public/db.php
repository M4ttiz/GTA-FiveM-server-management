<?php
// Connessione al database
$conn = new mysqli("localhost", "root", "", "fivem_gestionale");

// Controllo errori di connessione
if ($conn->connect_error) {
    die("Errore di connessione: " . $conn->connect_error);
}

// Imposta il charset per vedere correttamente accenti e caratteri speciali
$conn->set_charset("utf8mb4");
?>