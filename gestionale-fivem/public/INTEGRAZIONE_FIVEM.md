# 🎮 Guida Integrazione FiveM → Gestionale

**Sincronizzazione automatica delle armi dal server FiveM al Gestionale**

---

## 📋 Overview

Questa guida spiega come integrare il server FiveM con il Gestionale in modo che ogni arma acquistata nel server FiveM venga registrata automaticamente nel database del Gestionale.

### Flusso Dati:
```
Server FiveM (Lua)
    ↓ HTTP POST
Gestionale API (endpoint: api_sync_weapons.php)
    ↓ INSERT
Database MySQL (tabella: weapons)
```

---

## 🚀 Setup Iniziale

### Step 1: Esegui la Migrazione del Database
```
URL: http://localhost/gestionale_fivem_master/gestionale-fivem/public/migrate_weapons_table.php
```

Questo aggiungerà 3 nuove colonne al database:
- `server_sync` - Nome del server FiveM
- `citizen_id` - ID cittadino del giocatore (opzionale)
- `prezzo` - Prezzo pagato per l'arma

### Step 2: Configurazione API Key
**File:** `api_sync_weapons.php`

Cambia questa riga:
```php
define('API_KEY', 'your-secret-api-key-here-change-this');
```

Con una chiave sicura a tua scelta (es: `mk3Xj9pQwLm2nR5sT`). 

**IMPORTANTE:** Deve essere la stessa nel file Lua!

### Step 3: Configura lo Script Lua
**File:** `fivem_weapon_sync.lua`

Cambia questi parametri:
```lua
local GESTIONALE_URL = "http://localhost/gestionale_fivem_master/gestionale-fivem/public/api_sync_weapons.php"
local API_KEY = "your-secret-api-key-here-change-this"  -- DEVE CORRISPONDERE A api_sync_weapons.php
local SERVER_NAME = "main_server"  -- Nome univoco (main_server, test_server, etc)
```

### Step 4: Installa lo Script nel Server FiveM

1. **Crea una nuova cartella** in `resources/`:
```
resources/weapon_sync/
```

2. **Copia i file:**
   - `fivem_weapon_sync.lua` → `resources/weapon_sync/`
   
3. **Crea `fxmanifest.lua`** in `resources/weapon_sync/`:
```lua
fx_version 'cerulean'
game 'gta5'

author 'Gestionale Team'
description 'Weapon Synchronization System'
version '1.0.0'

shared_scripts {
    'fivem_weapon_sync.lua'
}
```

4. **Aggiungi in `server.cfg`:**
```
ensure weapon_sync
```

5. **Restart il server:**
```
refresh
restart weapon_sync
```

---

## 🔗 Utilizzo API

### Endpoint
```
POST http://localhost/gestionale_fivem_master/gestionale-fivem/public/api_sync_weapons.php
```

### Headers Richiesti
```
Content-Type: application/json
X-API-Key: [your-api-key]
```

### Body JSON
```json
{
  "player_name": "John Doe",
  "weapon_model": "PISTOL_1",
  "weapon_serial": "ABC123XYZ",
  "server_name": "main_server",
  "price": 5000,
  "citizen_id": "CITZ_12345"
}
```

### Campi Obbligatori
- `player_name` - Nome del giocatore
- `weapon_model` - Modello arma (PISTOL_1, RIFLE_1, etc)
- `weapon_serial` - Seriale UNICO dell'arma
- `server_name` - Nome server (deve corrispondere a SERVER_NAME in Lua)

### Campi Opzionali
- `price` - Prezzo pagato (default: 0)
- `citizen_id` - ID cittadino FiveM

### Risposta di Successo (201)
```json
{
  "success": true,
  "message": "Weapon added successfully",
  "weapon_id": 42,
  "timestamp": "2026-03-23 15:30:45"
}
```

### Errori Possibili

| Codice | Errore | Causa |
|--------|--------|-------|
| 405 | Method not allowed | Non usi POST |
| 400 | Invalid JSON | JSON malformato |
| 400 | Missing required fields | Mancano campi obbligatori |
| 409 | Weapon serial already exists | Seriale duplicato |
| 401 | Invalid API Key | API Key sbagliata |
| 500 | Database error | Errore database |

---

## 💻 Comandi FiveM Disponibili

### Registrazione Manuale (per test)
```
/registerweapon <model> <serial> [price] [citizen_id]
```

**Esempio:**
```
/registerweapon PISTOL_1 ABC123XYZ 5000 CITZ_12345
```

---

## 🔧 Integrazione con Sistemi di Shop/Armeria

Se hai un sistema di negozio/armeria, integra così:

