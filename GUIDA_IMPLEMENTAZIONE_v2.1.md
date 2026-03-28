# 🚀 GUIDA IMPLEMENTAZIONE - MODIFICHE DETTAGLI v2.1

## FASE 1: PREPARAZIONE DATABASE

### Prerequisiti
- ✅ MySQLi Server avviato (XAMPP)
- ✅ Database `fivem_gestionale` existente
- ✅ Backup del database (consigliato)

### Step 1.1: Backup Database
```bash
# Windows (Command Prompt)
cd C:\xampp\mysql\bin
mysqldump -u root -p fivem_gestionale > "C:\xampp\htdocs\gestionale_fivem_master\backup_pre_migration_28-03-2026.sql"
```

### Step 1.2: Eseguire Migrazione
```bash
# Metodo 1: MySQL Console
mysql -u root -p fivem_gestionale < "C:\xampp\htdocs\gestionale_fivem_master\gestionale-fivem\database_migration_dettagli.sql"

# Metodo 2: PhpMyAdmin (Localhost)
1. Aprire: http://localhost/phpmyadmin
2. Selezionare database "fivem_gestionale"
3. Andare a "Import"
4. Selezionare file "database_migration_dettagli.sql"
5. Click "Import"
```

### Verifica
```sql
-- Controllare che i campi siano stati aggiunti
DESCRIBE weapons;
DESCRIBE reports;
DESCRIBE aziende;
DESCRIBE dipendenti;
```

---

## FASE 2: UPLOAD FILES AGGIORNATI

### Files da Aggiornare
```
✅ weapons.php
✅ reports.php
✅ federal.php
✅ internal.php
✅ aziende.php
```

**Ubicazione**: `C:\xampp\htdocs\gestionale_fivem_master\gestionale-fivem\public\`

---

## FASE 3: TESTING FUNZIONALITÀ

### Test 1: Login & Permessi FIB
```
1. Aprire: http://localhost/gestionale_fivem_master/gestionale-fivem/public/index.php
2. Login con: fib1 / fib1
3. Verificare che FIB veda:
   ✓ Dashboard
   ✓ Archivio Balistico (lettura)
   ✓ Federal Service (accesso completo)
   ✓ Censimento Attività (lettura)
```

### Test 2: Armi - Form Esteso
```
1. Login: admin1 / admin1
2. Andare a: Archivio Balistico
3. Compilare form con:
   - Intestatario: Mario Rossi
   - Matricola: WPN-2026-001
   - Modello: Glock 19
   - 🆕 Calibro: 9x19mm
   - 🆕 Tipo Munizione: FMJ
   - 🆕 Condizione: Buona
   - 🆕 Localizzazione: Armeria Nord
   - 🆕 Note: Test arma nuova
4. Click "REGISTRA"
5. Verificare che la card mostra TUTTI i dettagli quando cliccato
```

### Test 3: Rapporti Investigativi
```
1. Login: cid1 / cid1
2. Andare a: Report Investigativi
3. Compilare nuovo rapporto con:
   - Titolo: Indagine Furto Banco
   - 🆕 Tipo Indagine: Furto
   - 🆕 Priorità: Alta
   - 🆕 Vittime: 0
   - 🆕 Importo Stimato: 50000
   - 🆕 Luogo: Gioielleria Centro
   - 🆕 Agenti: Agent Johnson, Sarg. Lee
   - Descrizione: Test...
4. Verificare che la card mostra:
   - 🔴 Badge ALTA in rosso
   - Tutti i dati aggiuntivi
```

### Test 4: Fascicoli Federali
```
1. Login: fib1 / fib1
2. Andare a: Federal Service
3. Compilare nuovo fascicolo con:
   - Titolo: OPERAZIONE NORTH
   - 🆕 Tipo: Crimini Transfrontalieri
   - 🆕 Priorità: Critica
   - 🆕 Soggetti Coinvolti: 5
   - 🆕 Importo: 2000000
   - 🆕 Luogo: Border Zone
   - 🆕 Agenti Federali: Spec. Agent Torres
   - Descrizione: Test indagine federale
4. Verificare badge CRITICA in rosso scuro
```

### Test 5: Aziende
```
1. Login: procura1 / procura1
2. Andare a: Censimento Attività
3. Compilare nuova azienda:
   - Nome: Vanilla Unicorn
   - Titolare: Franklin Clinton
   - 🆕 Indirizzo: Downtown, Strada 42
   - 🆕 Telefono: 555-0123
   - 🆕 Email: info@vanilla.com
   - 🆕 Tipo Business: Intrattenimento
