<?php
/**
 * MIGRATION SCRIPT - Aggiunge colonne per sincronizzazione FiveM
 * 
 * Esegui questo script una sola volta:
 * http://localhost/gestionale_fivem_master/gestionale-fivem/public/migrate_weapons_table.php
 */

include('db.php');

echo "<!DOCTYPE html>
<html lang='it'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Database Migration - Weapons Sync</title>
    <style>
        body { font-family: Arial, sans-serif; background: #0d1117; color: #c9d1d9; padding: 20px; }
        .container { max-width: 600px; margin: 40px auto; background: #161b22; padding: 30px; border-radius: 8px; }
        .success { color: #3fb950; padding: 10px; background: #0d3d22; border: 1px solid #238636; border-radius: 6px; margin: 10px 0; }
        .error { color: #f85149; padding: 10px; background: #3d1f1f; border: 1px solid #da3633; border-radius: 6px; margin: 10px 0; }
        .info { color: #79c0ff; padding: 10px; background: #0d2d4d; border: 1px solid #0969da; border-radius: 6px; margin: 10px 0; }
        h1 { color: #f0883e; margin-top: 0; }
        code { background: #0d1117; padding: 2px 6px; border-radius: 3px; }
    </style>
</head>
<body>
<div class='container'>
    <h1>🔄 Database Migration</h1>";

// Funzione per verificare se colonna esiste
function column_exists($conn, $table, $column) {
    $result = $conn->query("SHOW COLUMNS FROM $table LIKE '$column'");
    return $result && $result->num_rows > 0;
}

// ============ MIGRAZIONI ============

$migrations = [
    [
        'name' => 'Aggiunta colonna server_sync',
        'column' => 'server_sync',
        'sql' => "ALTER TABLE weapons ADD COLUMN server_sync VARCHAR(50) DEFAULT 'main_server' AFTER data_ora"
    ],
    [
        'name' => 'Aggiunta colonna citizen_id',
        'column' => 'citizen_id',
        'sql' => "ALTER TABLE weapons ADD COLUMN citizen_id VARCHAR(50) AFTER server_sync"
    ],
    [
        'name' => 'Aggiunta colonna prezzo',
        'column' => 'prezzo',
        'sql' => "ALTER TABLE weapons ADD COLUMN prezzo DECIMAL(10, 2) DEFAULT 0 AFTER citizen_id"
    ]
];

$total = count($migrations);
$completed = 0;

foreach ($migrations as $migration) {
    $column = $migration['column'];
    $sql = $migration['sql'];
    
    if (column_exists($conn, 'weapons', $column)) {
        echo "<div class='info'>ℹ️ {$migration['name']}: Colonna già esistente</div>";
        $completed++;
    } else {
        if ($conn->query($sql) === TRUE) {
            echo "<div class='success'>✅ {$migration['name']}: Aggiunta con successo</div>";
            $completed++;
        } else {
            echo "<div class='error'>❌ {$migration['name']}: Errore - " . $conn->error . "</div>";
        }
    }
}

// ============ SCHEMA FINALE ============

echo "<h2>📋 Schema della Tabella weapons (Finale)</h2>";
echo "<div class='info'>";

$result = $conn->query("DESCRIBE weapons");
echo "<table style='width: 100%; border-collapse: collapse;'>";
echo "<tr style='background: #0d1117;'><th style='border: 1px solid #30363d; padding: 8px;'>Campo</th><th style='border: 1px solid #30363d; padding: 8px;'>Tipo</th><th style='border: 1px solid #30363d; padding: 8px;'>Null</th><th style='border: 1px solid #30363d; padding: 8px;'>Default</th></tr>";

while ($row = $result->fetch_assoc()) {
    echo "<tr>";
    echo "<td style='border: 1px solid #30363d; padding: 8px;'><code>" . $row['Field'] . "</code></td>";
    echo "<td style='border: 1px solid #30363d; padding: 8px;'>" . $row['Type'] . "</td>";
    echo "<td style='border: 1px solid #30363d; padding: 8px;'>" . ($row['Null'] === 'YES' ? 'YES' : 'NO') . "</td>";
    echo "<td style='border: 1px solid #30363d; padding: 8px;'>" . ($row['Default'] ?? '-') . "</td>";
    echo "</tr>";
}

echo "</table>";
echo "</div>";

// ============ SUMMARY ============

echo "<h2>✅ Summary</h2>";
echo "<div class='success'>
    <strong>Migrazioni Completate:</strong> $completed / $total
    <br>
    Tutte le colonne sono ora disponibili per la sincronizzazione FiveM!
</div>";

echo "<div style='margin-top: 30px; text-align: center;'>
    <a href='index.php' style='color: #f0883e; text-decoration: none;'>← Torna al Login</a>
</div>";

echo "</div>
</body>
</html>";

$conn->close();
?>
