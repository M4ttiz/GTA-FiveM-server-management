# ⚙️ Configuration Guide - Gestionale-FiveM

Guida completa per configurare Gestionale-FiveM secondo le tue necessità.

---

## 🔧 Environment Configuration

### 1. Database Configuration

**File**: `public/db.php`

```php
// Configura questi parametri:
const DB_HOST = 'localhost';     // MySQL host
const DB_NAME = 'fivem_gestionale'; // Database name
const DB_USER = 'root';          // MySQL user
const DB_PASS = '';              // MySQL password (XAMPP default: empty)
const DB_CHARSET = 'utf8mb4';   // Character set
```

#### Per Produzione:
```php
// ❌ INSICURO
$conn = new mysqli('localhost', 'root', '', 'fivem_gestionale');

// ✅ SICURO
$conn = new mysqli(
    getenv('DB_HOST'),
    getenv('DB_USER'),
    getenv('DB_PASS'),
    getenv('DB_NAME')
);
```

### 2. Session Configuration

**File**: `server.js` (Node.js) or `public/config.php`

```php
// Session timeout (minuti)
ini_set('session.gc_maxlifetime', 3600); // 1 ora

// Secure flag
session_set_cookie_params([
    'secure' => true,      // HTTPS only
    'httponly' => true,    // No JS access
    'samesite' => 'Strict' // CSRF protection
]);
```

### 3. Security Headers

**In Apache `.htaccess`:**
```apache
<IfModule mod_headers.c>
    # Prevent content type sniffing
    Header always set X-Content-Type-Options "nosniff"
    
    # Clickjacking protection
    Header always set X-Frame-Options "DENY"
    
    # XSS Protection
    Header always set X-XSS-Protection "1; mode=block"
    
    # HSTS (for HTTPS only)
    Header always set Strict-Transport-Security "max-age=31536000; includeSubDomains"
</IfModule>
```

---

## 🔐 Security Settings

### Password Policy

**In `public/db.php`:**
```php
define('PASSWORD_MIN_LENGTH', 12);
define('PASSWORD_REQUIRE_UPPERCASE', true);
define('PASSWORD_REQUIRE_NUMBERS', true);
define('PASSWORD_REQUIRE_SYMBOLS', true);

// Password hashing
$hash = password_hash($password, PASSWORD_ARGON2ID, [
    'memory_cost' => 19456,
    'time_cost' => 4,
    'threads' => 1
]);
```

### CSRF Token

Viene rigenerato automaticamente:
```php
// In ogni form
<input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

// Validazione server
if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    die('CSRF Token validation failed');
}
```

### Rate Limiting

```php
// Implementa per endpoints sensibili
function checkRateLimit($action, $maxAttempts = 5, $timeWindow = 300) {
    $key = "ratelimit_" . $action . "_" . $_SESSION['user_id'];
    
    // Redis o file-based tracking
    // Aumenta counter ogni tentativo
    // Reset dopo timeWindow
}
```

---

## 📊 Performance Settings

### Database Optimization

```sql
-- Add indexes for common queries
CREATE INDEX idx_user_id ON weapons(user_id);
CREATE INDEX idx_report_status ON reports(stato);
CREATE INDEX idx_created_at ON reports(created_at);

-- Analyze tables
ANALYZE TABLE weapons;
ANALYZE TABLE reports;
```

### Caching Configuration

```php
// Implementare caching per query frequenti
// Opzioni: Redis, Memcached, File-based

// File-based cache (semplice)
function setCacheEntry($key, $value, $ttl = 3600) {
    $file = "/tmp/cache_{$key}";
    file_put_contents($file, serialize($value));
    touch($file, time() + $ttl);
}
```

### Query Optimization

```php
// ❌ N+1 Query Problem
foreach ($weapons as $weapon) {
    $owner = $db->query("SELECT * FROM users WHERE id = " . $weapon['user_id']);
}

// ✅ Solution: JOIN
$sql = "SELECT w.*, u.name FROM weapons w 
        JOIN users u ON w.user_id = u.id";
```

---

## 📋 User Management

### Adding New Users

```php
// Via admin dashboard o direttamente
$username = 'newuser';
$password = 'ComplexPassword123!@#';
$role = 'pd'; // admin, federal, pd, armeria60, cid

// Hash password
$hash = password_hash($password, PASSWORD_ARGON2ID);

// Insert
$stmt = $db->prepare("INSERT INTO users (username, password, ruolo) VALUES (?, ?, ?)");
$stmt->bind_param('sss', $username, $hash, $role);
$stmt->execute();
```

### Role Management

```
Ruoli disponibili:
- admin      → Accesso completo
- federal    → Federal service + reports
- pd         → Reports only
- armeria60  → Weapons system only
- cid        → Reports only (civile)
- interno    → Internal affairs only
```

