<?php
session_start();
include('db.php');

if (!isset($_SESSION['user'])) { header("Location: index.php"); exit(); }
$user = $_SESSION['user'];
$ruolo = $user['ruolo'];

// 🛡️ SECURITY CHECK: Solo Marshall, FIB e Admin
if (!in_array($ruolo, ['admin', 'marshall', 'fib'])) {
    header("Location: dashboard.php");
    exit();
}

// 💾 LOGICA SALVATAGGIO: Categoria 'federal'
if (isset($_POST['save_federal'])) {
    $titolo = $conn->real_escape_string($_POST['titolo']);
    $desc = $conn->real_escape_string($_POST['descrizione']);
    $tipo_ind = $conn->real_escape_string($_POST['tipo_indagine'] ?? 'federale');
    $priorita = $conn->real_escape_string($_POST['priorita'] ?? 'alta');
    $vittime = intval($_POST['numero_vittime'] ?? 0);
    $importo = floatval($_POST['importo_stimato'] ?? 0);
    $location = $conn->real_escape_string($_POST['location_crimine'] ?? '');
    $agenti = $conn->real_escape_string($_POST['agenti_coinvolti'] ?? '');
    $autore = $user['username'];
    
    $sql = "INSERT INTO reports (titolo, descrizione, creato_da, ruolo_creatore, categoria, tipo_indagine, priorita, numero_vittime, importo_stimato, location_crimine, agenti_coinvolti, stato) 
            VALUES ('$titolo', '$desc', '$autore', '$ruolo', 'federal', '$tipo_ind', '$priorita', $vittime, $importo, '$location', '$agenti', 'aperto')";
    
    if($conn->query($sql)) {
        header("Location: federal.php");
        exit();
    } else {
        die("ERRORE DB: " . $conn->error);
    }
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Federal Service - Sistema Gestionale</title>
    <link rel="stylesheet" href="style.css">
    
    <style>
        .fed-card {
            background: #161b22;
            border: 1px solid #30363d;
            border-radius: 8px;
            padding: 24px;
            margin-bottom: 28px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.3);
        }

        .fed-card h3 {
            color: #f0883e;
            margin-top: 0;
        }

        .case-item {
            background: #161b22;
            border: 1px solid #30363d;
            border-radius: 8px;
            margin-bottom: 16px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.3);
            transition: all 0.2s ease;
        }

        .case-item:hover {
            border-color: #58a6ff;
            box-shadow: 0 3px 12px rgba(88, 166, 255, 0.15);
        }

        .case-head {
            padding: 16px;
            cursor: pointer;
            background: #0d1117;
            border-bottom: 1px solid #30363d;
            color: #f0883e;
            font-weight: 600;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: all 0.2s ease;
        }

        .case-head:hover {
            background: #161b22;
        }

        .case-body {
            display: none;
            padding: 16px;
            background: #0d1117;
            color: #8b949e;
            line-height: 1.6;
            white-space: pre-wrap;
            word-wrap: break-word;
            font-size: 0.95em;
        }

        .btn-fed {
            background: #1f6feb;
            color: #fff;
            border: 1px solid #30363d;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.3);
        }

        .btn-fed:hover {
            background: #388bfd;
            box-shadow: 0 3px 8px rgba(88, 166, 255, 0.2);
        }
    </style>
    
    <script>
        function toggleCase(id) {
            var el = document.getElementById('case-' + id);
            el.style.display = (el.style.display === 'block') ? 'none' : 'block';
        }
    </script>
</head>
<body>

