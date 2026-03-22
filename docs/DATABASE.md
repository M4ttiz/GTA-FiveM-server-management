# 🗄️ Database Schema - Gestionale-FiveM

Documentazione completa della struttura del database.

---

## 📊 Database Overview

**Nome Database**: `fivem_gestionale`  
**Charset**: UTF-8MB4 (supporta emoji)  
**Engine**: InnoDB (supporta FK constraints)

---

## 📋 Tabella: users

Contiene credenziali e ruoli utenti.

```sql
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,      -- Argon2ID hash
    ruolo VARCHAR(50) NOT NULL,          -- admin, federal, pd, etc.
    email VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_login TIMESTAMP NULL,
    is_active TINYINT(1) DEFAULT 1,
    INDEX idx_username (username),
    INDEX idx_ruolo (ruolo)
);
```

| Colonna | Tipo | Note |
|---------|------|------|
| `id` | INT | Primary key, auto-increment |
| `username` | VARCHAR(50) | Unique identifier |
| `password` | VARCHAR(255) | **MUST** use password_hash() |
| `ruolo` | VARCHAR(50) | admin\|federal\|pd\|armeria60\|cid\|interno |
| `email` | VARCHAR(100) | Optional email address |
| `created_at` | TIMESTAMP | Auto-set |
| `last_login` | TIMESTAMP | Aggiornato ad ogni login |
| `is_active` | TINYINT(1) | Soft delete |

### Ruoli Disponibili
```
admin      → Accesso completo a tutto
federal    → Accesso Federal service + Reports
pd         → Accesso Police reports + weapons view
armeria60  → Accesso Weapons system (Armeria 60)
cid        → Accesso Reports (civili)
interno    → Accesso Internal affairs
```

---

## 🔫 Tabella: weapons

Registra licenze ballistiche e armi registrate.

```sql
CREATE TABLE weapons (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome_compratore VARCHAR(100) NOT NULL,
    cognome_compratore VARCHAR(100) NOT NULL,
    seriale VARCHAR(50) UNIQUE NOT NULL,      -- Unique identifier
    modello VARCHAR(100) NOT NULL,
    tipo_arma VARCHAR(50),                    -- pistola, fucile, ecc
    data_registrazione TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    data_revoca TIMESTAMP NULL,
    revocata TINYINT(1) DEFAULT 0,
    motivo_revoca VARCHAR(255),
    registrato_da INT,                        -- FK to users
    revocato_da INT,                          -- FK to users
    note TEXT,
    FOREIGN KEY (registrato_da) REFERENCES users(id),
    FOREIGN KEY (revocato_da) REFERENCES users(id),
    INDEX idx_seriale (seriale),
    INDEX idx_revocata (revocata),
    INDEX idx_data (data_registrazione)
);
```

