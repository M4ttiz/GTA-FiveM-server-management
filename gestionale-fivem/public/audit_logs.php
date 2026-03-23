<?php
session_start();
include('db.php');

// Solo admin può accedere
if (!isset($_SESSION['user']) || $_SESSION['user']['ruolo'] !== 'admin') {
    header("Location: index.php");
    exit();
}

// Gestione esportazione CSV
if (isset($_GET['export']) && $_GET['export'] === 'csv') {
    export_audit_logs_csv();
}

// Paginazione
$items_per_page = 50;
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($current_page - 1) * $items_per_page;

$logs = get_audit_logs($items_per_page, $offset);
$total_logs = count_audit_logs();
$total_pages = ceil($total_logs / $items_per_page);

// Filtri
$filtro_azione = isset($_GET['azione']) ? $_GET['azione'] : '';
$filtro_username = isset($_GET['username']) ? $_GET['username'] : '';
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Audit Logs - FiveM Management System</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .audit-container {
            max-width: 1400px;
            margin: 20px auto;
            padding: 20px;
        }
        
        .audit-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }
        
        .audit-header h2 {
            margin: 0;
            color: #f0883e;
        }
        
        .actions-btn {
            display: flex;
            gap: 10px;
        }
        
        .btn {
            padding: 8px 16px;
            background: #238636;
            color: #fff;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }
        
        .btn:hover {
            background: #2ea043;
        }
        
        .btn-secondary {
            background: #1f6feb;
        }
        
        .btn-secondary:hover {
            background: #388bfd;
        }
        
        .filters {
            background: #0d1117;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
            display: flex;
            gap: 10px;
            align-items: center;
        }
        
        .filters input, .filters select {
            background: #161b22;
            color: #c9d1d9;
            border: 1px solid #30363d;
            padding: 8px;
            border-radius: 6px;
            font-size: 12px;
        }
        
        .table-wrapper {
            overflow-x: auto;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            background: #0d1117;
            border: 1px solid #30363d;
            border-radius: 6px;
            overflow: hidden;
        }
        
        table thead {
            background: #161b22;
        }
        
        table th, table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #30363d;
            font-size: 12px;
        }
        
        table th {
            color: #f0883e;
            font-weight: bold;
        }
        
        table tbody tr:hover {
            background: #161b22;
        }
        
        .badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: bold;
        }
        
        .badge-success {
            background: #238636;
            color: #fff;
        }
        
        .badge-error {
            background: #da3633;
            color: #fff;
        }
        
        .badge-denied {
            background: #d97706;
            color: #fff;
        }
        
        .pagination {
            display: flex;
            gap: 5px;
            justify-content: center;
            margin-top: 30px;
        }
        
        .pagination a, .pagination span {
            padding: 8px 12px;
            background: #161b22;
            border: 1px solid #30363d;
            border-radius: 6px;
            text-decoration: none;
            color: #f0883e;
        }
        
        .pagination a:hover {
            background: #238636;
        }
        
        .pagination .active {
            background: #f0883e;
            color: #000;
        }
        
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: #161b22;
            padding: 15px;
            border-radius: 6px;
            border: 1px solid #30363d;
            text-align: center;
        }
        
        .stat-card h3 {
            margin: 0;
            color: #8b949e;
            font-size: 12px;
            text-transform: uppercase;
        }
        
        .stat-card .value {
            font-size: 28px;
            color: #f0883e;
            margin-top: 10px;
        }
    </style>
