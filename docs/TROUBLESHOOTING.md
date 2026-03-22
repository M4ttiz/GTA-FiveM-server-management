# 🆘 Troubleshooting - Gestionale-FiveM

Guida per risolvere problemi comuni.

---

## 🔌 Problemi di Connessione

### ❌ "Unable to connect to database"

**Possibili cause:**
- MySQL non è avviato
- Credenziali database errate
- Port 3306 bloccata

**Soluzioni:**

1. **Verifica MySQL sia avviato**
   ```bash
   # XAMPP Control Panel: Start MySQL
   # Oppure in terminal su Linux:
   sudo service mysql start
   ```

2. **Verifica credenziali in `db.php`**
   ```php
   const DB_HOST = 'localhost';
   const DB_USER = 'root';
   const DB_PASS = ''; // Vuota per XAMPP default
   ```

3. **Testa connessione**
   ```bash
   # Linux/Mac
   mysql -u root -p -e "USE fivem_gestionale; SHOW TABLES;"
   
   # Oppure via PhpMyAdmin
   http://localhost/phpmyadmin
   ```

4. **Se MySQL crashe:**
   ```bash
   # Ripara database
   mysqlcheck -u root --repair --all-databases
   ```

---

## 🌐 Problemi di Accesso

### ❌ "Login Failed"

**Soluzione 1: Verifica credenziali**
- Username: `admin`
- Password: `Admin@1234`
- No spaces, case-sensitive per password

**Soluzione 2: Reset password**
```sql
-- Connettiti alla DB
mysql -u root fivem_gestionale
UPDATE users SET password = SHA2('Admin@1234', 256) WHERE username = 'admin';
```

**Soluzione 3: Ricrea utente admin**
```sql
DELETE FROM users WHERE username = 'admin';
INSERT INTO users (username, password, ruolo) 
VALUES ('admin', password('Admin@1234'), 'admin');
```

### ❌ "Session Expired" / "Access Denied"

**Cause:**
- Session timeout (solitamente 1 ora)
- User deleted Admin e inattivo
- Permessi mancanti per azione

**Soluzioni:**
1. Logout e log back in
2. Verifica che il tuo account sia attivo
3. Contatta admin per aumentare permessi

---

## 📊 Problemi di Database

### ❌ "Table doesn't exist"

**Causa:** Database schema non importato

**Soluzione:**
```bash
cd gestionale-fivem/
mysql -u root < database.sql

# Verifica importazione riuscita
mysql -u root fivem_gestionale -e "SHOW TABLES;"
# Dovrebbe mostrare: users, weapons, reports, aziende, dipendenti
```

### ❌ "Duplicate Entry" error

**Causa:** Tentativo di inserire seriale/username duplicato

**Soluzione:**
1. Usa seriale diverso
2. Controlla se record esiste nel database già

**Esempio ricerca:**
```sql
SELECT * FROM weapons WHERE seriale = 'DUPLICATED-SERIAL';
```

### ❌ Database molto lento

**Cause:**
- Indici mancanti
- Tabelle molto grandi
- Query inefficiente

**Soluzioni:**

1. **Crea indici**
   ```sql
   CREATE INDEX idx_weapons_revocata ON weapons(revocata, data_registrazione);
   CREATE INDEX idx_reports_stato ON reports(stato);
   ANALYZE TABLE weapons;
   ANALYZE TABLE reports;
   ```

2. **Ottimizza tabelle**
   ```sql
   OPTIMIZE TABLE weapons;
   OPTIMIZE TABLE reports;
   OPTIMIZE TABLE users;
   ```

3. **Monitora query lente**
   ```bash
   # In MySQL
   SET GLOBAL slow_query_log = 'ON';
   SET GLOBAL long_query_time = 2;
   tail -f /var/log/mysql/slow-query.log
   ```

---

## 💾 Problemi di File

### ❌ "File Not Found" (404)

