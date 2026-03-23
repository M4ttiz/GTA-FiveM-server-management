<?php
/**
 * FUNZIONI DI SICUREZZA E AUDIT LOGGING
 * Gestionale-FiveM Security Functions
 */

// ============== SECURITY HEADERS ==============

/**
 * Imposta gli header di sicurezza HTTP
 * Previene XSS, Clickjacking, MIME sniffing
 */
function set_security_headers() {
    // Previene il MIME sniffing attacks
    header('X-Content-Type-Options: nosniff');
    
    // Previene il Clickjacking (Framing attacks)
    header('X-Frame-Options: DENY');
    
    // Abilita XSS Protection nel browser
    header('X-XSS-Protection: 1; mode=block');
    
    // Content Security Policy - whitelist delle risorse
    header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline'; style-src 'self' 'unsafe-inline'; img-src 'self' data:; font-src 'self'; connect-src 'self'");
    
    // Se usi HTTPS, abilita HSTS (Strict-Transport-Security)
    if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
        header('Strict-Transport-Security: max-age=31536000; includeSubDomains');
    }
    
    // Referrer Policy - controlla le informazioni inviate
    header('Referrer-Policy: strict-origin-when-cross-origin');
    
    // Permissions Policy - disabilita feature potenzialmente pericolose
    header('Permissions-Policy: geolocation=(), microphone=(), camera=()');
}

// ============== AUDIT LOGGING ==============

/**
 * Registra le azioni degli utenti nel log di audit
 * 
 * @param string $azione - Tipo di azione (login, create, update, delete, export)
 * @param string $modulo - Modulo interessato (weapons, users, reports, etc.)
 * @param string $dettagli - Dettagli aggiuntivi (JSON o testo)
 * @param int $record_id - ID del record interessato (opzionale)
 * @param string $esito - Esito dell'azione (success, error, denied)
 */
function log_audit($azione, $modulo, $dettagli = "", $record_id = null, $esito = "success") {
    global $conn;
    
    // Controlla che esista una sessione
    if (!isset($_SESSION['user'])) {
        return false;
    }
    
    $username = $_SESSION['user']['username'];
    $ruolo = $_SESSION['user']['ruolo'];
    $ip_address = $_SERVER['REMOTE_ADDR'];
    $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';
    $timestamp = date('Y-m-d H:i:s');
    
    // Sanitizza i dati
    $azione = mysqli_real_escape_string($conn, $azione);
    $modulo = mysqli_real_escape_string($conn, $modulo);
    $dettagli = mysqli_real_escape_string($conn, $dettagli);
    $username = mysqli_real_escape_string($conn, $username);
    $ruolo = mysqli_real_escape_string($conn, $ruolo);
    $ip_address = mysqli_real_escape_string($conn, $ip_address);
    $user_agent = mysqli_real_escape_string($conn, $user_agent);
    $esito = mysqli_real_escape_string($conn, $esito);
    
    $sql = "INSERT INTO audit_logs (username, ruolo, azione, modulo, record_id, dettagli, ip_address, user_agent, esito, timestamp) 
            VALUES ('$username', '$ruolo', '$azione', '$modulo', " . ($record_id ? "'$record_id'" : "NULL") . ", '$dettagli', '$ip_address', '$user_agent', '$esito', '$timestamp')";
    
    return $conn->query($sql);
}

/**
 * Log di LOGIN - registra accessi all'applicazione
 */
function log_login($username, $esito = "success") {
    global $conn;
    
    $ip_address = $_SERVER['REMOTE_ADDR'];
    $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';
    $timestamp = date('Y-m-d H:i:s');
    
    $username = mysqli_real_escape_string($conn, $username);
    $ip_address = mysqli_real_escape_string($conn, $ip_address);
    $user_agent = mysqli_real_escape_string($conn, $user_agent);
    $esito = mysqli_real_escape_string($conn, $esito);
    
    $sql = "INSERT INTO audit_logs (username, ruolo, azione, modulo, dettagli, ip_address, user_agent, esito, timestamp) 
            VALUES ('$username', 'unknown', 'login', 'authentication', '$timestamp', '$ip_address', '$user_agent', '$esito', '$timestamp')";
    
    return $conn->query($sql);
}

/**
 * Recupera il log di audit per visualizzazione (solo per admin)
 * 
 * @param int $limit - Numero di record da visualizzare
 * @param int $offset - Offset per paginazione
 * @return array - Array dei log
 */
function get_audit_logs($limit = 50, $offset = 0) {
    global $conn;
    
    // Solo admin può visualizzare
    if ($_SESSION['user']['ruolo'] !== 'admin') {
        return [];
    }
    
    $limit = (int)$limit;
    $offset = (int)$offset;
    
    $sql = "SELECT id, username, ruolo, azione, modulo, record_id, dettagli, ip_address, esito, timestamp 
            FROM audit_logs 
            ORDER BY timestamp DESC 
            LIMIT $limit OFFSET $offset";
    
    $result = $conn->query($sql);
    return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
}

/**
 * Conta i record totali di audit log
 */
function count_audit_logs() {
    global $conn;
    
    $sql = "SELECT COUNT(*) as total FROM audit_logs";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    
    return $row['total'] ?? 0;
}

/**
 * Esporta il log di audit in CSV (solo admin)
 */
function export_audit_logs_csv() {
    global $conn;
    
    if ($_SESSION['user']['ruolo'] !== 'admin') {
        die("Accesso negato");
    }
    
    $sql = "SELECT id, username, ruolo, azione, modulo, record_id, dettagli, ip_address, esito, timestamp 
            FROM audit_logs 
            ORDER BY timestamp DESC";
    
    $result = $conn->query($sql);
    
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="audit_logs_' . date('Y-m-d_H-i-s') . '.csv"');
    
    $output = fopen('php://output', 'w');
    
    // Header del CSV
    fputcsv($output, ['ID', 'Username', 'Ruolo', 'Azione', 'Modulo', 'Record ID', 'Dettagli', 'IP Address', 'Esito', 'Timestamp']);
    
    // Dati
    while ($row = $result->fetch_assoc()) {
        fputcsv($output, $row);
    }
    
    fclose($output);
    exit();
}

/**
 * Crea la tabella audit_logs se non esiste
 * Esegui una sola volta durante l'installazione
 */
function create_audit_table() {
    global $conn;
    
    $sql = "CREATE TABLE IF NOT EXISTS audit_logs (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) NOT NULL,
        ruolo VARCHAR(50),
        azione VARCHAR(50) NOT NULL,
        modulo VARCHAR(50) NOT NULL,
        record_id INT,
        dettagli TEXT,
        ip_address VARCHAR(45) NOT NULL,
        user_agent TEXT,
        esito VARCHAR(20) DEFAULT 'success',
        timestamp DATETIME NOT NULL,
        INDEX idx_username (username),
        INDEX idx_azione (azione),
        INDEX idx_modulo (modulo),
        INDEX idx_timestamp (timestamp)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
    
    return $conn->query($sql);
}

?>
