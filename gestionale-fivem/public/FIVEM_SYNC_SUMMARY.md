# 🎮 Implementazione Sincronizzazione FiveM-Gestionale

**Data**: 23 Marzo 2026  
**Status**: ✅ COMPLETATO

---

## 📦 File Creati

### 1. **api_sync_weapons.php** (NEW) ⭐
**Endpoint PHP sicuro** che riceve i dati dal server FiveM e aggiunge le armi al database.

**Funzioni:**
- ✅ Validazione API Key
- ✅ Validazione JSON input
- ✅ Controllo seriale univoco
- ✅ Insert nel database
- ✅ Audit logging
- ✅ Risposta JSON

**Uso:**
```
POST http://localhost/gestionale_fivem_master/gestionale-fivem/public/api_sync_weapons.php
Header: X-API-Key: [chiave]
Body: JSON con dati arma
```

---

### 2. **fivem_weapon_sync.lua** (NEW) ⭐
**Script Lua** pronto all'uso per il server FiveM che sincronizza le armi.

**Funzioni:**
- ✅ Comando `/registerweapon` per test
- ✅ Evento `gestionale:syncWeapon` per integrazione
- ✅ Gestione risposta HTTP
- ✅ Messaggi di feedback in chat
- ✅ Console logging

**Uso:**
1. Copia in `resources/weapon_sync/`
2. Crea `fxmanifest.lua` nella cartella
3. Aggiungi `ensure weapon_sync` in server.cfg
4. Restart server

---

### 3. **migrate_weapons_table.php** (NEW)
**Script di migrazione** che aggiunge colonne al database per la sincronizzazione.

**Colonne Aggiunte:**
- `server_sync` - Nome server FiveM
- `citizen_id` - ID cittadino
- `prezzo` - Prezzo arma

**Uso:**
```
URL: http://localhost/gestionale_fivem_master/gestionale-fivem/public/migrate_weapons_table.php
```

---

### 4. **INTEGRAZIONE_FIVEM.md** (NEW)
**Documentazione completa** con:
- Setup iniziale step-by-step
- Configurazione API
- Riferimento API (endpoints, request/response)
- Comandi FiveM
- Integrazione con shop/armeria
- Troubleshooting
- Security best practices

---

## 🔐 Sicurezza Implementata

| Misura | Dettagli |
|--------|----------|
| API Key | Autenticazione via header X-API-Key |
| Input Validation | Validazione lunghezza, tipo, format |
| Serial Uniqueness | Impedisce armi duplicate |
| HTTPS Support | Pronto per SSL/TLS |
| Audit Logging | Tutti i sync tracciati |
| IP Tracking | IP registrato in audit_logs |
| Error Handling | Risposta JSON con errori specifici |

---

## 🚀 Step di Implementazione Rapidi

### **Per il Gestore del Gestionale:**

```bash
# 1. Esegui migrazione database
http://localhost/gestionale_fivem_master/gestionale-fivem/public/migrate_weapons_table.php

# 2. Ottieni lo script Lua
# File: fivem_weapon_sync.lua (nel public folder)

# 3. Configura API Key
# Cambia in api_sync_weapons.php:
define('API_KEY', 'la-tua-chiave-segura');

# 4. Fornisci ai proprietari del server:
# - fivem_weapon_sync.lua
# - INTEGRAZIONE_FIVEM.md
# - API_KEY (in privato!)
```

### **Per il Proprietario del Server FiveM:**

```bash
# 1. Crea cartella
mkdir resources/weapon_sync

# 2. Copia file ricevuti
# - fivem_weapon_sync.lua

# 3. Crea fxmanifest.lua e configura:
# - Endpoint URL (ricevuto dal gestore)
# - API Key (ricevuta dal gestore)
# - SERVER_NAME

# 4. Aggiungi in server.cfg:
ensure weapon_sync

# 5. Restart server FiveM
refresh
restart weapon_sync

# 6. Test comando:
/registerweapon PISTOL_1 TEST123 5000
```

---

## 📊 Campi della Tabella weapons (Aggiornato)

```sql
id                INT - ID arma
nome_compratore   VARCHAR(50) - Nome giocatore
cognome_compratore VARCHAR(50) - Cognome (opzionale)
seriale           VARCHAR(50) - Seriale UNICO
modello           VARCHAR(50) - Modello arma
tipo_utente       INT - Tipo utente (legacy)
data_ora          TIMESTAMP - Data acquisizione
revocata          TINYINT - Se revocata (0/1)
server_sync       VARCHAR(50) - Server FiveM di provenienza ⭐ NEW
citizen_id        VARCHAR(50) - ID cittadino FiveM ⭐ NEW
prezzo            DECIMAL(10,2) - Prezzo pagato ⭐ NEW
```

