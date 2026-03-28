# 📋 Guida di Implementazione Audit Logging

## ✅ Cosa è stato implementato

### 1. **Security Headers** 
I seguenti header HTTP sono stati aggiunti in `db.php`:
- `X-Content-Type-Options: nosniff` - Previene MIME sniffing
- `X-Frame-Options: DENY` - Previene clickjacking
- `X-XSS-Protection: 1; mode=block` - Abilita protezione XSS del browser
- `Content-Security-Policy` - Whitelist risorse
- `Referrer-Policy` - Controlla informazioni referrer
- `Permissions-Policy` - Disabilita feature pericolose

### 2. **Audit Logging**
- ✅ Tabella `audit_logs` creata in database
- ✅ Funzione `log_audit()` per loggare azioni generiche
- ✅ Funzione `log_login()` per loggare accessi
- ✅ Funzione `get_audit_logs()` per recuperare i log
- ✅ Pagina admin `audit_logs.php` per visualizzare i log
- ✅ Export CSV dei log

---

## 🚀 Come Eseguire il Setup

### Step 1: Esegui lo script di setup
```
1. Vai a: http://localhost/public/setup.php
2. Attendi che la tabella audit_logs sia creata
3. Verifica il messaggio di successo
4. Puoi eliminare il file setup.php dopo l'esecuzione
```

### Step 2: Accedi alla dashboard di audit
```
1. Login come admin
2. Vai a: http://localhost/public/audit_logs.php
3. Visualizza i log di accesso
```

---

## 📝 Come Aggiungere Logging alle Operazioni CRUD

### Esempio 1: Logging di CREATE (Aggiunta record)
```php
<?php
// In weapons.php (aggiunta arma)

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] === 'add') {
    $nome = $_POST['nome_compratore'];
    $cognome = $_POST['cognome_compratore'];
    $seriale = $_POST['seriale'];
    $modello = $_POST['modello'];
    
    $sql = "INSERT INTO weapons (nome_compratore, cognome_compratore, seriale, modello) 
            VALUES ('$nome', '$cognome', '$seriale', '$modello')";
    
    if ($conn->query($sql) === TRUE) {
        $id = $conn->insert_id;
        
        // ✅ LOG AUDIT
        log_audit(
            'create',                            // azione
            'weapons',                           // modulo
            "Arma: $modello, Seriale: $seriale", // dettagli
            $id,                                 // record_id
            'success'                            // esito
        );
        
        header("Location: weapons.php?success=1");
    } else {
        log_audit('create', 'weapons', "Errore: " . $conn->error, null, 'error');
        $error = "Errore nell'aggiunta: " . $conn->error;
    }
}
?>
```

### Esempio 2: Logging di UPDATE (Modifica record)
```php
<?php
// In weapons.php (modifica arma)

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] === 'edit') {
    $id = $_POST['id'];
    $modello = $_POST['modello'];
    
    $sql = "UPDATE weapons SET modello = '$modello' WHERE id = $id";
    
    if ($conn->query($sql) === TRUE) {
        // ✅ LOG AUDIT
        log_audit(
            'update',                // azione
            'weapons',              // modulo
            "Modello aggiornato: $modello", // dettagli
            $id,                    // record_id
            'success'
        );
        
        header("Location: weapons.php?success=updated");
    } else {
        log_audit('update', 'weapons', "Errore: " . $conn->error, $id, 'error');
    }
}
?>
```

### Esempio 3: Logging di DELETE (Eliminazione record)
```php
<?php
// In weapons.php (revoca arma)

if (isset($_GET['revoke']) && is_numeric($_GET['revoke'])) {
    $id = (int)$_GET['revoke'];
    $sql = "UPDATE weapons SET revocata = 1 WHERE id = $id";
    
    if ($conn->query($sql) === TRUE) {
        // ✅ LOG AUDIT
        log_audit(
            'delete',                        // azione (revoca)
            'weapons',                       // modulo
            "Arma revocata",                 // dettagli
            $id,                             // record_id
            'success'
        );
        
        header("Location: weapons.php?success=revoked");
    } else {
        log_audit('delete', 'weapons', "Errore revoca: " . $conn->error, $id, 'error');
    }
}
?>
```