**Controllare:**
1. URL corretto? Es: `http://localhost/gestionale-fivem/public/index.php`
2. File esiste in cartella giusta?
3. Cartella è in `C:\xampp\htdocs\gestionale-fivem\`?

**Soluzione:**
```bash
# Verifica struttura cartelle
ls -la C:\xampp\htdocs\gestionale-fivem\
# Dovrebbe contenere: public/, database.sql, package.json, README.md
```

### ❌ "Permission Denied"

**Causa:** File permissions sbagliate

**Soluzione (Linux/Mac):**
```bash
chmod 755 gestionale-fivem/
chmod 644 gestionale-fivem/public/*.php
chmod 644 gestionale-fivem/public/*.css
chmod 644 gestionale-fivem/public/*.js
```

### ❌ File upload non funziona

**Verifica in `public/db.php`:**
```php
define('MAX_UPLOAD_SIZE', 10 * 1024 * 1024); // 10MB
define('UPLOAD_DIR', dirname(__FILE__) . '/uploads/');

// Crea cartella se non esiste
if (!is_dir(UPLOAD_DIR)) {
    mkdir(UPLOAD_DIR, 0755, true);
}
```

---

## 🔐 Problemi di Sicurezza

### ❌ "CSRF Token Invalid"

**Causa:** Token scaduto o mismatch

**Soluzione:**
1. Refresh pagina
2. Prova form di nuovo
3. Se persiste, logout/login

**Debug:**
```php
// In form
echo "Token: " . $_SESSION['csrf_token'];
echo "<input type='hidden' name='csrf_token' value='" . $_SESSION['csrf_token'] . "'>";
```

### ❌ "Access Denied" su moduli

**Causa:** Permessi insufficienti per ruolo

**Soluzione:**
1. Contatta admin
2. Chiedi di upgrading ruolo
3. O crea nuovo account con ruolo giusto

---

## 🔧 Problemi di PHP

### ❌ "PHP Version Error"

**Causa:** PHP < 8.0

**Controllare versione:**
```bash
# Terminal
php --version

# Oppure in browser
# http://localhost -> cerca phpinfo() link
```

**Se < 8.0:**
1. Aggiorna XAMPP a versione recente
2. O installa PHP 8.0+ manualmente
3. Aggiorna Apache configuration

### ❌ "Call to undefined function"

**Causa:** Extension PHP mancanate

**Soluzioni:**

Per MySQLi:
```bash
# php.ini deve avere (no semicolon all'inizio):
extension=mysqli
extension=pdo_mysql
```

Per JSON:
```bash
# JSON è built-in in PHP 7.2+, no action needed
```

**Restart Apache dopo modifiche a php.ini**

### ❌ "Memory limit exceeded"

**Causa:** Script usa troppa memoria

**Soluzione:**
```php
// In php.ini o .htaccess
memory_limit = 256M
max_execution_time = 300
```

---

## 🖥️ Problemi di XAMPP

### ❌ Apache non start

```bash
# Controlla porta 80 occupata
# Windows
netstat -ano | findstr :80

# Linux/Mac
lsof -i :80

# Kill processo se necessario
# Windows
taskkill /PID 1234 /F

# Linux/Mac
kill -9 1234

# Riavvia Apache in XAMPP Control Panel
```

### ❌ MySQL non start

```bash
# XAMPP: Clicca "Admin" button accanto MySQL
# Oppure in terminal:

# Linux/Mac
mysql -u root
# Se pede password: prova <vuoto> o "password"

# Windows
cd C:\xampp\mysql\bin
mysql.exe -u root
```

---

## 📈 Performance Issues

### Pagina carica lentamente

**Debug steps:**
1. Apri **Developer Tools** (F12)
2. Tab **Network**
3. Refresh pagina
4. Guarda tempi di caricamento:
   - CSS: deve essere < 200ms
   - JS: deve essere < 200ms
   - API calls: < 500ms

**Soluzioni:**

1. **Cache abilitare**
   ```php
   header("Cache-Control: max-age=3600");
   header("Expires: " . gmdate("D, d M Y H:i:s T", time() + 3600));
   ```

2. **Comprimi files**
   ```apache
   # In .htaccess
   <IfModule mod_deflate.c>
       AddOutputFilterByType DEFLATE text/html text/plain text/xml
   </IfModule>
   ```

3. **Minimize JS/CSS**
   - Usa tools come minify
   - Riduci numero di files

---

## 🔍 Debugging Tips

### Abilita Error Reporting

```php
// In top of index.php (development only!)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Log to file instead
ini_set('log_errors', 1);
ini_set('error_log', '/tmp/php-errors.log');
```

### Usa Browser DevTools

1. **F12** per aprire
2. **Console** tab per errori JavaScript
3. **Network** tab per HTTP requests
4. **Storage** tab per sessions/cookies

### Log Database Queries

```php
// In db.php
define('DEBUG_MODE', true);

if (DEBUG_MODE) {
    error_log("QUERY: " . $query);
}
```

---

## 📞 Come Reportare un Bug

Se trovi bugge:

1. **Documenta il problema:**
   - Passo-per-passo per riprodurlo
   - Screenshot di errore
   - Browser/PHP version

2. **Apri GitHub Issue:**
   - Vai a: https://github.com/M4ttiz/GTA-FiveM-server-management/issues
   - Click "New Issue"
   - Descrivi il problema

3. **Per sicurezza critical:**
   - Vedi [SECURITY.md](../SECURITY.md)
   - Non postare dettagli pubblicamente

---

**Still stuck?** Contatta admin o apri GitHub discussion!
