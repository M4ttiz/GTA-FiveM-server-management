<?php
session_start();
include('db.php');

if (!isset($_SESSION['user'])) { header("Location: index.php"); exit(); }
$user = $_SESSION['user'];
$ruolo = $user['ruolo'];

// --- LOGICA AZIONI ---
if (isset($_POST['add_weapon']) && in_array($ruolo, ['armeria60', 'armeria200', 'admin'])) {
    $intestatario = $conn->real_escape_string($_POST['intestatario']);
    $seriale = $conn->real_escape_string($_POST['seriale']);
    $modello = $conn->real_escape_string($_POST['modello']);
    $tipo = ($ruolo == 'armeria60') ? '60' : '200';
    if ($ruolo == 'admin') $tipo = $_POST['tipo_utente'];

    // Dividiamo l'intestatario per compatibilità col DB esistente o usiamo un campo unico se preferisci
    $conn->query("INSERT INTO weapons (nome_compratore, seriale, modello, tipo_utente) VALUES ('$intestatario', '$seriale', '$modello', '$tipo')");
}

if (isset($_GET['revoca']) && !in_array($ruolo, ['armeria60', 'armeria200'])) {
    $id = intval($_GET['revoca']);
    $conn->query("UPDATE weapons SET revocata = 1 WHERE id = $id");
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Archivio Balistico - Sistema Gestionale</title>
    <link rel="stylesheet" href="style.css?v=999" type="text/css">
</head>
<body>
<div class="container">
    <div class="header-flex" style="margin-bottom: 28px;">
        <div>
            <h1>Archivio Balistico Centrale</h1>
            <p style="color: #8b949e; margin: 4px 0 0 0;">Gestione cespiti, matricole e tracciamento armi</p>
        </div>
        <a href="dashboard.php" class="btn" style="text-decoration: none; height: fit-content;">← Dashboard</a>
    </div>

    <?php if(in_array($ruolo, ['armeria60', 'armeria200', 'admin'])): ?>
    <div class="form-section">
        <h3>Registrazione Nuovo Cespite</h3>
        <form method="POST" class="form-row">
            <div>
                <label>Intestatario</label>
                <input type="text" name="intestatario" placeholder="Nome e Cognome" required>
            </div>
            <div>
                <label>Matricola / Seriale</label>
                <input type="text" name="seriale" placeholder="es. WPN-9982" required>
            </div>
            <div>
                <label>Modello Arma</label>
                <input type="text" name="modello" placeholder="es. Beretta M9" required>
            </div>
            <?php if($ruolo == 'admin'): ?>
            <div>
                <label>Categoria</label>
                <select name="tipo_utente">
                    <option value="60">CAT. 60 (Civile)</option>
                    <option value="200">CAT. 200 (Speciale)</option>
                </select>
            </div>
            <?php endif; ?>
            <div style="display: flex; align-items: flex-end;">
                <button type="submit" name="add_weapon" class="btn-save" style="width: 100%; margin: 0;">REGISTRA</button>
            </div>
        </form>
    </div>
    <?php endif; ?>

    <table class="weapon-table">
        <thead>
            <tr>
                <th>Stato</th>
                <th>Matricola</th>
                <th>Modello</th>
                <th>Intestatario</th>
                <th>Data Rilascio</th>
                <th style="text-align: center;">Azioni</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $result = $conn->query("SELECT * FROM weapons ORDER BY data_ora DESC");
            while($row = $result->fetch_assoc()):
                $status_class = ($row['revocata'] == 1) ? 'status-revoked' : '';
            ?>
            <tr class="<?php echo $status_class; ?>">
                <td>
                    <?php if($row['revocata'] == 1): ?>
                        <span class="badge badge-revoked">REVOCATA</span>
                    <?php else: ?>
                        <span class="badge badge-active">ATTIVA</span>
                    <?php endif; ?>
                </td>
                <td><span class="seriale-text"><?php echo htmlspecialchars($row['seriale']); ?></span></td>
                <td><?php echo htmlspecialchars($row['modello']); ?></td>
                <td><?php echo htmlspecialchars($row['nome_compratore']); ?></td>
                <td style="font-size: 0.9em;"><?php echo date('d/m/Y H:i', strtotime($row['data_ora'])); ?></td>
                <td style="text-align: center;">
                    <?php if($row['revocata'] == 0 && !in_array($ruolo, ['armeria60', 'armeria200'])): ?>
                        <a href="weapons.php?revoca=<?php echo $row['id']; ?>" class="btn-action btn-revoca">REVOCA</a>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
</body>
</html>