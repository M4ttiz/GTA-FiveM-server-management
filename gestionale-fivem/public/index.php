<?php
include('db.php');
session_start();

$error = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = $_POST['username'];
    $pass = $_POST['password'];

    $sql = "SELECT * FROM users WHERE username = '$user' AND password = '$pass'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $_SESSION['user'] = $result->fetch_assoc();
        header("Location: dashboard.php");
    } else {
        $error = "Credenziali errate!";
    }
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>FiveM Management System - Login</title>
    <style>
        .login-container {
            max-width: 420px;
            margin: 80px auto;
            padding: 20px;
        }
        
        .login-box {
            background: #161b22;
            border: 1px solid #30363d;
            border-radius: 8px;
            padding: 44px;
            box-shadow: 0 3px 12px rgba(0, 0, 0, 0.4);
        }
        
        .login-box h1 {
            font-size: 1.8em;
            margin-bottom: 8px;
            color: #f0883e;
        }
        
        .login-subtitle {
            color: #8b949e;
            font-size: 0.9em;
            margin-bottom: 28px;
        }
        
        .login-box input {
            width: 100%;
            padding: 10px 12px;
            margin: 8px 0 12px 0;
            background: #0d1117;
            border: 1px solid #30363d;
            color: #e6edf3;
            border-radius: 6px;
            font-size: 0.95em;
            transition: all 0.2s ease;
        }
        
        .login-box input:focus {
            border-color: #58a6ff;
            color: #f0883e;
            box-shadow: 0 0 8px rgba(88, 166, 255, 0.1);
            outline: none;
        }
        
        .login-box input::placeholder {
            color: #6e7681;
        }
        
        .login-button {
            width: 100%;
            padding: 10px;
            margin-top: 20px;
            background: #238636;
            border: 1px solid #30363d;
            color: #fff;
            font-weight: 600;
            font-size: 0.95em;
            cursor: pointer;
            border-radius: 6px;
            transition: all 0.2s ease;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.3);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .login-button:hover {
            background: #2ea043;
            box-shadow: 0 3px 8px rgba(36, 134, 54, 0.2);
            transform: translateY(-1px);
        }
        
        .error-message {
            color: #f85149;
            font-weight: 600;
            margin-top: 12px;
            font-size: 0.95em;
            text-align: center;
        }
        
        .login-box label {
            display: block;
            color: #f0883e;
            font-weight: 600;
            font-size: 0.85em;
            margin-bottom: 4px;
            text-transform: uppercase;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <h1>FiveM Management</h1>
            <p class="login-subtitle">Sistema di Gestione Operativo</p>
            
            <form method="POST">
                <label>Username</label>
                <input type="text" name="username" placeholder="Inserisci username" required autocomplete="username">
                
                <label style="margin-top: 12px;">Password</label>
                <input type="password" name="password" placeholder="Inserisci password" required autocomplete="current-password">
                
                <button type="submit" class="login-button">ACCEDI</button>
            </form>
            
            <?php if($error) echo "<p class='error-message'>❌ " . htmlspecialchars($error) . "</p>"; ?>
        </div>
    </div>
</body>
</html>