### Custom Roles

Per aggiungere ruoli custom:

1. Aggiungi colonna a table `users`:
   ```sql
   ALTER TABLE users ADD COLUMN permissions JSON;
   ```

2. Define permissions:
   ```php
   $permissions = [
       'weapons' => ['create', 'read', 'update'],
       'reports' => ['create', 'read'],
       'users' => []
   ];
   ```

3. Check permission:
   ```php
   function canAccess($module, $action) {
       return in_array($action, $_SESSION['permissions'][$module] ?? []);
   }
   ```

---

## 🌐 HTTP & SSL Configuration

### Enable HTTPS

```apache
<VirtualHost *:443>
    ServerName your-domain.com
    
    SSLEngine on
    SSLCertificateFile /path/to/certificate.crt
    SSLCertificateKeyFile /path/to/private.key
    
    DocumentRoot /path/to/gestionale-fivem
</VirtualHost>

# Redirect HTTP to HTTPS
<VirtualHost *:80>
    ServerName your-domain.com
    Redirect permanent / https://your-domain.com/
</VirtualHost>
```

### CORS Configuration

```php
header('Access-Control-Allow-Origin: https://yourdomain.com');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
```

---

## 💾 Backup & Recovery

### Automated Backup Script

```bash
#!/bin/bash
# backup.sh - Backup database giornalmente

BACKUP_DIR="/backup/fivem"
DB_NAME="fivem_gestionale"
DB_USER="root"
DB_PASS=""
DATE=$(date +%Y%m%d_%H%M%S)

mkdir -p $BACKUP_DIR

# Backup database
mysqldump -u $DB_USER -p$DB_PASS $DB_NAME > "$BACKUP_DIR/db_$DATE.sql"

# Backup application files
tar -czf "$BACKUP_DIR/app_$DATE.tar.gz" /path/to/gestionale-fivem

# Keep only last 30 days
find $BACKUP_DIR -mtime +30 -delete

echo "Backup completed: $DATE"
```

### Restore from Backup

```bash
# Restore database
mysql -u root -p fivem_gestionale < /backup/fivem/db_20260322_120000.sql

# Restore files
tar -xzf /backup/fivem/app_20260322_120000.tar.gz
```

### Cron Job Setup

```bash
# Edit crontab
crontab -e

# Add:
0 2 * * * /home/user/scripts/backup.sh
# Esegue backup ogni giorno alle 02:00 AM
```

---

## 📈 Monitoring & Logging

### Enable Error Logging

```php
// php.ini configuration
error_reporting = E_ALL;
display_errors = Off; // Per produzione
log_errors = On;
error_log = "/var/log/php/gestionale.log";

// Application logging
define('LOG_FILE', '/var/log/gestionale/app.log');

function logAction($type, $user, $action, $details) {
    $log = date('Y-m-d H:i:s') . " | $type | $user | $action | " . json_encode($details);
    file_put_contents(LOG_FILE, $log . "\n", FILE_APPEND);
}
```

### System Resource Monitoring

```php
// Monitor CPU, Memory, Disk
$cpu = shell_exec("top -bn1 | grep 'Cpu(s)' | sed 's/.*, *\\([0-9.]*\\)%* id.*/\\1/' | awk '{print 100 - $1}'");
$memory = shell_exec("free -m | awk 'NR==2{print $3*100/$2 }'");
$disk = disk_free_space("/") / disk_total_space("/") * 100;

// Alert if critical
if ($cpu > 80 || $memory > 85 || $disk < 10) {
    sendAlert("Resource usage critical!");
}
```

---

## 🔄 Update & Maintenance

### Keep Dependencies Updated

```bash
# Check for outdated packages
npm audit

# Update Node packages (if used)
npm update

# Check PHP extensions
php -m | grep -i <extension>

# Update PHP
# (via your system package manager)
```

### Database Maintenance

```sql
-- Optimize tables
OPTIMIZE TABLE users;
OPTIMIZE TABLE weapons;
OPTIMIZE TABLE reports;

-- Check table integrity
CHECK TABLE users;
CHECK TABLE weapons;

-- Repair if needed
REPAIR TABLE weapons;
```

---

## 📝 Configuration Checklist

- [ ] Database credentials updated
- [ ] Session timeout configured
- [ ] Security headers enabled
- [ ] SSL/HTTPS configured
- [ ] CSRF protection verified
- [ ] Password hashing working
- [ ] Backup script running
- [ ] Logging enabled
- [ ] Error reporting configured
- [ ] Rate limiting implemented
- [ ] Default credentials changed
- [ ] File permissions correct (644, 755)

---

**Next Step**: Read [USER_GUIDE.md](USER_GUIDE.md) per imparare come usare il sistema.
