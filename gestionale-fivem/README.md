# FiveM Management System - Gestionale

> **Sistema di gestione web per server FiveM**  
> Una soluzione completa per la gestione di armi, investigazioni, aziende e dipendenti in un server FiveM roleplay.

---

## 📋 Indice

1. [Overview](#overview)
2. [Stack Tecnologico](#stack-tecnologico)
3. [Installazione](#installazione)
4. [Struttura del Progetto](#struttura-del-progetto)
5. [Autenticazione](#autenticazione)
6. [Moduli e Funzionalità](#moduli-e-funzionalità)
7. [Sistema di Permessi](#sistema-di-permessi)
8. [Utenti di Test](#utenti-di-test)
9. [Database](#database)
10. [API e Flussi](#api-e-flussi)
11. [Sicurezza](#sicurezza)
12. [Sviluppo Futuro](#sviluppo-futuro)

---

## Overview

Il **FiveM Management System** è una piattaforma web centralizzata per la gestione amministrativa di un server FiveM roleplay basato su GTA V. Fornisce strumenti per:

- 🔫 **Gestione Armi**: Registrazione, tracciamento e revoca di licenze ballistiche
- 📋 **Rapporti Investigativi**: Archiviazione centralizzata di inchieste e report
- 🏢 **Censimento Attività**: Registro aziendale con gestione dipendenti
- ⚖️ **Federal Service**: Fascicoli per indagini federali
- 🛡️ **Internal Affairs**: Rapporti confidenziali su investigazioni interne
- ⚙️ **Gestione Utenti**: Pannello admin per controllare utenti e permessi

**Design**: Tema corporate con accenti FiveM/GTA Online, interfaccia intuitiva e responsive.

---

## Stack Tecnologico

| Componente | Tecnologia |
|-----------|-----------|
| **Backend** | PHP 8.0+ |
| **Database** | MySQL/MariaDB |
| **Frontend** | HTML5 + CSS3 + Vanilla JS |
| **Charset** | UTF-8MB4 (supporto emoji e caratteri speciali) |
| **Server** | XAMPP / Apache |

---

## Installazione

### Prerequisiti

- XAMPP con Apache e MySQL
- PHP 8.0 o superiore
- Browser moderno

### Setup

1. **Clona/Copia il progetto**
   ```bash
   cd c:\xampp\htdocs
   # Copia la cartella gestionale-fivem
   ```

2. **Importa il database**
   ```bash
   # Apri phpMyAdmin (http://localhost/phpmyadmin)
   # Crea database: fivem_gestionale
   # Importa: database.sql
   ```

3. **Configura la connessione**
   ```php
   # File: public/db.php
   $conn = new mysqli("localhost", "root", "", "fivem_gestionale");
   ```

4. **Accedi al sito**
   ```
   http://localhost/gestionale-fivem/public/index.php
   ```

---

## Struttura del Progetto

```
gestionale-fivem/
├── database.sql             # Schema e dati iniziali
├── sql/
│   └── migration_dettagli.sql   # Migrazione opzionale (campi extra)
├── resources/fivem/
│   └── fivem_weapon_sync.lua    # Copia nel resource FiveM (weapon_sync)
├── package.json
├── server.js
├── web.config               # Opzionale (IIS)
├── README.md
└── public/                  # Document root
    ├── index.php
    ├── dashboard.php
    ├── style.css
    ├── db.php
    ├── logout.php
    ├── api_sync_weapons.php
    ├── weapons.php
    ├── reports.php
    ├── aziende.php
    ├── federal.php
    ├── internal.php
    └── users.php
```

---

## Autenticazione

### Flusso Login

```php
POST /index.php
├── Username + Password
├── Verifica credenziali vs tabella 'users'
├── Session['user'] = array(id, username, password, ruolo)
└── Redirect → dashboard.php
```

### Gestione Sessioni

- ✅ `session_start()` all'inizio di ogni pagina
- ✅ Controllo: `if (!isset($_SESSION['user']))` → Redirect login
- ✅ Salvataggio dati: `$user = $_SESSION['user']`
- ✅ Logout: Distrugge sessione e redirect

---

## Moduli e Funzionalità

### 🔫 **WEAPONS.PHP** - Archivio Balistico

**Scopo**: Registrazione e tracciamento armi, matricole, rilevamenti ballistici

**Funzionalità**:
- ✅ Registrazione nuova arma (nome, cognome, seriale, modello)
- ✅ Visualizzazione elenco armi con stato
- ✅ Badge per stato revoca (verde=attiva, rosso=revocata)
- ✅ Tabella ordinata per ID

**Database**: Tabella `weapons`
```sql
id, nome_compratore, cognome_compratore, seriale (UNIQUE), 
modello, tipo_utente, data_ora (TIMESTAMP), revocata (TINYINT)
```

**Accesso**: Tutti gli utenti

---

### 📋 **REPORTS.PHP** - Rapporti Investigativi

**Scopo**: Archivio centralizzato di investigazioni ufficiali

**Funzionalità**:
- ✅ Creazione nuovo rapporto (titolo, descrizione)
- ✅ Visualizzazione accordion-style dei rapporti
- ✅ Status badge (aperto, closed, etc.)
- ✅ Info creatore (username, ruolo)

**Database**: Tabella `reports`
```sql
id, titolo, descrizione, stato (DEFAULT 'aperto'), 
creato_da, ruolo_creatore, categoria, tipo
```

**Accesso**: Admin, CID, LSSD, Alto Comando LSPD/LSSD

---

### 🏢 **AZIENDE.PHP** - Censimento Attività

**Scopo**: Registro imprese, tracciamento dipendenti

**Funzionalità**:
- ✅ Registrazione nuova azienda (nome, titolare)
- ✅ Aggiunta dipendenti con ruolo
- ✅ Espansione accordeon per vedere dipendenti
- ✅ Visualizzazione struttura organizzativa

**Database**: Tabelle `aziende`, `dipendenti`
```sql
-- aziende
id, nome, titolare

-- dipendenti
id, azienda_id (FK), nome, ruolo
```

**Accesso**: Tutti tranne armeria60, armeria200

---

### ⚖️ **FEDERAL.PHP** - Federal Service

**Scopo**: Fascicoli per indagini federali

**Funzionalità**:
- ✅ Creazione fascicolo federale
- ✅ Elenco ordinato dei fascicoli
- ✅ Dettagli caso espandibili

**Database**: Tabella `reports` (con categoria='federal')

**Accesso**: Admin, Marshall

---

### 🛡️ **INTERNAL.PHP** - Internal Affairs

**Scopo**: Rapporti confidenziali di investigazioni interne (IAA)

**Funzionalità**:
- ✅ Creazione rapporto segreto (soggetto, descrizione)
- ✅ Elenco rapporti con espansione dettagli
- ✅ Protezione accesso (solo admin, iaa)

**Database**: Tabella `reports` (con categoria='internal')

**Accesso**: Admin, IAA

---

### ⚙️ **USERS.PHP** - Gestione Utenti (ADMIN ONLY)

**Scopo**: Pannello amministrativo per gestire utenti

**Funzionalità**:
- ✅ Aggiungi nuovo utente (username, password, ruolo)
- ✅ Modifica username, password, ruolo (inline editing)
- ✅ Elimina utente (con conferma)
- ✅ Protezione auto-eliminazione account admin
- ✅ Tabella completa di tutti gli utenti

**Database**: Tabella `users`
```sql
id, username (UNIQUE), password, ruolo
```

**Accesso**: Solo Admin

---

## Sistema di Permessi

### Matrice di Accesso

| Funzione | Admin | LSPD | LSSD | CID | Armeria60 | Armeria200 | Marshall | IAA | Procura | Alto LSPD | Alto LSSD |
|----------|:----:|:----:|:----:|:---:|:---------:|:---------:|:--------:|:---:|:-------:|:---------:|:---------:|
| Weapons | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ |
| Reports | ✅ | ❌ | ✅ | ✅ | ❌ | ❌ | ❌ | ❌ | ❌ | ✅ | ✅ |
| Aziende | ✅ | ✅ | ✅ | ✅ | ❌ | ❌ | ✅ | ✅ | ✅ | ✅ | ✅ |
| Federal | ✅ | ❌ | ❌ | ❌ | ❌ | ❌ | ✅ | ❌ | ❌ | ❌ | ❌ |
| Internal | ✅ | ❌ | ❌ | ❌ | ❌ | ❌ | ❌ | ✅ | ❌ | ❌ | ❌ |
| Users | ✅ | ❌ | ❌ | ❌ | ❌ | ❌ | ❌ | ❌ | ❌ | ❌ | ❌ |

### Logica di Controllo Accesso

```php
// Esempio da dashboard.php
<?php if(in_array($ruolo, ['admin', 'cid', 'lssd', 'alto_comando_lspd'])): ?>
    <div class="card" onclick="location.href='reports.php'">
        <!-- Accesso consentito -->
    </div>
<?php endif; ?>

// Protezione pagina (es. federal.php)
<?php
if (!in_array($ruolo, ['admin', 'marshall'])) {
    header("Location: dashboard.php");
    exit();
}
?>
```

---

## Utenti di Test

### Credenziali di Test

| Username | Password | Ruolo | Descrizione |
|----------|----------|-------|-------------|
| `admin1` | `admin1` | admin | Accesso completo + gestione utenti |
| `arma60` | `arma60` | armeria60 | Armeria 60 - Accesso limitato |
| `cid1` | `cid1` | cid | Criminal Investigation Division |
| `lspd1` | `lspd1` | lspd | LSPD Patrol Officer |
| `lssd1` | `lssd1` | lssd | Los Santos Sheriff |
| `procura1` | `procura1` | procura | Ufficio Procura |
| `marshall1` | `marshall1` | marshall | U.S. Marshals Service |
| `iaa1` | `iaa1` | iaa | Internal Affairs |
| `alto_lspd` | `alto_lspd` | alto_comando_lspd | Alto Comando LSPD |

### ⚠️ Nota sulla Sicurezza

In produzione, **CAMBIA TUTTE LE PASSWORD** e implementa:
- Hash password con `password_hash()` e `password_verify()`
- Session regeneration dopo login
- CSRF token
- SQL Injection prevention (già usato `real_escape_string()`)

---

## Database

### Schema Principale

#### users
```sql
id INT AUTO_INCREMENT PRIMARY KEY
username VARCHAR(50) NOT NULL UNIQUE
password VARCHAR(50) NOT NULL
ruolo VARCHAR(50) NOT NULL
```

#### weapons
```sql
id INT AUTO_INCREMENT PRIMARY KEY
nome_compratore VARCHAR(50)
cognome_compratore VARCHAR(50)
seriale VARCHAR(50) UNIQUE
modello VARCHAR(50)
tipo_utente INT
data_ora TIMESTAMP DEFAULT CURRENT_TIMESTAMP
revocata TINYINT(1) DEFAULT 0
```

#### reports
```sql
id INT AUTO_INCREMENT PRIMARY KEY
titolo VARCHAR(100)
descrizione TEXT
stato VARCHAR(20) DEFAULT 'aperto'
creato_da VARCHAR(50)
ruolo_creatore VARCHAR(50)
tipo VARCHAR(50)
categoria VARCHAR(50) -- 'investigativo', 'federale', 'interno'
```

#### aziende
```sql
id INT AUTO_INCREMENT PRIMARY KEY
nome VARCHAR(100)
titolare VARCHAR(100)
```

#### dipendenti
```sql
id INT AUTO_INCREMENT PRIMARY KEY
azienda_id INT
nome VARCHAR(100)
ruolo VARCHAR(50)
FOREIGN KEY (azienda_id) REFERENCES aziende(id) ON DELETE CASCADE
```

### Charset

```
UTF-8MB4 - Supporto completo emoji e caratteri speciali
```

---

## API e Flussi

### Flusso Creazione Arma

```
POST /weapons.php?action=add
├── nome_compratore
├── cognome_compratore
├── seriale (UNIQUE CHECK)
├── modello
└── Inserimento in DB + Redirect
```

### Flusso Creazione Rapporto

```
POST /reports.php?action=save
├── titolo
├── descrizione
├── auto-fill: creato_da = $_SESSION['user']['username']
├── auto-fill: ruolo_creatore = $_SESSION['user']['ruolo']
├── auto-fill: stato = 'aperto'
└── Inserimento in DB + Redirect
```

### Flusso Gestione Utente

```
GET  /users.php          → Lista tutti gli utenti
POST /users.php?add      → Aggiungi utente
POST /users.php?edit     → Modifica utente
POST /users.php?delete   → Elimina utente (protetto)
```

---

## Sicurezza

### Implementato ✅

- ✅ Autenticazione via sessione
- ✅ Controllo accesso per ruolo
- ✅ SQL Injection prevention (`real_escape_string()`)
- ✅ XSS prevention (`htmlspecialchars()`)
- ✅ Charset UTF-8MB4
- ✅ Foreign Key constraints
- ✅ Protezione auto-eliminazione admin

### Da Implementare ⚠️

- ❌ Password hashing (`password_hash()`)
- ❌ CSRF token
- ❌ Session regeneration post-login
- ❌ Rate limiting
- ❌ Input validation centralizzata
- ❌ Audit logging
- ❌ HTTPS/SSL
- ❌ Security headers

---

## Sviluppo Futuro

### Miglioramenti Consigliati

**Priority 1 (CRITICA)**
- [ ] Implement password hashing
- [ ] Add CSRF token protection
- [ ] Session regeneration after login
- [ ] Security headers (CSP, X-Frame-Options, etc.)

**Priority 2 (IMPORTANTE)**
- [ ] Centralized input validation
- [ ] Audit logging
- [ ] Error handling standardizzato
- [ ] Pagination per liste lunghe

**Priority 3 (NICE-TO-HAVE)**
- [ ] Flash messages
- [ ] PDF export per rapporti
- [ ] Search/filter avanzati
- [ ] Statistiche dashboard
- [ ] Email notifications
- [ ] API REST

**Quality of Life**
- [ ] Dark mode toggle
- [ ] Multi-language support
- [ ] Mobile app
- [ ] WebSocket real-time updates

---

## Contatti e Supporto

**Sviluppatore**: pasqui
**Versione**: 1.0 
**Data**: 20 March 2026

---

## License

Proprietario - pasqui

---

## Changelog

### v1.0 (March 2026)
- ✅ Sistema di gestione completo
- ✅ 6 moduli funzionali
- ✅ Gestione utenti admin
- ✅ Tema professional corporate
- ✅ RBAC implementato
- ✅ Database normalizzato