### Esempio 4: Logging di operazioni denegate (Accesso negato)
```php
<?php
// In users.php (modifica utente - solo admin può)

if ($user['ruolo'] !== 'admin') {
    // ✅ LOG AUDIT - Accesso negato
    log_audit(
        'update',
        'users',
        "Tentativo non autorizzato di modifica utente",
        $_POST['user_id'] ?? null,
        'denied'
    );
    
    die("❌ Accesso Negato!");
}
?>
```

---

## 📊 Campi della Tabella audit_logs

| Campo | Tipo | Descrizione |
|-------|------|-------------|
| `id` | INT | ID del log |
| `username` | VARCHAR(50) | Username utente che ha fatto l'azione |
| `ruolo` | VARCHAR(50) | Ruolo dell'utente |
| `azione` | VARCHAR(50) | Tipo di azione (login, create, update, delete) |
| `modulo` | VARCHAR(50) | Modulo interessato (weapons, users, reports, etc.) |
| `record_id` | INT | ID del record interessato |
| `dettagli` | TEXT | Dettagli aggiuntivi |
| `ip_address` | VARCHAR(45) | IP da cui è stata fatta l'azione |
| `user_agent` | TEXT | User agent del browser |
| `esito` | VARCHAR(20) | Esito (success, error, denied) |
| `timestamp` | DATETIME | Data e ora dell'azione |

---

## 🔍 Query Utili per Analizzare i Log

### Ultime 10 azioni di un utente
```sql
SELECT * FROM audit_logs 
WHERE username = 'admin1' 
ORDER BY timestamp DESC 
LIMIT 10;
```

### Tutte le azioni fallite
```sql
SELECT * FROM audit_logs 
WHERE esito = 'failed' OR esito = 'error'
ORDER BY timestamp DESC;
```

### Modifiche a weapons negli ultimi 7 giorni
```sql
SELECT * FROM audit_logs 
WHERE modulo = 'weapons' 
AND azione = 'update'
AND timestamp > DATE_SUB(NOW(), INTERVAL 7 DAY)
ORDER BY timestamp DESC;
```

### Accessi per IP
```sql
SELECT ip_address, COUNT(*) as accessi, 
       GROUP_CONCAT(DISTINCT username) as utenti
FROM audit_logs 
WHERE azione = 'login'
GROUP BY ip_address
ORDER BY accessi DESC;
```

---

## ✨ Prossimi Passi

1. **Implementa logging in tutti i file CRUD:**
   - `weapons.php` - Gestione armi
   - `aziende.php` - Gestione aziende
   - `reports.php` - Gestione report
   - `users.php` - Gestione utenti
   - `federal.php` - Gestione fascicoli federali
   - `internal.php` - Gestione IA

2. **Controlla i log regolarmente via:**
   - http://localhost/public/audit_logs.php

3. **Esporta report periodici:**
   - Usa il pulsante "Esporta CSV" per fare backup dei log

4. **Monitora per attività sospette:**
   - Accessi falliti ripetuti
   - Operazioni da IP inusuali
   - Accessi fuori orario

---

## 🔐 Sicurezza

- ✅ Security headers già implementati
- ✅ IP address tracciato
- ✅ User agent registrato
- ✅ Timestamp preciso
- ⚠️ **IMPORTANTE**: Cambia la password di default `Admin@1234` dopo il primo login!

---

## 📞 Supporto

Per domande o problemi:
1. Controlla che la tabella `audit_logs` esista
2. Verifica che `functions.php` sia incluso in `db.php`
3. Pulisci i log vecchi periodicamente con:
   ```sql
   DELETE FROM audit_logs WHERE timestamp < DATE_SUB(NOW(), INTERVAL 90 DAY);
   ```