### Relazioni
- `registrato_da` → users.id (Chi ha registrato l'arma)
- `revocato_da` → users.id (Chi ha revocato)

---

## 📋 Tabella: reports

Rapporti investigativi generici.

```sql
CREATE TABLE reports (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titolo VARCHAR(255) NOT NULL,
    descrizione LONGTEXT,
    tipo VARCHAR(50) NOT NULL,         -- investigativo, federale, interno
    stato VARCHAR(50) DEFAULT 'aperto', -- aperto, chiuso, archiviato
    priorita VARCHAR(50) DEFAULT 'normale', -- bassa, normale, alta, critica
    
    creato_da INT NOT NULL,            -- FK to users
    assegnato_a INT,                   -- FK to users
    
    data_creazione TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    data_modifica TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    data_chiusura TIMESTAMP NULL,
    
    allegati JSON,                     -- URLs o nomi files
    note_private TEXT,                 -- Visibile solo admin
    
    FOREIGN KEY (creato_da) REFERENCES users(id),
    FOREIGN KEY (assegnato_a) REFERENCES users(id),
    INDEX idx_stato (stato),
    INDEX idx_tipo (tipo),
    INDEX idx_data_creazione (data_creazione)
);
```

---

## 🏢 Tabella: aziende

Registro aziendale e attività economiche.

```sql
CREATE TABLE aziende (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL UNIQUE,
    proprietario VARCHAR(100),          -- Nome proprietario
    email VARCHAR(100),
    telefono VARCHAR(20),
    indirizzo TEXT,
    
    tipo_attivita VARCHAR(100),         -- Es: ristorante, negozio, ecc
    licenza_numero VARCHAR(50),
    data_apertura DATE,
    
    attiva TINYINT(1) DEFAULT 1,
    data_registrazione TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    registrata_da INT,                  -- FK to users
    
    note TEXT,
    
    FOREIGN KEY (registrata_da) REFERENCES users(id),
    INDEX idx_nome (nome),
    INDEX idx_attiva (attiva)
);
```

---

## 👥 Tabella: dipendenti

Dipendenti delle aziende registrate.

```sql
CREATE TABLE dipendenti (
    id INT AUTO_INCREMENT PRIMARY KEY,
    azienda_id INT NOT NULL,           -- FK to aziende
    nome VARCHAR(100) NOT NULL,
    cognome VARCHAR(100),
    ruolo_azienda VARCHAR(100),         -- Es: Manager, Cassiere
    stipendio_netto INT,
    data_assunzione DATE,
    
    documento_id VARCHAR(50),           -- ID documento civile
    numero_telefono VARCHAR(20),
    email VARCHAR(100),
    
    attivo TINYINT(1) DEFAULT 1,
    data_registrazione TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    note TEXT,
    
    FOREIGN KEY (azienda_id) REFERENCES aziende(id) ON DELETE CASCADE,
    INDEX idx_azienda_id (azienda_id),
    INDEX idx_nome (nome)
);
```

### Relazioni
- `azienda_id` → aziende.id (Cascading delete)

---

## 🆗 Entity-Relationship Diagram

```
        users
        ├── weapons (registrato_da, revocato_da)
        ├── reports (creato_da, assegnato_a)
        └── aziende (registrata_da)
            └── dipendenti
```

---

## 📈 Query Comuni

### Get Armi Revocate
```sql
SELECT * FROM weapons 
WHERE revocata = 1 
ORDER BY data_revoca DESC;
```

### Get Rapporti Aperti Assegnati a Utente
```sql
SELECT * FROM reports 
WHERE assegnato_a = ? AND stato = 'aperto'
ORDER BY priorita DESC, data_creazione DESC;
```

### Get Dipendenti per Azienda
```sql
SELECT d.*, a.nome as azienda_nome
FROM dipendenti d
JOIN aziende a ON d.azienda_id = a.id
WHERE d.azienda_id = ? AND d.attivo = 1;
```

### Get Activity Log (via query audit)
```sql
SELECT u.username, a.action, a.timestamp
FROM audit_logs a
JOIN users u ON a.user_id = u.id
ORDER BY a.timestamp DESC
LIMIT 100;
```

---

## 🔐 Indici per Performance

```sql
-- Query frequenti
CREATE INDEX idx_weapons_revocata ON weapons(revocata, data_registrazione);
CREATE INDEX idx_reports_stato_assegnato ON reports(stato, assegnato_a);
CREATE INDEX idx_dipendenti_azienda_attivo ON dipendenti(azienda_id, attivo);
CREATE INDEX idx_users_username ON users(username);

-- Analizza dopo creazione
ANALYZE TABLE weapons;
ANALYZE TABLE reports;
ANALYZE TABLE aziende;
ANALYZE TABLE dipendenti;
ANALYZE TABLE users;
```

---

## 💾 Backup & Version Control

### Export Schema
```bash
mysqldump -u root --no-data fivem_gestionale > schema.sql
```

### Export Data
```bash
mysqldump -u root fivem_gestionale > full_backup.sql
```

### Diff con versione precedente
```bash
# Mantieni version control di schema
git show HEAD:schema.sql > schema.old
diff schema.old schema.sql
```

---

## 🔄 Future Enhancements

### Planned Tables (v1.1+)

#### audit_logs
```sql
CREATE TABLE audit_logs (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    action VARCHAR(255),
    table_name VARCHAR(100),
    record_id INT,
    old_values JSON,
    new_values JSON,
    ip_address VARCHAR(45),
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);
```

#### api_tokens
```sql
CREATE TABLE api_tokens (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    token VARCHAR(255) UNIQUE,
    scopes JSON,
    expires_at TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);
```

#### email_logs
```sql
CREATE TABLE email_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    recipient VARCHAR(255),
    subject VARCHAR(255),
    body LONGTEXT,
    status ENUM('sent', 'failed', 'pending'),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

---

**Next**: Leggi [API.md](API.md) per i endpoints disponibili.