<div class="container">
    <div class="header-flex" style="margin-bottom: 28px;">
        <div>
            <h1>Federal Service</h1>
            <p style="color: #8b949e; margin: 4px 0 0 0;">Fascicoli investigazioni federali</p>
        </div>
        <a href="dashboard.php" class="btn" style="text-decoration:none; height: fit-content;">← Dashboard</a>
    </div>

    <div class="fed-card">
        <h3>Apertura Fascicolo Federale</h3>
        <form method="POST">
            <div style="margin-bottom: 12px;">
                <label>Nome Caso / Identificativo Soggetto</label>
                <input type="text" name="titolo" placeholder="es. OPERAZIONE COYOTE" required>
            </div>
            <div style="margin-bottom: 12px;">
                <label>Tipo Indagine Federale</label>
                <select name="tipo_indagine">
                    <option value="federale">Federale Standard</option>
                    <option value="crimini_transfrontalieri">Crimini Transfrontalieri</option>
                    <option value="terrorismo">Terrorismo</option>
                    <option value="riciclaggio">Riciclaggio</option>
                    <option value="corruzione">Corruzione</option>
                    <option value="traffico">Traffico Persone/Droga</option>
                    <option value="altro">Altro</option>
                </select>
            </div>
            <div style="margin-bottom: 12px;">
                <label>Priorità Indagine</label>
                <select name="priorita">
                    <option value="bassa">Bassa</option>
                    <option value="media">Media</option>
                    <option value="alta" selected>Alta</option>
                    <option value="critica">Critica</option>
                </select>
            </div>
            <div style="margin-bottom: 12px;">
                <label>Numero Vittime / Soggetti Coinvolti</label>
                <input type="number" name="numero_vittime" min="0" placeholder="0">
            </div>
            <div style="margin-bottom: 12px;">
                <label>Importo Stimato (Criminale/Perdite)</label>
                <input type="number" name="importo_stimato" min="0" step="0.01" placeholder="0.00">
            </div>
            <div style="margin-bottom: 12px;">
                <label>Luogo Principale</label>
                <input type="text" name="location_crimine" placeholder="es. Distretto Nord">
            </div>
            <div style="margin-bottom: 12px;">
                <label>Agenti Federali Assegnati</label>
                <input type="text" name="agenti_coinvolti" placeholder="es. Spec. Agent Torres, Ag. Martinez">
            </div>
            <div style="margin-bottom: 12px;">
                <label>Dettagli dell'indagine federale</label>
                <textarea name="descrizione" rows="5" placeholder="Inserire i dettagli dell'indagine..." required style="resize: vertical;"></textarea>
            </div>
            <button type="submit" name="save_federal" class="btn btn-fed">REGISTRA FASCICOLO</button>
        </form>
    </div>

    <div class="case-list">
        <?php
        $res = $conn->query("SELECT * FROM reports WHERE categoria = 'federal' ORDER BY id DESC");
        if ($res && $res->num_rows > 0) {
            while($row = $res->fetch_assoc()) {
                echo '<div class="case-item">
                        <div class="case-head" onclick="toggleCase('.$row['id'].')">
                            <div>
                                <span style="color:#f0883e; font-weight:600;">CASO #'.$row['id'].' - '.htmlspecialchars($row['titolo']).'</span><br>
                                <small style="color:#8b949e; margin-top:4px; display:block;">Tipo: '.htmlspecialchars($row['tipo_indagine'] ?? 'federale').'</small>
                            </div>
                            <div style="text-align:right;">
                                <span style="display:inline-block; padding:4px 10px; border-radius:12px; font-weight:600; font-size:0.85em; background:'.
                                    (['bassa' => '#58a6ff', 'media' => '#f0883e', 'alta' => '#f85149', 'critica' => '#da3633'][$row['priorita'] ?? 'alta'] ?? '#f0883e').'; color:#fff;">
                                    '.strtoupper($row['priorita'] ?? 'ALTA').'
                                </span><br>
                                <small style="color:#8b949e; margin-top:4px; display:block;">DETTAGLI ▼</small>
                            </div>
                        </div>
                        <div id="case-'.$row['id'].'" class="case-body">
                            <div style="margin-bottom:12px;">
                                <strong style="color:#f0883e;">DATI ESSENZIALI</strong>
                            </div>';
                
                if($row['numero_vittime'] > 0) {
                    echo '<div style="margin-bottom:8px;"><small style="color:#8b949e;">👥 Soggetti Coinvolti:</small> <strong>'.$row['numero_vittime'].'</strong></div>';
                }
                if($row['importo_stimato'] > 0) {
                    echo '<div style="margin-bottom:8px;"><small style="color:#8b949e;">💰 Importo Stimato:</small> <strong>$'.number_format($row['importo_stimato'],2).'</strong></div>';
                }
                if($row['location_crimine']) {
                    echo '<div style="margin-bottom:8px;"><small style="color:#8b949e;">📍 Luogo:</small> <strong>'.htmlspecialchars($row['location_crimine']).'</strong></div>';
                }
                if($row['agenti_coinvolti']) {
                    echo '<div style="margin-bottom:8px;"><small style="color:#8b949e;">🕵️ Agenti Federali:</small> <strong>'.htmlspecialchars($row['agenti_coinvolti']).'</strong></div>';
                }
                
                echo '<div style="margin:12px 0; padding:8px 0; border-top:1px solid #30363d; border-bottom:1px solid #30363d;">
                        <strong style="color:#c9d1d9;">RELAZIONE FEDERALE:</strong><br><br>
                        '.nl2br(htmlspecialchars($row['descrizione'])).'
                      </div>
                      <div style="margin-top:12px; font-size:0.9em; border-top:1px solid #30363d; padding-top:8px;">
                        📅 Data Apertura: '.date('d/m/Y H:i', strtotime($row['data_creazione'] ?? $row['id'])).' | 
                        Stato: <strong>'.strtoupper($row['stato']).'</strong><br>
                        Agente Federale: '.strtoupper($row['creato_da']).'
                      </div>
                    </div>
                  </div>';
            }
        } else {
            echo "<div style=\"text-align:center; padding:40px; background:#161b22; border:1px solid #30363d; border-radius:8px; color:#8b949e;\">Nessun fascicolo federale archiviato</div>";
        }
        ?>
    </div>
</div>

</body>
</html>