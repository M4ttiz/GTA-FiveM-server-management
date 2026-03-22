# 📖 User Guide - Gestionale-FiveM

Guida pratica per usare il sistema Gestionale-FiveM.

---

## 🚀 Primo Accesso

### Login
1. Vai a: `http://localhost/gestionale-fivem`
2. Inserisci credentials:
   - **Username**: `admin`
   - **Password**: `Admin@1234`
3. Click **Login**

### Cambia Password (IMPORTANTE!)
1. Clicca sul tuo **username** (top-right)
2. Seleziona **Change Password**
3. Inserisci:
   - Vecchia password
   - Nuova password (min 12 char, lettere maiuscole, numeri, simboli)
   - Conferma password
4. Click **Update**

---

## 📊 Dashboard

La homepage dopo il login.

**Elementi principali:**
- 📈 **Statistics**: Armi registrate, rapporti aperti, ecc.
- 🔔 **Recent Activity**: Azioni recenti nel sistema
- ⚡ **Quick Actions**: Accesso veloce alle funzioni principali
- ⚙️ **System Health**: Status database, PHP version, etc.

### Personalizza Dashboard
1. Vai a **Settings → Dashboard**
2. Scegli widgets da mostrare
3. Ordina per drag-and-drop
4. Click **Save**

---

## 🔫 Gestione Armi

### Registrare una Nuova Arma

1. Di navigazione: **Weapons → Register New**
2. Compila il modulo:
   - **Serial Number**: Es. `ARM-001` (unico!)
   - **Model**: Es. `Combat Pistol`
   - **Buyer Name**: Nome compratore
   - **Buyer Surname**: Cognome
   - **Weapon Type** (opzionale): pistola, fucile, shotgun, sniper
   - **Notes**: Dettagli aggiuntivi

3. Click **Register Weapon**

**Tip**: I serial number non possono essere duplicati. Il sistema ti avviserà se esiste uno simile.

### Ricerca Armi

1. Vai a **Weapons → Search**
2. Opzioni filtri:
   - **Status**: Attiva / Revocata
   - **Model**: Cerca per tipo di arma
   - **Owner Name**: Cerca per nome compratore
   - **Serial**: Cerca per numero seriale

3. Click **Search**

### Revocazione Arma

1. Nella lista weapons, clicca su **arma specifica**
2. Clicca bottone **Revoke**
3. Inserisci **Reason for Revocation**:
   - Es: "License expired"
   - Es: "Criminal activity"
   - Es: "Owner request"

4. Click **Confirm Revocation**

**Attenzione**: Questa azione è definitiva e registrata nel log audit!

### Export Weapons List

1. Vai a **Weapons → Search**
2. Applica filtri (opzionale)
3. Clicca **Export to CSV**
4. File scaricato automaticamente

---

## 📋 Rapporti Investigativi

### Creare un Nuovo Rapporto

1. Vai a **Reports → New Report**
2. Seleziona **Type**:
   - **Investigativo**: Rapporti PD standard
   - **Federale**: Casi federali (accesso ristretto)
   - **Interno**: Internal Affairs (confidenziale)

3. Compila:
   - **Title**: Titolo rapporto
   - **Description**: Dettagli investigazione
   - **Priority**: Bassa / Normale / Alta / Critica
   - **Assigned To**: Assegna a investigatore (opzionale)

4. Click **Create Report**

### Aggiorna Rapporto

1. Vai a **Reports → Search**
2. Clicca sul rapporto da modificare
3. Modifica i campi:
   - Status: aperto → chiuso → archiviato
   - Priority
   - Assignment
   - Description

4. Click **Update**

### Ricerca Rapporti

Filtri disponibili:
- **Status**: Aperto, Chiuso, Archiviato
- **Type**: Investigativo, Federale, Interno
- **Priority**: Bassa, Normale, Alta, Critica
- **Assigned To**: Nome investigatore
- **Date Range**: Data creazione

---

## 👥 Gestione Utenti (Admin Only)

### Creare Nuovo Utente

1. Vai a **Admin → Users → Add User**
2. Compila:
   - **Username**: Nome unico (3-50 chars)
   - **Password**: Password complessa (min 12 chars)
   - **Role**: Seleziona ruolo
   - **Email**: Indirizzo email (opzionale)

3. Click **Create User**

**Ruoli disponibili:**
- `admin`: Accesso completo
- `federal`: Federal service + reports
- `pd`: Police reports standard
- `armeria60`: Weapons system only
- `cid`: Reports only (civili)
- `interno`: Internal affairs only

### Gestisci Utenti

1. Vai a **Admin → Users**
2. Lista attiva mostra tutti gli utenti
3. Opzioni per ogni utente:
   - **Edit**: Modifica role, email, status
   - **Reset Password**: Reimposta a temporary
   - **Deactivate**: Disabilita accesso
   - **Delete**: Rimuovi completamente

