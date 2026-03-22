<?php
session_start();
include('db.php');

if (!isset($_SESSION['user'])) { header("Location: index.php"); exit(); }
$user = $_SESSION['user'];
$ruolo = $user['ruolo'];

// --- 🛡️ SECURITY CHECK: L'Armeria non deve nemmeno entrare ---
if (in_array($ruolo, ['armeria60', 'armeria200'])) {
    header("Location: dashboard.php");
    exit();
}

// --- ⚙️ PERMESSI AZIONI ---
$can_edit = in_array($ruolo, ['admin', 'procura', 'marshall']);

// --- 💾 LOGICA: AGGIUNGI AZIENDA ---
if (isset($_POST['add_azienda']) && $can_edit) {
    $nome = $conn->real_escape_string($_POST['nome_azienda']);
    $titolare = $conn->real_escape_string($_POST['titolare']);
    $conn->query("INSERT INTO aziende (nome, titolare) VALUES ('$nome', '$titolare')");
}

// --- 💾 LOGICA: AGGIUNGI DIPENDENTE ---
if (isset($_POST['add_dipendente']) && $can_edit) {
    $az_id = intval($_POST['azienda_id']);
    $nome_dip = $conn->real_escape_string($_POST['nome_dipendente']);
    $ruolo_dip = $conn->real_escape_string($_POST['ruolo_dipendente']);
    $conn->query("INSERT INTO dipendenti (azienda_id, nome, ruolo) VALUES ($az_id, '$nome_dip', '$ruolo_dip')");
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Censimento Attività - Sistema Gestionale</title>
    <link rel="stylesheet" href="style.css?v=999" type="text/css">
    <script>
        function toggleAzienda(id) {
            let el = document.getElementById('az-' + id);
            el.style.display = (el.style.display === 'block') ? 'none' : 'block';
        }
    </script>
</head>
<body>

<div class="wrapper">
    <div class="header-flex" style="margin-bottom: 28px;">
        <div>
            <h1>Censimento Attività e Imprese</h1>
            <p style="color: #8b949e; margin: 4px 0 0 0;">Registro aziende, titolari e organigrammi dipendenti</p>
        </div>
        <a href="dashboard.php" class="btn" style="text-decoration:none; height: fit-content;">← Dashboard</a>
    </div>

    <?php if($can_edit): ?>
    <div class="form-section">
        <h3>Nuova Registrazione Impresa</h3>
        <form method="POST" class="form-row">
            <div>
                <label>Nome Attività</label>
                <input type="text" name="nome_azienda" placeholder="es. Vanilla Unicorn" required>
            </div>
            <div>
                <label>Proprietario / Legale Rappresentante</label>
                <input type="text" name="titolare" placeholder="es. Franklin Clinton" required>
            </div>
            <div style="display: flex; align-items: flex-end;">
                <button type="submit" name="add_azienda" class="btn-save" style="width: 100%; margin: 0;">REGISTRA</button>
            </div>
        </form>
    </div>
    <?php endif; ?>

    <div class="aziende-list">
        <?php
        $res = $conn->query("SELECT * FROM aziende ORDER BY nome ASC");
        while($az = $res->fetch_assoc()):
        ?>
        <div class="azienda-card">
            <div class="azienda-header" onclick="toggleAzienda(<?php echo $az['id']; ?>)">
                <div>
                    <strong><?php echo strtoupper(htmlspecialchars($az['nome'])); ?></strong><br>
                    <small style="margin-top: 4px; display: block;">Titolare: <?php echo htmlspecialchars($az['titolare']); ?></small>
                </div>
                <div style="text-align: right; color: #f0883e;">DIPENDENTI ▼</div>
            </div>

            <div id="az-<?php echo $az['id']; ?>" class="dipendenti-box">
                <h4 style="margin: 0 0 12px 0; color: #f0883e; font-size: 0.95em;">Organigramma</h4>
                <table class="table-dipendenti">
                    <thead>
                        <tr><th>Nome Dipendente</th><th>Qualifica / Ruolo</th></tr>
                    </thead>
                    <tbody>
                        <?php
                        $dip_res = $conn->query("SELECT * FROM dipendenti WHERE azienda_id = ".$az['id']);
                        if($dip_res->num_rows > 0):
                            while($dip = $dip_res->fetch_assoc()):
                        ?>
                        <tr>
                            <td><strong><?php echo htmlspecialchars($dip['nome']); ?></strong></td>
                            <td><?php echo htmlspecialchars($dip['ruolo']); ?></td>
                        </tr>
                        <?php endwhile; else: ?>
                        <tr><td colspan="2" style="text-align:center; color: #f0883e;">Nessun dipendente censito</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>

                <?php if($can_edit): ?>
                <form method="POST" class="mini-form">
                    <input type="hidden" name="azienda_id" value="<?php echo $az['id']; ?>">
                    <input type="text" name="nome_dipendente" placeholder="Nome Dipendente" required style="flex:2; margin:0;">
                    <input type="text" name="ruolo_dipendente" placeholder="Ruolo (es. Barman)" required style="flex:1; margin:0;">
                    <button type="submit" name="add_dipendente" class="btn-save" style="margin:0;">AGGIUNGI</button>
                </form>
                <?php endif; ?>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
</div>

</body>
</html>