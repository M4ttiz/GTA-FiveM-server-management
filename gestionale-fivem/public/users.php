<?php
session_start();
include('db.php');

if (!isset($_SESSION['user'])) { header("Location: index.php"); exit(); }
$user = $_SESSION['user'];
$ruolo = $user['ruolo'];

// ⛔ SOLO ADMIN PUÒ ACCEDERE
if ($ruolo !== 'admin') { 
    header("Location: dashboard.php"); 
    exit(); 
}

// 🔧 GESTIONE UTENTI
$error_delete = "";

// Aggiungi nuovo utente
if (isset($_POST['add_user'])) {
    $username = $conn->real_escape_string($_POST['username']);
    $password = $conn->real_escape_string($_POST['password']);
    $role = $conn->real_escape_string($_POST['role']);
    
    $sql = "INSERT INTO users (username, password, ruolo) VALUES ('$username', '$password', '$role')";
    if($conn->query($sql)) {
        header("Location: users.php");
        exit();
    }
}

// Modifica utente
if (isset($_POST['edit_user'])) {
    $user_id = (int)$_POST['user_id'];
    $username = $conn->real_escape_string($_POST['username']);
    $password = $conn->real_escape_string($_POST['password']);
    $role = $conn->real_escape_string($_POST['role']);
    
    $sql = "UPDATE users SET username='$username', password='$password', ruolo='$role' WHERE id=$user_id";
    if($conn->query($sql)) {
        header("Location: users.php");
        exit();
    }
}

