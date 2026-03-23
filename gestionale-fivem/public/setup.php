<?php
/**
 * SETUP SCRIPT - Inizializzazione Audit Logging
 * Esegui questo file una sola volta per creare la tabella audit_logs
 * 
 * URL: http://localhost/public/setup.php
 */

include('db.php');

echo "<!DOCTYPE html>
<html lang='it'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Setup - Gestionale FiveM</title>
    <style>
        body { font-family: Arial, sans-serif; background: #0d1117; color: #c9d1d9; padding: 20px; }
        .container { max-width: 600px; margin: 40px auto; background: #161b22; padding: 30px; border-radius: 8px; border: 1px solid #30363d; }
        h1 { color: #f0883e; margin-top: 0; }
        .success { color: #3fb950; padding: 10px; background: #0d3d22; border: 1px solid #238636; border-radius: 6px; margin: 10px 0; }
        .error { color: #f85149; padding: 10px; background: #3d1f1f; border: 1px solid #da3633; border-radius: 6px; margin: 10px 0; }
        .info { color: #79c0ff; padding: 10px; background: #0d2d4d; border: 1px solid #0969da; border-radius: 6px; margin: 10px 0; }
        code { background: #0d1117; padding: 2px 6px; border-radius: 3px; }
    </style>
</head>
<body>
<div class='container'>
    <h1>⚙️ Setup Gestionale-FiveM</h1>";

// Verifica della connessione al database
if ($conn->connect_error) {
    echo "<div class='error'>❌ Errore di connessione al database: " . $conn->connect_error . "</div>";
} else {
    echo "<div class='success'>✅ Connessione al database: OK</div>";
}

// Crea la tabella audit_logs
echo "<h2>📋 Creazione Tabella Audit Logs</h2>";

if (create_audit_table()) {
    echo "<div class='success'>✅ Tabella <code>audit_logs</code> creata/verificata con successo!</div>";
} else {
    echo "<div class='error'>❌ Errore nella creazione della tabella: " . $conn->error . "</div>";
}

// Verifica che la tabella esista
$sql = "SELECT COUNT(*) as count FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_NAME='audit_logs' AND TABLE_SCHEMA=DATABASE()";
$result = $conn->query($sql);
$row = $result->fetch_assoc();

if ($row['count'] > 0) {
    echo "<div class='success'>✅ Tabella audit_logs confermata nel database</div>";
} else {
    echo "<div class='error'>❌ Tabella audit_logs non trovata</div>";
}

// Info finali
echo "
    <h2>📝 Informazioni Setup</h2>
    <div class='info'>
        <strong>✓ Completato:</strong><br>
        - Tabella audit_logs creata<br>
        - Security headers abilitati<br>
        - Funzioni di logging caricate<br>
        - Login logging attivato
    </div>
    
    <h2>🚀 Prossimi Passi</h2>
    <div class='info'>
        <strong>1. Visita la pagina admin per visualizzare i log:</strong><br>
        <code><a href='audit_logs.php' style='color: #79c0ff;'>http://localhost/public/audit_logs.php</a></code>
        <br><br>
        <strong>2. Modifica i file di gestione (weapons.php, reports.php, etc.) per aggiungere log_audit() nelle operazioni CRUD</strong>
        <br><br>
        <strong>3. Elimina questo file (setup.php) dopo l'esecuzione</strong>
    </div>
    
    <div style='margin-top: 30px; text-align: center;'>
        <a href='index.php' style='color: #f0883e; text-decoration: none;'>← Torna al Login</a>
    </div>
</div>
</body>
</html>";

$conn->close();
?>
