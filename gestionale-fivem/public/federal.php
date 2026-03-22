<?php
session_start();
include('db.php');

if (!isset($_SESSION['user'])) { header("Location: index.php"); exit(); }
$user = $_SESSION['user'];
$ruolo = $user['ruolo'];

// 🛡️ SECURITY CHECK: Solo Marshall e Admin
if (!in_array($ruolo, ['admin', 'marshall'])) {
    header("Location: dashboard.php");
    exit();
}

// 💾 LOGICA SALVATAGGIO: Categoria 'federal'
if (isset($_POST['save_federal'])) {
    $titolo = $conn->real_escape_string($_POST['titolo']);
    $desc = $conn->real_escape_string($_POST['descrizione']);
    $autore = $user['username'];
    
    $sql = "INSERT INTO reports (titolo, descrizione, creato_da, ruolo_creatore, categoria, stato) 
            VALUES ('$titolo', '$desc', '$autore', '$ruolo', 'federal', 'aperto')";
    
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
                            <span>CASO #'.$row['id'].' - '.htmlspecialchars($row['titolo']).'</span>
                            <span>DETTAGLI ▼</span>
                        </div>
                        <div id="case-'.$row['id'].'" class="case-body">
                            '.htmlspecialchars($row['descrizione']).'
                            <div style="margin-top:12px; font-size:0.9em; border-top:1px solid #30363d; padding-top:8px;">
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