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
    $calibro = $conn->real_escape_string($_POST['calibro'] ?? '');
    $tipo_mun = $conn->real_escape_string($_POST['tipo_munizione'] ?? '');
    $condizione = $conn->real_escape_string($_POST['condizione'] ?? 'buona');
    $note = $conn->real_escape_string($_POST['note_tecniche'] ?? '');
    $location = $conn->real_escape_string($_POST['location'] ?? '');
    $tipo = ($ruolo == 'armeria60') ? '60' : '200';
    if ($ruolo == 'admin') $tipo = $_POST['tipo_utente'];

    $conn->query("INSERT INTO weapons (nome_compratore, seriale, modello, tipo_utente, calibro, tipo_munizione, condizione, note_tecniche, location, data_registrazione) 
                  VALUES ('$intestatario', '$seriale', '$modello', '$tipo', '$calibro', '$tipo_mun', '$condizione', '$note', '$location', NOW())");
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
    <style>
        .weapon-card {
            background: #161b22;
            border: 1px solid #30363d;
            border-radius: 8px;
            margin-bottom: 16px;
            cursor: pointer;
            transition: all 0.2s ease;
            overflow: hidden;
        }

        .weapon-card:hover {
            border-color: #58a6ff;
            box-shadow: 0 3px 12px rgba(88, 166, 255, 0.15);
        }

        .weapon-header {
            padding: 16px;
            background: #0d1117;
            border-bottom: 1px solid #30363d;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-weight: 600;
        }

        .weapon-details {
            display: none;
            padding: 16px;
            background: #0d1117;
            border-top: 1px solid #30363d;
            color: #8b949e;
            font-size: 0.95em;
            line-height: 1.8;
        }

        .detail-row {
            display: grid;
            grid-template-columns: 200px 1fr;
            margin-bottom: 8px;
            padding: 4px 0;
        }

        .detail-label {
            color: #f0883e;
            font-weight: 600;
        }

        .detail-value {
            color: #c9d1d9;
        }

        .weapon-status-badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.85em;
        }

        .badge-active {
            background: #238636;
            color: #fff;
        }

        .badge-revoked {
            background: #da3633;
            color: #fff;
        }

        .btn-action {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 0.85em;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.2s ease;
            border: 1px solid;
            margin-top: 12px;
        }

        .btn-revoca {
            color: #f85149;
            border-color: #da3633;
        }

        .btn-revoca:hover {
            background: #da3633;
            color: #fff;
            box-shadow: 0 1px 3px rgba(218, 54, 51, 0.3);
        }
    </style>
    <script>
        function toggleWeapon(id) {
            var el = document.getElementById('weapon-details-' + id);
            el.style.display = (el.style.display === 'block') ? 'none' : 'block';
        }
    </script>
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
            <div>
                <label>Calibro</label>
                <input type="text" name="calibro" placeholder="es. 9x21mm">
            </div>
            <div>
                <label>Tipo Munizione</label>
                <input type="text" name="tipo_munizione" placeholder="es. Pallottole FMJ">
            </div>
            <div>
                <label>Condizione</label>
                <select name="condizione">
                    <option value="buona">Buona</option>
                    <option value="ragionevole">Ragionevole</option>
                    <option value="cattiva">Cattiva</option>
                </select>
            </div>
            <div>
                <label>Localizzazione</label>
                <input type="text" name="location" placeholder="es. Armeria Nord">
            </div>
            <div>
                <label>Note Tecniche</label>
                <textarea name="note_tecniche" placeholder="Note aggiuntive..." rows="2" style="resize: vertical;"></textarea>
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

    <table class="weapon-table" style="display:none;">
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

    <!-- NUOVA VISTA: Card dettagliate -->
    <div class="weapons-list">
        <?php
        $result = $conn->query("SELECT * FROM weapons ORDER BY data_registrazione DESC");
        if ($result && $result->num_rows > 0) {
            while($row = $result->fetch_assoc()):
                $status_badge = ($row['revocata'] == 1) ? 
                    '<span class="weapon-status-badge badge-revoked">REVOCATA</span>' : 
                    '<span class="weapon-status-badge badge-active">ATTIVA</span>';
        ?>
        <div class="weapon-card" onclick="toggleWeapon(<?php echo $row['id']; ?>)">
            <div class="weapon-header">
                <div>
                    <strong style="color: #f0883e;">ARMA <?php echo htmlspecialchars($row['seriale']); ?></strong><br>
                    <small style="color: #8b949e; margin-top: 4px; display: block;">Modello: <?php echo htmlspecialchars($row['modello']); ?></small>
                </div>
                <div style="text-align: right;">
                    <?php echo $status_badge; ?><br>
                    <small style="color: #8b949e; margin-top: 4px; display: block;">Clicca per dettagli ▼</small>
                </div>
            </div>

            <div id="weapon-details-<?php echo $row['id']; ?>" class="weapon-details">
                <div class="detail-row">
                    <span class="detail-label">SERIALE</span>
                    <span class="detail-value"><?php echo htmlspecialchars($row['seriale']); ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">MODELLO</span>
                    <span class="detail-value"><?php echo htmlspecialchars($row['modello']); ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">CALIBRO</span>
                    <span class="detail-value"><?php echo $row['calibro'] ? htmlspecialchars($row['calibro']) : '<em style="color:#6e7681;">Non specificato</em>'; ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">TIPO MUNIZIONE</span>
                    <span class="detail-value"><?php echo $row['tipo_munizione'] ? htmlspecialchars($row['tipo_munizione']) : '<em style="color:#6e7681;">Non specificato</em>'; ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">CONDIZIONE</span>
                    <span class="detail-value"><?php echo htmlspecialchars($row['condizione']); ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">PROPRIETARIO</span>
                    <span class="detail-value"><?php echo htmlspecialchars($row['nome_compratore']); ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">LOCALIZZAZIONE</span>
                    <span class="detail-value"><?php echo $row['location'] ? htmlspecialchars($row['location']) : '<em style="color:#6e7681;">Non specificato</em>'; ?></span>
                </div>
                <?php if($row['note_tecniche']): ?>
                <div class="detail-row" style="grid-template-columns: 200px 1fr;">
                    <span class="detail-label">NOTE TECNICHE</span>
                    <span class="detail-value"><?php echo nl2br(htmlspecialchars($row['note_tecniche'])); ?></span>
                </div>
                <?php endif; ?>
                <div class="detail-row">
                    <span class="detail-label">DATA REGISTRAZIONE</span>
                    <span class="detail-value"><?php echo date('d/m/Y H:i', strtotime($row['data_registrazione'])); ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">CATEGORIA</span>
                    <span class="detail-value"><?php echo ($row['tipo_utente'] == 60) ? 'CAT. 60 (Civile)' : 'CAT. 200 (Speciale)'; ?></span>
                </div>

                <?php if($row['revocata'] == 0 && !in_array($ruolo, ['armeria60', 'armeria200'])): ?>
                <div style="margin-top: 16px; border-top: 1px solid #30363d; padding-top: 12px;">
                    <a href="weapons.php?revoca=<?php echo $row['id']; ?>" class="btn-action btn-revoca" onclick="event.stopPropagation(); return confirm('Revocare questa arma?');">🔒 REVOCA ARMA</a>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <?php 
            endwhile;
        } else {
            echo "<div style=\"text-align:center; padding:40px; background:#161b22; border:1px solid #30363d; border-radius:8px; color:#8b949e;\">Nessun cespite registrato</div>";
        }
        ?>
    </div>
</div>
</body>
</html>