// Cancella utente
if (isset($_POST['delete_user'])) {
    $user_id = (int)$_POST['user_id'];
    
    if ($user_id === $user['id']) {
        $error_delete = "Non puoi eliminare il tuo account!";
    } else {
        $sql = "DELETE FROM users WHERE id=$user_id";
        if($conn->query($sql)) {
            header("Location: users.php");
            exit();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestione Utenti - Sistema Gestionale</title>
    <link rel="stylesheet" href="style.css">
    
    <style>
        .admin-card {
            background: #161b22;
            border: 2px solid #f0883e;
            border-radius: 8px;
            padding: 24px;
            margin-bottom: 28px;
            box-shadow: 0 2px 8px rgba(240, 136, 62, 0.15);
        }

        .admin-card h3 {
            color: #f0883e;
            margin-top: 0;
            display: flex;
            align-items: center;
        }

        .users-table {
            width: 100%;
            border-collapse: collapse;
            background: #0d1117;
            border: 1px solid #30363d;
            border-radius: 8px;
            overflow: hidden;
        }

        .users-table thead {
            background: #161b22;
            border-bottom: 2px solid #30363d;
        }

        .users-table th {
            padding: 12px 16px;
            text-align: left;
            color: #f0883e;
            font-weight: 600;
        }

        .users-table td {
            padding: 12px 16px;
            border-bottom: 1px solid #30363d;
            color: #8b949e;
        }

        .users-table tbody tr:hover {
            background: #161b22;
        }

        .users-table input[type="text"],
        .users-table input[type="password"],
        .users-table select {
            background: #0d1117;
            border: 1px solid #30363d;
            color: #c9d1d9;
            padding: 6px 8px;
            border-radius: 4px;
            font-size: 0.85em;
            width: 100%;
        }

        .users-table input:focus,
        .users-table select:focus {
            outline: none;
            border-color: #58a6ff;
            box-shadow: 0 0 0 2px rgba(88, 166, 255, 0.1);
        }

        .btn-small {
            padding: 6px 12px;
            font-size: 0.85em;
            background: #1f6feb;
            color: #fff;
            border: 1px solid #30363d;
            border-radius: 4px;
            cursor: pointer;
            margin-right: 4px;
            transition: all 0.2s ease;
        }

        .btn-small:hover {
            background: #388bfd;
        }

        .btn-delete {
            background: #da3633;
        }

        .btn-delete:hover {
            background: #f85149;
        }
    </style>
    
    <script>
        function confirmDelete() {
            return confirm('Sei sicuro di voler eliminare questo utente?');
        }
    </script>
</head>
<body>

<div class="container">
    <div class="header-flex" style="margin-bottom: 28px;">
        <div>
            <h1>Gestione Utenti</h1>
            <p style="color: #8b949e; margin: 4px 0 0 0;">Pannello amministrativo - Modifica utenti e permessi</p>
        </div>
        <a href="dashboard.php" class="btn" style="text-decoration:none; height: fit-content;">← Dashboard</a>
    </div>

    <?php if (!empty($error_delete)): ?>
        <div style="background:#da3633; color:#fff; padding:12px; border-radius:4px; margin-bottom:16px;">
            ⚠️ <?php echo $error_delete; ?>
        </div>
    <?php endif; ?>

    <div class="admin-card">
        <h3>➕ Aggiungi Nuovo Utente</h3>
        
        <form method="POST" style="display:grid; grid-template-columns: 1fr 1fr 1fr auto; gap:12px; align-items:end;">
            <div>
                <label style="display:block; font-size:0.85em; color:#8b949e; margin-bottom:4px;">Username</label>
                <input type="text" name="username" placeholder="nuovo_utente" required>
            </div>
            <div>
                <label style="display:block; font-size:0.85em; color:#8b949e; margin-bottom:4px;">Password</label>
                <input type="text" name="password" placeholder="password" required>
            </div>
            <div>
                <label style="display:block; font-size:0.85em; color:#8b949e; margin-bottom:4px;">Ruolo</label>
                <select name="role" required>
                    <option value="">Seleziona ruolo</option>
                    <option value="admin">admin</option>
                    <option value="lspd">lspd</option>
                    <option value="lssd">lssd</option>
                    <option value="cid">cid</option>
                    <option value="armeria60">armeria60</option>
                    <option value="armeria200">armeria200</option>
                    <option value="marshall">marshall</option>
                    <option value="fib">fib</option>
                    <option value="iaa">iaa</option>
                    <option value="procura">procura</option>
                    <option value="alto_comando_lspd">alto_comando_lspd</option>
                    <option value="alto_comando_lssd">alto_comando_lssd</option>
                </select>
            </div>
            <button type="submit" name="add_user" class="btn-small">+ Aggiungi</button>
        </form>
    </div>

    <div class="admin-card">
        <h3>👥 Utenti Registrati</h3>
        
        <table class="users-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Password</th>
                    <th>Ruolo</th>
                    <th>Azioni</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $users_list = $conn->query("SELECT * FROM users ORDER BY id ASC");
                if ($users_list && $users_list->num_rows > 0) {
                    while($u = $users_list->fetch_assoc()) {
                        $user_id = $u['id'];
                        echo '<form method="POST">
                            <tr>
                                <td>'.$u['id'].'</td>
                                <td><input type="text" name="username" value="'.htmlspecialchars($u['username']).'" required></td>
                                <td><input type="text" name="password" value="'.htmlspecialchars($u['password']).'" required></td>
                                <td>
                                    <select name="role" required style="width:100%;">
                                        <option value="admin" '.($u['ruolo'] === 'admin' ? 'selected' : '').'>admin</option>
                                        <option value="lspd" '.($u['ruolo'] === 'lspd' ? 'selected' : '').'>lspd</option>
                                        <option value="lssd" '.($u['ruolo'] === 'lssd' ? 'selected' : '').'>lssd</option>
                                        <option value="cid" '.($u['ruolo'] === 'cid' ? 'selected' : '').'>cid</option>
                                        <option value="armeria60" '.($u['ruolo'] === 'armeria60' ? 'selected' : '').'>armeria60</option>
                                        <option value="armeria200" '.($u['ruolo'] === 'armeria200' ? 'selected' : '').'>armeria200</option>
                                        <option value="marshall" '.($u['ruolo'] === 'marshall' ? 'selected' : '').'>marshall</option>
                                        <option value="fib" '.($u['ruolo'] === 'fib' ? 'selected' : '').'>fib</option>
                                        <option value="iaa" '.($u['ruolo'] === 'iaa' ? 'selected' : '').'>iaa</option>
                                        <option value="procura" '.($u['ruolo'] === 'procura' ? 'selected' : '').'>procura</option>
                                        <option value="alto_comando_lspd" '.($u['ruolo'] === 'alto_comando_lspd' ? 'selected' : '').'>alto_comando_lspd</option>
                                        <option value="alto_comando_lssd" '.($u['ruolo'] === 'alto_comando_lssd' ? 'selected' : '').'>alto_comando_lssd</option>
                                    </select>
                                </td>
                                <td>
                                    <input type="hidden" name="user_id" value="'.$user_id.'">
                                    <button type="submit" name="edit_user" class="btn-small">SALVA</button>
                                    <button type="submit" name="delete_user" class="btn-small btn-delete" onclick="return confirmDelete()">DEL</button>
                                </td>
                            </tr>
                        </form>';
                    }
                } else {
                    echo '<tr><td colspan="5" style="text-align:center; color:#8b949e;">Nessun utente trovato</td></tr>';
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>