</head>
<body>
<div class="container">
    <nav>
        <span>FIVEM Management System - AUDIT LOGS ADMIN</span>
        <a href="dashboard.php" style="color:#f0883e;">[ DASHBOARD ]</a>
    </nav>
    
    <div class="audit-container">
        <div class="audit-header">
            <h2>📊 Audit Logs - Tracciamento Azioni</h2>
            <div class="actions-btn">
                <a href="?export=csv" class="btn btn-secondary">📥 Esporta CSV</a>
                <a href="dashboard.php" class="btn">← Indietro</a>
            </div>
        </div>
        
        <!-- STATISTICHE -->
        <div class="stats">
            <div class="stat-card">
                <h3>Total Logs</h3>
                <div class="value"><?php echo number_format($total_logs); ?></div>
            </div>
            <div class="stat-card">
                <h3>Pagina Attuale</h3>
                <div class="value"><?php echo $current_page; ?>/<?php echo $total_pages; ?></div>
            </div>
            <div class="stat-card">
                <h3>Azioni Registrate</h3>
                <div class="value">👤📝🔐 🗑️</div>
            </div>
        </div>
        
        <!-- FILTRI -->
        <div class="filters">
            <form method="GET" style="display: flex; gap: 10px; width: 100%;">
                <input type="text" name="username" placeholder="Filtra per username..." value="<?php echo htmlspecialchars($filtro_username); ?>">
                <select name="azione">
                    <option value="">-- Tutte le azioni --</option>
                    <option value="login" <?php echo $filtro_azione === 'login' ? 'selected' : ''; ?>>Login</option>
                    <option value="create" <?php echo $filtro_azione === 'create' ? 'selected' : ''; ?>>Create</option>
                    <option value="update" <?php echo $filtro_azione === 'update' ? 'selected' : ''; ?>>Update</option>
                    <option value="delete" <?php echo $filtro_azione === 'delete' ? 'selected' : ''; ?>>Delete</option>
                </select>
                <button type="submit" class="btn" style="padding: 8px 16px; margin: 0;">🔍 Filtra</button>
                <a href="?" class="btn btn-secondary" style="padding: 8px 16px;">Ripristina</a>
            </form>
        </div>
        
        <!-- TABELLA LOG -->
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Timestamp</th>
                        <th>Username</th>
                        <th>Ruolo</th>
                        <th>Azione</th>
                        <th>Modulo</th>
                        <th>Record ID</th>
                        <th>Dettagli</th>
                        <th>IP Address</th>
                        <th>Esito</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($logs)): ?>
                    <tr>
                        <td colspan="10" style="text-align: center; color: #8b949e;">Nessun log trovato</td>
                    </tr>
                    <?php else: ?>
                        <?php foreach ($logs as $log): ?>
                        <tr>
                            <td>#<?php echo $log['id']; ?></td>
                            <td><?php echo $log['timestamp']; ?></td>
                            <td><strong><?php echo htmlspecialchars($log['username']); ?></strong></td>
                            <td><?php echo htmlspecialchars($log['ruolo']); ?></td>
                            <td>
                                <?php 
                                $azione = $log['azione'];
                                $emoji = [
                                    'login' => '👤',
                                    'create' => '➕',
                                    'update' => '✏️',
                                    'delete' => '🗑️',
                                    'logout' => '🚪'
                                ];
                                echo ($emoji[$azione] ?? '📝') . ' ' . strtoupper($azione);
                                ?>
                            </td>
                            <td><?php echo htmlspecialchars($log['modulo']); ?></td>
                            <td><?php echo $log['record_id'] ? '#' . $log['record_id'] : '-'; ?></td>
                            <td><small><?php echo substr(htmlspecialchars($log['dettagli']), 0, 50) . (strlen($log['dettagli']) > 50 ? '...' : ''); ?></small></td>
                            <td><code style="font-size: 10px;"><?php echo $log['ip_address']; ?></code></td>
                            <td>
                                <?php 
                                if ($log['esito'] === 'success') {
                                    echo '<span class="badge badge-success">✓ SUCCESS</span>';
                                } elseif ($log['esito'] === 'failed') {
                                    echo '<span class="badge badge-error">✗ FAILED</span>';
                                } elseif ($log['esito'] === 'denied') {
                                    echo '<span class="badge badge-denied">⚠ DENIED</span>';
                                } else {
                                    echo '<span class="badge">' . strtoupper($log['esito']) . '</span>';
                                }
                                ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <!-- PAGINAZIONE -->
        <?php if ($total_pages > 1): ?>
        <div class="pagination">
            <?php if ($current_page > 1): ?>
                <a href="?page=1">« Prima</a>
                <a href="?page=<?php echo $current_page - 1; ?>">< Precedente</a>
            <?php endif; ?>
            
            <?php for ($i = max(1, $current_page - 2); $i <= min($total_pages, $current_page + 2); $i++): ?>
                <?php if ($i === $current_page): ?>
                    <span class="active"><?php echo $i; ?></span>
                <?php else: ?>
                    <a href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                <?php endif; ?>
            <?php endfor; ?>
            
            <?php if ($current_page < $total_pages): ?>
                <a href="?page=<?php echo $current_page + 1; ?>">Successiva ></a>
                <a href="?page=<?php echo $total_pages; ?>">Ultima »</a>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>
</div>

</body>
</html>
