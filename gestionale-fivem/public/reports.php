<?php
session_start();
include('db.php');

if (!isset($_SESSION['user'])) { header("Location: index.php"); exit(); }
$user = $_SESSION['user'];
$ruolo = $user['ruolo'];

// --- 🛡️ SECURITY CHECK: Chi non è autorizzato torna in Dashboard ---
$autorizzati = ['admin', 'cid', 'lssd', 'alto_comando_lspd', 'alto_comando_lssd'];
if (!in_array($ruolo, $autorizzati)) {
    header("Location: dashboard.php");
    exit();
}

// Solo chi fa indagini penali o i Marshall (in lettura) entrano qui
if (!in_array($ruolo, ['admin', 'procura', 'cid', 'marshall'])) {
    header("Location: dashboard.php");
    exit();
}

// --- ⚙️ LOGICA PERMESSI ---
$can_manage = in_array($ruolo, ['admin', 'cid']); // Solo loro scrivono/cancellano

// Azioni Database
if (isset($_POST['save']) && $can_manage) {
    $t = $conn->real_escape_string($_POST['titolo']);
    $d = $conn->real_escape_string($_POST['desc']);
    $autore = $user['username'];
    // Se l'admin scrive, decidiamo un'appartenenza fissa per il colore (LSSD in questo caso)
    $org = ($ruolo == 'admin') ? 'lssd' : $ruolo; 

    $conn->query("INSERT INTO reports (titolo, descrizione, creato_da, ruolo_creatore, categoria) VALUES ('$t', '$d', '$autore', '$org', 'investigativo')");
}

if (isset($_GET['del']) && $can_manage) {
    $id = intval($_GET['del']);
    $conn->query("DELETE FROM reports WHERE id = $id");
}
?>


<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report Investigativi - Sistema Gestionale</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .report-card { 
            background: #161b22;
            border: 1px solid #30363d;
            border-radius: 8px;
            margin-bottom: 16px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            transition: all 0.2s ease;
        }

        .report-card:hover {
            border-color: #58a6ff;
            box-shadow: 0 3px 12px rgba(88, 166, 255, 0.15);
        }

        .report-header { 
            padding: 16px;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #0d1117;
            border-bottom: 1px solid #30363d;
            transition: all 0.2s ease;
        }

        .report-header:hover {
            background: #161b22;
        }

        .report-body { 
            display: none;
            padding: 16px;
            background: #0d1117;
            color: #8b949e;
            line-height: 1.6;
            white-space: pre-wrap;
            word-wrap: break-word;
            font-size: 0.95em;
        }

        .badge-report {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.8em;
            background: #238636;
            color: #fff;
        }
        
        .btn-del { 
            color: #f85149 !important;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.2s ease;
            display: inline-block;
            margin-top: 12px;
            padding: 6px 12px;
            border: 1px solid #da3633;
            border-radius: 6px;
            font-size: 0.85em;
        }
        
        .btn-del:hover {
            background: #da3633;
            color: #fff !important;
            box-shadow: 0 1px 3px rgba(218, 54, 51, 0.3);
        }
    </style>
    <script>
        function openReport(id) {
            let item = document.getElementById('details-' + id);
            item.style.display = (item.style.display === 'block') ? 'none' : 'block';
        }
    </script>
</head>
<body>

<div class="container">
    <div class="header-flex" style="margin-bottom: 28px;">
        <div>
            <h1>Report Investigativi</h1>
            <p style="color: #8b949e; margin: 4px 0 0 0;">Archivio centralizzato dei rapporti indagini</p>
        </div>
        <a href="dashboard.php" class="btn" style="text-decoration:none; height: fit-content;">← Dashboard</a>
    </div>

    <?php if($can_manage): ?>
    <div class="form-section">
        <h3>Nuovo Rapporto Investigativo</h3>
        <form method="POST">
            <div style="margin-bottom: 12px;">
                <label>Oggetto del Rapporto / Nome Indagato</label>
                <input type="text" name="titolo" placeholder="es. Indagine Furto Gioielleria" required>
            </div>
            <div style="margin-bottom: 12px;">
                <label>Descrizione dettagliata dei fatti e prove raccolte</label>
                <textarea name="desc" placeholder="Inserire i dettagli dell'investigazione..." rows="5" required style="resize: vertical;"></textarea>
            </div>
            <button type="submit" name="save" class="btn-save">DEPOSITA RAPPORTO</button>
        </form>
    </div>
    <?php endif; ?>

    <div class="report-list">
        <?php
        $query = "SELECT * FROM reports WHERE categoria = 'investigativo' ORDER BY id DESC";
        $res = $conn->query($query);
        if ($res->num_rows > 0) {
            while($row = $res->fetch_assoc()):
        ?>
        <div class="report-card">
            <div class="report-header" onclick="openReport(<?php echo $row['id']; ?>)">
                <div>
                    <small style="color: #8b949e; font-size: 0.85em;">PROTOCOLLO #<?php echo $row['id']; ?></small><br>
                    <strong style="color: #f0883e; margin-top: 4px; display: block;"><?php echo htmlspecialchars($row['titolo']); ?></strong>
                </div>
                <div style="text-align:right;">
                    <span class="badge-report">AGENZIA</span><br>
                    <small style="color: #8b949e; margin-top: 4px; display: block;">Agente: <?php echo strtoupper($row['creato_da']); ?></small>
                </div>
            </div>
            
            <div id="details-<?php echo $row['id']; ?>" class="report-body">
                <?php echo htmlspecialchars($row['descrizione']); ?>
                
                <?php if($can_manage): ?>
                <div style="margin-top:16px; border-top:1px solid #30363d; padding-top:12px;">
                    <a href="reports.php?del=<?php echo $row['id']; ?>" class="btn-del" onclick="return confirm('Distruggere definitivamente questo atto?')">🗑️ ELIMINA</a>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <?php 
            endwhile;
        } else {
            echo "<div style=\"text-align:center; padding:40px; background:#161b22; border:1px solid #30363d; border-radius:8px; color:#8b949e;\">Nessun rapporto archiviato</div>";
        }
        ?>
    </div>
</div>
</body>
</html>