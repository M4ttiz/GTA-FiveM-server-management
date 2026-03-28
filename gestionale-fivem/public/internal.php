<?php
session_start();
include('db.php');

if (!isset($_SESSION['user'])) { header("Location: index.php"); exit(); }
$user = $_SESSION['user'];
$ruolo = $user['ruolo'];

if (!in_array($ruolo, ['admin', 'iaa'])) { header("Location: dashboard.php"); exit(); }

// 💾 SALVATAGGIO RAPPORTO
if (isset($_POST['save_internal'])) {
    $titolo = $conn->real_escape_string($_POST['titolo']);
    $desc = $conn->real_escape_string($_POST['descrizione']);
    $tipo_ind = $conn->real_escape_string($_POST['tipo_indagine'] ?? 'interno');
    $priorita = $conn->real_escape_string($_POST['priorita'] ?? 'media');
    $location = $conn->real_escape_string($_POST['location_crimine'] ?? '');
    $note = $conn->real_escape_string($_POST['note_aggiuntive'] ?? '');
    $autore = $user['username'];
    
    $sql = "INSERT INTO reports (titolo, descrizione, creato_da, ruolo_creatore, categoria, tipo_indagine, priorita, location_crimine, stato) 
            VALUES ('$titolo', '$desc', '$autore', '$ruolo', 'internal', '$tipo_ind', '$priorita', '$location', 'aperto')";
    
    if($conn->query($sql)) {
        header("Location: internal.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Internal Affairs - Sistema Gestionale</title>
    <link rel="stylesheet" href="style.css">
    
    <style>
        .iaa-card {
            background: #161b22;
            border: 1px solid #30363d;
            border-radius: 8px;
            padding: 24px;
            margin-bottom: 28px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.3);
        }

        .iaa-card h3 {
            color: #f0883e;
            margin-top: 0;
        }

        .doc-item {
            background: #161b22;
            border: 1px solid #30363d;
            border-radius: 8px;
            margin-bottom: 16px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.3);
            transition: all 0.2s ease;
        }

        .doc-item:hover {
            border-color: #58a6ff;
            box-shadow: 0 3px 12px rgba(88, 166, 255, 0.15);
        }

        .doc-header {
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

        .doc-header:hover {
            background: #161b22;
        }

        .doc-body {
            display: none;
            padding: 16px;
            background: #0d1117;
            color: #8b949e;
            line-height: 1.6;
            white-space: pre-wrap;
            word-wrap: break-word;
            font-size: 0.95em;
        }

        .btn-iaa {
            background: #1f6feb;
            color: #fff;
            border: 1px solid #30363d;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.3);
        }

        .btn-iaa:hover {
            background: #388bfd;
            box-shadow: 0 3px 8px rgba(88, 166, 255, 0.2);
        }
    </style>
    
    <script>
        function toggle(id) {
            var x = document.getElementById('doc-' + id);
            x.style.display = (x.style.display === 'block') ? 'none' : 'block';
        }
    </script>
</head>
<body>

<div class="container">
    <div class="header-flex" style="margin-bottom: 28px;">
        <div>
            <h1>Internal Affairs</h1>
            <p style="color: #8b949e; margin: 4px 0 0 0;">Rapporti e investigazioni interne</p>
        </div>
        <a href="dashboard.php" class="btn" style="text-decoration:none; height: fit-content;">← Dashboard</a>
    </div>

    <div class="iaa-card">
        <h3>Nuovo Rapporto Investigativo Interno</h3>
        <form method="POST">
            <div style="margin-bottom: 12px;">
                <label>Soggetto / Matricola Agente</label>
                <input type="text" name="titolo" placeholder="es. Agent Johnson - Corruzione" required>
            </div>
            <div style="margin-bottom: 12px;">
                <label>Tipo Indagine Interna</label>
                <select name="tipo_indagine">
                    <option value="interno">Indagine Interna Standard</option>
                    <option value="corruzione">Corruzione</option>
                    <option value="abuso_autorità">Abuso di Autorità</option>
                    <option value="condotta_impropria">Condotta Impropria</option>
                    <option value="furto_interno">Furto Interno</option>
                    <option value="otro">Altro</option>
                </select>
            </div>
            <div style="margin-bottom: 12px;">
                <label>Priorità</label>
                <select name="priorita">
                    <option value="bassa">Bassa</option>
                    <option value="media" selected>Media</option>
                    <option value="alta">Alta</option>
                    <option value="critica">Critica</option>
                </select>
            </div>
            <div style="margin-bottom: 12px;">
                <label>Reparto / Locazione</label>
                <input type="text" name="location_crimine" placeholder="es. Divisione Nord">
            </div>
            <div style="margin-bottom: 12px;">
                <label>Descrizione dettagliata</label>
                <textarea name="descrizione" rows="5" placeholder="Descrizione dettagliata dei fatti..." required style="resize: vertical;"></textarea>
            </div>
            <button type="submit" name="save_internal" class="btn btn-iaa">DEPOSITA RAPPORTO</button>
        </form>
    </div>

    <div class="list">
        <?php
        $res = $conn->query("SELECT * FROM reports WHERE categoria = 'internal' ORDER BY id DESC");
        if ($res && $res->num_rows > 0) {
            while($row = $res->fetch_assoc()) {
                echo '<div class="doc-item">
                        <div class="doc-header" onclick="toggle('.$row['id'].')">
                            <div>
                                <span style="color:#f0883e; font-weight:600;">RAPPORTO #'.$row['id'].' - '.htmlspecialchars($row['titolo']).'</span><br>
                                <small style="color:#8b949e; margin-top:4px; display:block;">Tipo: '.htmlspecialchars($row['tipo_indagine'] ?? 'interno').'</small>
                            </div>
                            <div style="text-align:right;">
                                <span style="display:inline-block; padding:4px 10px; border-radius:12px; font-weight:600; font-size:0.85em; background:'.
                                    (['bassa' => '#58a6ff', 'media' => '#f0883e', 'alta' => '#f85149', 'critica' => '#da3633'][$row['priorita'] ?? 'media'] ?? '#f0883e').'; color:#fff;">
                                    '.strtoupper($row['priorita'] ?? 'MEDIA').'
                                </span><br>
                                <small style="color:#8b949e; margin-top:4px; display:block;">'.strtoupper($row['stato']).' ▼</small>
                            </div>
                        </div>
                        <div id="doc-'.$row['id'].'" class="doc-body">
                            <div style="margin-bottom:12px;">
                                <strong style="color:#f0883e;">DATI INVESTIGAZIONE</strong>
                            </div>';
                
                if($row['location_crimine']) {
                    echo '<div style="margin-bottom:8px;"><small style="color:#8b949e;">📍 Reparto/Locazione:</small> <strong>'.htmlspecialchars($row['location_crimine']).'</strong></div>';
                }
                
                echo '<div style="margin:12px 0; padding:8px 0; border-top:1px solid #30363d; border-bottom:1px solid #30363d;">
                        <strong style="color:#c9d1d9;">RELAZIONE DETTAGLIATA:</strong><br><br>
                        '.nl2br(htmlspecialchars($row['descrizione'])).'
                      </div>
                      <div style="margin-top:12px; font-size:0.85em; color:#6e7681;">
                        📅 Data: '.date('d/m/Y H:i', strtotime($row['data_creazione'] ?? $row['id'])).' | Agente IAA: '.strtoupper($row['creato_da']).'
                      </div>
                    </div>
                  </div>';
            }
        } else {
            echo "<div style=\"text-align:center; padding:40px; background:#161b22; border:1px solid #30363d; border-radius:8px; color:#8b949e;\">Nessun rapporto trovato nel database</div>";
        }
        ?>
    </div>
</div>

</body>
</html>