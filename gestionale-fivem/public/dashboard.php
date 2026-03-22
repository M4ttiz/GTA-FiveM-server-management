<?php
session_start();
if (!isset($_SESSION['user'])) { header("Location: index.php"); exit(); }
$user = $_SESSION['user'];
$ruolo = $user['ruolo'];
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - FiveM Management System</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <nav>
        <span>FIVEM Management System | <strong><?php echo strtoupper($ruolo); ?></strong></span>
        <a href="logout.php" style="color:#f85149;">[ LOGOUT ]</a>
    </nav>

    <div style="margin-top: 32px;">
        <h2>Pannello di Controllo</h2>
        <p style="color: #8b949e; margin-top: 4px;">Seleziona un'area per accedere alle funzioni disponibili</p>
    </div>

    <div class="dashboard-grid">
        
        <div class="card" onclick="location.href='weapons.php'" style="cursor:pointer;">
            <h3>🔫 Archivio Balistico</h3>
            <p>Gestione armi, matricole e rilevamenti ballistici</p>
        </div>

        <?php if(in_array($ruolo, ['admin', 'cid', 'lssd', 'alto_comando_lspd', 'alto_comando_lssd'])): ?>
        <div class="card" onclick="location.href='reports.php'" style="cursor:pointer;">
            <h3>📋 Report Investigativi</h3>
            <p>Archivio centralizzato atti investigativi</p>
        </div>
        <?php endif; ?>

        <?php if(!in_array($ruolo, ['armeria60', 'armeria200'])): ?>
        <div class="card" onclick="location.href='aziende.php'" style="cursor:pointer;">
            <h3>🏢 Censimento Attività</h3>
            <p>Registro imprese, pendenti e dipendenti</p>
        </div>
        <?php endif; ?>

        <?php if(in_array($ruolo, ['admin', 'marshall'])): ?>
        <div class="card" onclick="location.href='federal.php'" style="cursor:pointer;">
            <h3>⚖️ Federal Service</h3>
            <p>Fascicoli indagini federali</p>
        </div>
        <?php endif; ?>

        <?php if(in_array($ruolo, ['admin', 'iaa'])): ?>
        <div class="card" onclick="location.href='internal.php'" style="cursor:pointer;">
            <h3>🛡️ Internal Affairs</h3>
            <p>Rapporti e investigazioni interne</p>
        </div>
        <?php endif; ?>

        <?php if($ruolo === 'admin'): ?>
        <div class="card" onclick="location.href='users.php'" style="cursor:pointer;">
            <h3>⚙️ Gestione Utenti</h3>
            <p>Modifica permessi, password e ruoli degli utenti</p>
        </div>
        <?php endif; ?>

    </div>
</div>
</body>
</html>