### Metodo 1: Usa l'Evento Gestionale
Quando il giocatore compra un'arma:

```lua
-- Nel tuo script di shop/armeria
local playerID = source
local playerName = GetPlayerName(playerID)
local weaponModel = "PISTOL_1"
local weaponSerial = GenerateUniqueSerial()  -- La tua funzione
local price = 5000
local citizenID = GetPlayerCitizenID(playerID)  -- La tua funzione

TriggerEvent('gestionale:syncWeapon', playerID, playerName, weaponModel, weaponSerial, price, citizenID)
```

### Metodo 2: Chiama la Funzione Direttamente
```lua
-- Nel tuo script
local source = source
local playerName = GetPlayerName(source)
local weaponModel = "PISTOL_1"
local weaponSerial = GenerateUniqueSerial()

TriggerEvent('gestionale:syncWeapon', source, playerName, weaponModel, weaponSerial, 5000, citizenID)
```

---

## 📊 Visualizzazione Dati Sincronizzati

Dopo la sincronizzazione, i dati sono disponibili nel Gestionale:

1. **Login come admin:**
```
http://localhost/gestionale_fivem_master/gestionale-fivem/public/index.php
```

2. **Vai a Archivio Balistico:**
```
http://localhost/gestionale_fivem_master/gestionale-fivem/public/weapons.php
```

3. **Visualizza le armi sincronizzate** con:
   - Nome giocatore
   - Modello arma
   - Seriale
   - Server di provenienza
   - Data acquisizione
   - Prezzo

---

## 🔒 Sicurezza

### Best Practices Implementate:
- ✅ **API Key** - Autenticazione via header X-API-Key
- ✅ **HTTPS** - Supporto SSL/TLS (cambia localhost con https://)
- ✅ **Input Validation** - Validazione completa input
- ✅ **Serial Uniqueness** - Impedisce duplicati
- ✅ **Audit Logging** - Tutti i sync registrati in audit_logs
- ✅ **IP Tracking** - Tracciamento IP richieste

### Configurazione Sicura:
1. **Cambia API_KEY** - Usa una chiave secura e casuale
2. **Usa HTTPS in Produzione** - Non usare HTTP
3. **Whitelist IP** - Permetti solo IP del server FiveM
4. **Backup Regolari** - Backup del database

---

## 🐛 Troubleshooting

### Problema: "Unauthorized. Invalid API Key"
**Soluzione:**
- Verifica che `API_KEY` in `api_sync_weapons.php` sia uguale a `API_KEY` in `fivem_weapon_sync.lua`
- Verifica che nel header sia `X-API-Key` (maiuscole corrette)

### Problema: "Invalid JSON format"
**Soluzione:**
- Assicurati che il body sia JSON valido
- Verifica il Content-Type: application/json

### Problema: "Weapon serial already exists"
**Soluzione:**
- Ogni arma DEVE avere un seriale UNICO
- Non synchronizzare la stessa arma due volte

### Problema: Script Lua non carica
**Soluzione:**
- Controlla `fxmanifest.lua` è nella cartella resource
- Verifica che il path sia corretto in `fxmanifest.lua`
- Riavvia il server FiveM: `refresh` + `restart weapon_sync`

### Problema: Connessione rifiutata
**Soluzione:**
- Verifica che il server PHP sia online
- Controlla che XAMPP sia avviato
- Verifica l'URL sia corretto (con il path completo)
- Firewall potrebbe bloccare: apri porta 80

---

## 📝 Log e Monitoraggio

Tutti i sync sono registrati in `audit_logs` con:
- Username/Server che ha fatto la richiesta
- Dati completi dell'arma
- IP di provenienza
- Timestamp
- Esito (success/error)

**Visualizza i log:**
```
http://localhost/gestionale_fivem_master/gestionale-fivem/public/audit_logs.php
```
(Accesso admin)

---

## 🚀 Prossimi Passi

1. **Configura la tua API_KEY** - Non dimenticare!
2. **Testa con il comando**: `/registerweapon PISTOL_1 TEST123 1000`
3. **Integra nel tuo sistema di shop** - Usa l'evento `gestionale:syncWeapon`
4. **Monitora i log** - Controlla audit_logs regolarmente
5. **Fai backup** - Backup database periodico

---

## 📞 Supporto

Per problemi o domande:
1. Controlla la sezione **Troubleshooting**
2. Verifica i **Log di Audit** per errori
3. Controlla la console del server FiveM:
   ```
   [GESTIONALE SYNC] ✅ Arma sincronizzata...
   [GESTIONALE SYNC] ❌ Errore...
   ```

---

**✅ Integrazione FiveM Completata e Pronta all'Uso!**
