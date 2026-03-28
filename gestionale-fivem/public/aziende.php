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
$can_edit = in_array($ruolo, ['admin', 'procura', 'marshall', 'fib']);

// --- 💾 LOGICA: AGGIUNGI AZIENDA ---
if (isset($_POST['add_azienda']) && $can_edit) {
    $nome = $conn->real_escape_string($_POST['nome_azienda']);
    $titolare = $conn->real_escape_string($_POST['titolare']);
    $indirizzo = $conn->real_escape_string($_POST['indirizzo'] ?? '');
    $telefono = $conn->real_escape_string($_POST['telefono'] ?? '');
    $email = $conn->real_escape_string($_POST['email'] ?? '');
    $tipo = $conn->real_escape_string($_POST['tipo_business'] ?? 'commerciale');
    $conn->query("INSERT INTO aziende (nome, titolare, indirizzo, telephone, email, tipo_business) VALUES ('$nome', '$titolare', '$indirizzo', '$telefono', '$email', '$tipo')");
}

// --- 💾 LOGICA: AGGIUNGI DIPENDENTE ---
if (isset($_POST['add_dipendente']) && $can_edit) {
    $az_id = intval($_POST['azienda_id']);
    $nome_dip = $conn->real_escape_string($_POST['nome_dipendente']);
    $ruolo_dip = $conn->real_escape_string($_POST['ruolo_dipendente']);
    $telefono_dip = $conn->real_escape_string($_POST['telefono_dipendente'] ?? '');
    $email_dip = $conn->real_escape_string($_POST['email_dipendente'] ?? '');
    $data_ass = $_POST['data_assunzione'] ?? NULL;
    $conn->query("INSERT INTO dipendenti (azienda_id, nome, ruolo, telefono, email, data_assunzione) VALUES ($az_id, '$nome_dip', '$ruolo_dip', '$telefono_dip', '$email_dip', ".($data_ass ? "'$data_ass'" : "NULL").")");
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
            <div>
                <label>Indirizzo</label>
                <input type="text" name="indirizzo" placeholder="es. Downtown, Strada Principale 123">
            </div>
            <div>
                <label>Telefono</label>
                <input type="tel" name="telefono" placeholder="es. 555-0123">
            </div>
            <div>
                <label>Email</label>
                <input type="email" name="email" placeholder="es. info@azienda.com">
            </div>
            <div>
                <label>Tipo di Business</label>
                <select name="tipo_business">
                    <option value="commerciale">Commerciale</option>
                    <option value="ristorazione">Ristorazione</option>
                    <option value="intrattenimento">Intrattenimento</option>
                    <option value="servizi">Servizi</option>
                    <option value="produzione">Produzione</option>
                    <option value="altro">Altro</option>
                </select>
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
                    <small style="margin-top: 2px; display: block; color: #8b949e; font-size: 0.85em;">
                        <?php echo $az['indirizzo'] ? '📍 ' . htmlspecialchars($az['indirizzo']) : ''; ?>
                    </small>
                </div>
                <div style="text-align: right; color: #f0883e;">
                    <small style="display: block; margin-bottom: 4px;">TIPO: <?php echo strtoupper($az['tipo_business'] ?? 'N/D'); ?></small>
                    DIPENDENTI ▼
                </div>
            </div>

            <div id="az-<?php echo $az['id']; ?>" class="dipendenti-box">
                <div style="margin-bottom:12px; padding-bottom:12px; border-bottom:1px solid #30363d;">
                    <h4 style="margin: 0 0 8px 0; color: #f0883e; font-size: 0.95em;">📊 INFORMAZIONI AZIENDA</h4>
                    <div style="display: grid; grid-template-columns: 150px 1fr; gap: 8px; font-size: 0.9em; color:#8b949e;">
                        <?php if($az['indirizzo']): ?>
                        <strong>Indirizzo:</strong><span><?php echo htmlspecialchars($az['indirizzo']); ?></span>
                        <?php endif; ?>
                        <?php if($az['telephone']): ?>
                        <strong>Telefono:</strong><span><?php echo htmlspecialchars($az['telephone']); ?></span>
                        <?php endif; ?>
                        <?php if($az['email']): ?>
                        <strong>Email:</strong><span><?php echo htmlspecialchars($az['email']); ?></span>
                        <?php endif; ?>
                        <strong>Tipo Business:</strong><span><?php echo htmlspecialchars($az['tipo_business'] ?? 'Non specificato'); ?></span>
                        <strong>Data Registrazione:</strong><span><?php echo date('d/m/Y', strtotime($az['data_registrazione'])); ?></span>
                        <strong>Stato:</strong><span style="color: #238636; font-weight: 600;"><?php echo strtoupper($az['stato'] ?? 'ATTIVA'); ?></span>
                    </div>
                </div>

                <h4 style="margin: 12px 0 12px 0; color: #f0883e; font-size: 0.95em;">👥 ORGANIGRAMMA</h4>
                <table class="table-dipendenti">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Qualifica</th>
                            <th>Telefono</th>
                            <th>Email</th>
                            <th>Data Assunzione</th>
                        </tr>
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
                            <td><?php echo $dip['telefono'] ? htmlspecialchars($dip['telefono']) : '<em style="color:#6e7681;">-</em>'; ?></td>
                            <td><?php echo $dip['email'] ? htmlspecialchars($dip['email']) : '<em style="color:#6e7681;">-</em>'; ?></td>
                            <td><?php echo $dip['data_assunzione'] ? date('d/m/Y', strtotime($dip['data_assunzione'])) : '<em style="color:#6e7681;">-</em>'; ?></td>
                        </tr>
                        <?php endwhile; else: ?>
                        <tr><td colspan="5" style="text-align:center; color: #f0883e;">Nessun dipendente censito</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>

                <?php if($can_edit): ?>
                <div style="margin-top: 16px; padding-top: 12px; border-top: 1px solid #30363d;">
                    <form method="POST" class="mini-form" style="display: grid; grid-template-columns: 1fr 1fr 1fr 1fr 1fr auto; gap: 8px; align-items: flex-end;">
                        <input type="hidden" name="azienda_id" value="<?php echo $az['id']; ?>">
                        <input type="text" name="nome_dipendente" placeholder="Nome" required style="margin:0;">
                        <input type="text" name="ruolo_dipendente" placeholder="Ruolo" required style="margin:0;">
                        <input type="tel" name="telefono_dipendente" placeholder="Telefono" style="margin:0;">
                        <input type="email" name="email_dipendente" placeholder="Email" style="margin:0;">
                        <input type="date" name="data_assunzione" style="margin:0;">
                        <button type="submit" name="add_dipendente" class="btn-save" style="margin:0;">AGGIUNGI</button>
                    </form>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
</div>

</body>
</html>