4. Aggiungere dipendente:
   - Nome: Michael Jones
   - Ruolo: Manager
   - 🆕 Telefono: 555-0456
   - 🆕 Email: mjones@vanilla.com
   - 🆕 Data Assunzione: 01/01/2025
5. Verificare card mostra indirizzo in header
6. Click per espandere e verificare info dettagliate
```

### Test 6: Responsive Design
```
1. Aprire strumenti sviluppatore (F12)
2. Simulare mobile (375px width)
3. Verificare che card restino leggibili
4. Form input restino accessibili
5. Tabelle si adattino dinamicamente
```

---

## FASE 4: CONTROLLO QUALITÀ

### Checklist QA
```
✅ Tutti i campi form accettano input correttamente
✅ Dati non-obbligatori restano NULL/vuoti se non compilati
✅ Card espandibili funzionano correttamente
✅ Colori badge priorità sono corretti
✅ Icone emoji si visualizzano correttamente
✅ Formato date è consistente (d/m/Y)
✅ Formattazione number currency ($X,XXX.XX)
✅ Ogni dispositivo (desktop/tablet/mobile) visualizza correttamente
✅ Tutte le role accedono solo ai propri moduli
✅ FIB e Marshall hanno permessi equivalenti
```

---

## FASE 5: TROUBLESHOOTING

### Errore: "ERRO DB: Unknown column..."
```
❌ Soluzione: Migrazione non eseguita correttamente
1. Verificare che database_migration_dettagli.sql sia stato importato
2. Controllare syntax errors nei log MySQL:
   SHOW ERRORS;
3. Ri-eseguire migration dopo correzioni
```

### Errore: "Field 'tipo_utente' doesn't have a default value"
```
❌ Soluzione: Vincolo NOT NULL su campo
1. Verificare migration ha aggiunto DEFAULT
2. Update weapons SET tipo_utente = 60 WHERE tipo_utente IS NULL;
```

### Card non si espandono
```
❌ Soluzione: JavaScript non caricato
1. Verificare console browser (F12 > Console)
2. Assicurarsi che toggleWeapon(), openReport(), toggleCase() siano definiti
3. Controllare syntax nei script tag HTML
```

### Colori badge non visibili
```
❌ Soluzione: CSS conflict
1. Svuotare cache browser (Ctrl+Shift+Del)
2. Verificare file style.css sia caricato (F12 > Network)
3. Controllare che colori HEX siano corretti
```

---

## FASE 6: DEPLOYMENT

### Checklist Pre-Deploy
```
✅ Backup database eseguito
✅ Migration completata senza errori
✅ Tutti i file PHP aggiornati
✅ Testing manuale completato
✅ QA checklist completato
✅ Nessun errore in console browser (F12)
```

### Post-Deploy
```
1. Monitorare logs MySQL/PHP per 24 ore
2. Testare con vari browser (Chrome, Firefox, Edge)
3. Raccogliere feedback dagli utenti
4. Documentare bug trovati per v2.2
5. Celebrare il successo! 🎉
```

---

## 📞 SUPPORTO

### Domande Frequenti

**D: I miei dati vecchi vengono mantenuti?**  
R: ✅ Sì! La migration aggiunge campi, non modifica quelli esistenti.

**D: Posso rollback se qualcosa va male?**  
R: ✅ Usa il backup creato in Fase 1:
```bash
mysql -u root -p fivem_gestionale < backup_pre_migration_28-03-2026.sql
```

**D: FIB può davvero fare tutto come Marshall?**  
R: ✅ Sì, i permessi sono integrati a tutti i livelli.

**D: I campi nuovi sono obbligatori?**  
R: ❌ No! Tutti i nuovi campi sono opzionali (NULL by default).

---

## 📈 STATISTICHE MODIFICHE

| Metrica | Valore |
|---------|--------|
| Files Modificati | 5 |
| Nuovo File SQL | 1 |
| Campi Database Aggiunti | 20+ |
| Form Input Aggiunti | 30+ |
| Dettagli Visualizzati | 50+ |
| Permessi Verificati | ✅ FIB=Marshall |
| Tempo Implementazione | 2-3 ore |
| Tempo Testing | 1-2 ore |

---

**Versione**: 2.1  
**Data**: 28/03/2026  
**Status**: 🟢 PRONTO  

Buona fortuna con l'implementazione! 💪