### Reset Password Utente

1. Seleziona utente
2. Click **Reset Password**
3. Password temporanea inviata a email
4. Utente la cambia al prossimo login

---

## 🏢 Gestione Aziende

### Registrare Nuova Azienda

1. Vai a **Companies → Register**
2. Compila:
   - **Company Name**: Nome azienda (unico)
   - **Owner Name**: Nome proprietario
   - **Business Type**: Tipo attività (ristorante, negozio, ecc)
   - **Email**: Email aziendale
   - **License Number**: Numero licenza
   - **Opening Date**: Data apertura

3. Click **Register Company**

### Gestisci Dipendenti

1. Vai a **Companies**
2. Clicca sulla **company specifica**
3. Sezione **Employees**:
   - **Add Employee**: Aggiungi dipendente
   - **Edit**: Modifica dati
   - **Remove**: Rimuovi dalla company

4. Click **Save**

### Informazioni Dipendente

Campi tracciati:
- Nome, Cognome
- Ruolo in azienda
- Documento ID
- Stipendio netto
- Data assunzione
- Contatti (phone, email)

---

## ⚖️ Federal Service (Federal Users Only)

### Accesso Federal Section

1. Vai a **Federal Service**
2. Vedi solo casi assegnati a te + globali

**Permissions:**
- Creare cases federali
- Assegnare a investigatori federali
- Accesso "note private" per casi assegnati

### Creare Caso Federale

1. Clicca **New Federal Case**
2. Compila come rapporto standard
3. Select **Assegnato a**: Investigatore federale
4. Aggiungi note confidenziali se necessario
5. Click **Create**

---

## 🛡️ Internal Affairs (Admin/Internal Users Only)

### Accesso Internal Section

1. Vai a **Internal Affairs**
2. Vedi rapporti interni confidenziali

### Creare Rapporto Interno

1. Clicca **New Internal Report**
2. Compila:
   - Title (es: "Investigation Subject Name")
   - Detailed description
   - Evidence/findings
   - Recommendation

3. **Note Private**: Solo admin vede
4. Click **Create**

---

## 🔐 Security Best Practices

### Per Tutti gli Utenti:
- ✅ Logout quando finisci
- ✅ Non shareae password
- ✅ Cambia password ogni 90 giorni
- ✅ Report suspicious activity
- ❌ Non accedere da PC pubblici
- ❌ Non copiare dati sensibili
- ❌ Non discutere dati con non-autorizzati

### Per Admin:
- ✅ Monitora access logs quotidianamente
- ✅ Disattiva utenti inattivi dopo 30 giorni
- ✅ Backup database settimanali
- ✅ Review audit trail regolarmente
- ❌ Mai shareae database credentials
- ❌ Non dare accesso admin a test accounts

---

## 📊 Reports & Export

### Crea Custom Report

1. Vai a **Tools → Reports Generator**
2. Scegli tipo report:
   - Weekly summary
   - Weapons statistics
   - User activity
   - Federal cases status

3. Seleziona date range
4. Click **Generate**
5. Download PDF/CSV

### Export Data

**Formato CSV:**
- Headers inclusi
- UTF-8 encoding
- Semicolon delimited

**Formato PDF:**
- Professional formatting
- Company logo/header
- Timestamp di generazione

---

## 🆘 Aiuto & Supporto

### Se non puoi fare login:
1. Verifica username e password
2. Capslock attivo? Prova a spegnere
3. Contatta admin per reset

### Se un'azione non funziona:
1. Verifica di avere i permessi per quella azione
2. Prova a refreshare pagina (F5)
3. Svuota cache browser (CTRL+SHIFT+DEL)
4. Se persiste, contatta IT support

### Per domande su funzioni:
1. Leggi [docs/README.md](README.md)
2. Chiedi al tuo manager
3. Contatta amministratore sistema

---

## 💡 Tips & Tricks

### Scorciatoie Tastiera
- `CTRL+K`: Focus search bar
- `CTRL+/`: Mostra shortcuts help
- `ESC`: Chiudi modals/menus

### Filtri Veloci
- Clicca **Today** per rapporti di oggi
- Clicca **This Month** per mese corrente
- Clicca **My Cases** per tuoi assegnamenti

### Bulk Operations
- Seleziona più rapporti (checkbox)
- Clicca **Bulk Actions**
- Cambia status/assign a multiple alla volta

### Advanced Search
- Usa quotes: `"exact phrase"`
- Usa minus: `-word` per escludere
- Usa OR: `weapon OR firearms`
- Usa date range: `after:2026-03-01`

---

**Questions?** Vedi [TROUBLESHOOTING.md](TROUBLESHOOTING.md) o contatta admin.