---

## 🔄 Flusso di Sincronizzazione

```
┌─────────────────────────────────────────────────┐
│   Server FiveM (Lua Script)                     │
│   /registerweapon PISTOL_1 ABC123 5000          │
└──────────────────┬──────────────────────────────┘
                   │
                   │ HTTP POST (JSON)
                   │ X-API-Key: [key]
                   ↓
┌─────────────────────────────────────────────────┐
│   api_sync_weapons.php (Endpoint)               │
│   - Valida API Key                              │
│   - Valida Input                                │
│   - Controlla Serial Unico                      │
└──────────────────┬──────────────────────────────┘
                   │
                   │ INSERT INTO weapons
                   ↓
┌─────────────────────────────────────────────────┐
│   Database MySQL (fivem_gestionale)             │
│   Tabella: weapons                              │
│   Log: audit_logs                               │
└─────────────────────────────────────────────────┘
                   │
                   │ JSON Response (201)
                   │ { success: true, weapon_id: 42 }
                   ↓
┌─────────────────────────────────────────────────┐
│   FiveM Console & Chat                          │
│   ✅ Arma registrata nel database! ID: 42      │
└─────────────────────────────────────────────────┘
```

---

## 📋 Checklist di Installazione

### Per il Gestore:
- [ ] Esegui migrate_weapons_table.php
- [ ] Cambia API_KEY in api_sync_weapons.php
- [ ] Testa l'endpoint con Postman/curl
- [ ] Documenta API Key (comunicare al proprietario server)
- [ ] Backup database prima di andare in produzione

### Per il Proprietario Server FiveM:
- [ ] Ricevi files e API Key dal gestore
- [ ] Crea cartella resources/weapon_sync
- [ ] Copia fivem_weapon_sync.lua
- [ ] Crea fxmanifest.lua
- [ ] Configura GESTIONALE_URL, API_KEY, SERVER_NAME
- [ ] Aggiungi `ensure weapon_sync` in server.cfg
- [ ] Restart server FiveM
- [ ] Testa comando `/registerweapon PISTOL_1 TEST123 1000`
- [ ] Verifica dati in Gestionale (weapons.php)
- [ ] Integra nel sistema di shop/armeria

---

## 🧪 Test Rapidi

### Test 1: Endpoint PHP direttamente
```bash
curl -X POST http://localhost/gestionale_fivem_master/gestionale-fivem/public/api_sync_weapons.php \
  -H "Content-Type: application/json" \
  -H "X-API-Key: your-secret-api-key" \
  -d '{
    "player_name": "Test Player",
    "weapon_model": "PISTOL_1",
    "weapon_serial": "TEST123456",
    "server_name": "main_server",
    "price": 5000
  }'
```

### Test 2: Comando FiveM
```
/registerweapon PISTOL_1 ABC123XYZ 5000 CITZ_123
```

### Test 3: Visualizza in Gestionale
```
http://localhost/gestionale_fivem_master/gestionale-fivem/public/weapons.php
(Login come admin, cercalo nella lista)
```

---

## 📞 Documentazione Fornita

Se devi fornire al proprietario del server, includi:

1. **fivem_weapon_sync.lua** - Script pronto all'uso
2. **INTEGRAZIONE_FIVEM.md** - Guida completa
3. **API_KEY** (comunicare privatamente)
4. **URL Endpoint:**
   ```
   http://localhost/gestionale_fivem_master/gestionale-fivem/public/api_sync_weapons.php
   ```

---

## ⏱️ Tempo di Setup

| Fase | Tempo |
|------|-------|
| Implementazione API | 30 min |
| Script Lua | 20 min |
| Migration DB | 5 min |
| Documentazione | 25 min |
| **TOTALE** | **~80 minuti** |

---

## 🎯 Prossimi Passi Opzionali

1. **Sincronizzare altri dati:**
   - Report investigativi
   - Bans/Kicks dal server
   - Attività giocatori

2. **Webhook FiveM → Gestionale:**
   - Notifiche real-time
   - Discord webhooks per events

3. **Dashboard in Gestionale:**
   - Stats armi sincronizzate
   - Grafico server attivi
   - Ultimi sync

4. **Integrazione Pagamenti:**
   - Registrare transazioni
   - Cronologia prezzi

---

**✅ IMPLEMENTAZIONE SINCRONIZZAZIONE FIVEM COMPLETATA!**

Pronta per essere utilizzata da qualsiasi server FiveM.
