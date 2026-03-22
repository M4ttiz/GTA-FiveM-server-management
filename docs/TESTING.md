# 🧪 Testing Guide - Gestionale-FiveM

Guida per testare l'applicazione.

---

## ✅ Manual Testing Checklist

### 1. Authentication Tests

- [ ] Login con credenziali valide funziona
- [ ] Login con password sbagliata fallisce
- [ ] Login con username inesistente fallisce
- [ ] Session timeout disconnette utente dopo 1 ora
- [ ] Logout distrugge la sessione
- [ ] CSRF token su login form è presente
- [ ] Password hash storage (non plain text)

### 2. User Management Tests

- [ ] Admin può creare nuovo utente
- [ ] Nuovo utente può loggare con credenziali create
- [ ] Cambio password funziona
- [ ] Admin può disattivare utente
- [ ] Utente disattivato non può loggare
- [ ] Password reset reimosta password per utente

### 3. Weapons Management Tests

- [ ] Registrazione arma con seriale unico
- [ ] Seriale duplicato viene rifiutato
- [ ] Filtro armi attive vs revocate funziona
- [ ] Ricerca per nome compratore funziona
- [ ] Revoca arma aggiorna status
- [ ] Log audit registra revoca
- [ ] Export CSV contiene dati corretti

### 4. Reports Tests

- [ ] Creazione rapporto investigativo
- [ ] Creazione rapporto federale (solo federal users)
- [ ] Creazione rapporto interno (solo admin/internal)
- [ ] Status workflow: aperto → chiuso → archiviato
- [ ] Assegnazione rapporto a utente
- [ ] Ricerca rapporti per tipo, status, priority
- [ ] Priorità alta/bassa mostrata correttamente
- [ ] Private notes visibili solo a admin

### 5. Companies Tests

- [ ] Registrazione azienda con nome unico
- [ ] Nome duplicato viene rifiutato
- [ ] Aggiunta dipendenti alla azienda
- [ ] Rimozione dipendente
- [ ] Ricerca aziende per tipo attività
- [ ] Export company list con dipendenti

### 6. Permission Tests

- [ ] Admin accede a tutti moduli
- [ ] Federal user non accede weapons
- [ ] PD user non accede federal service
- [ ] External user non accede internal affairs
- [ ] Armeria user accede solo weapons
- [ ] CID user accede solo reports

### 7. Security Tests

- [ ] SQL Injection attempt fail (test: `' OR '1'='1`)
- [ ] XSS attempt in form fail (test: `<script>alert(1)</script>`)
- [ ] CSRF token validation works
- [ ] Direct URL access senza login reindirizza
- [ ] Session cookie ha secure flag
- [ ] Password never returned in API responses
- [ ] Rate limiting blocks brute force (10+ login failures)

### 8. Database Tests

- [ ] Foreign key constraints enforced
- [ ] Cascading delete per dipendenti quando azienda deleted
- [ ] Database connect fallover handling
- [ ] Query timeout handling (very slow query)
- [ ] Special characters (emoji, accenti) handled correctly

### 9. UI/UX Tests

- [ ] All forms validate input client-side
- [ ] Error messages sono chiari
- [ ] Success messages mostra feedback
- [ ] Responsive design works on mobile
- [ ] Navigation menu accessible
- [ ] Search results paginati
- [ ] Sorting works (date, name, etc)

### 10. Performance Tests

- [ ] Page load < 2 seconds
- [ ] Database query < 100ms
- [ ] 1000+ weapons list query non crashes
- [ ] Bulk operations (50+ reports) works
- [ ] Memory usage stays under 256MB

---

## 🧪 Automated Testing (Future)

### Unit Tests Structure

```bash
tests/
├── Unit/
│   ├── ValidateUserTest.php
│   ├── PasswordHashTest.php
│   └── PermissionTest.php
├── Integration/
│   ├── LoginFlowTest.php
│   ├── WeaponRegistrationTest.php
│   └── ReportCRUDTest.php
└── Security/
    ├── SQLInjectionTest.php
    ├── XSSTest.php
    └── CSRFTest.php
```

### Run Tests

```bash
# Installa PHPUnit
composer require --dev phpunit/phpunit

# Run all tests
./vendor/bin/phpunit tests/

# Run specific test
./vendor/bin/phpunit tests/Unit/PasswordHashTest.php

# Run with coverage
./vendor/bin/phpunit --coverage-html coverage/
```

---

## 🔍 Test Scenarios

### Scenario 1: New User Registration
```
1. Admin navigates to Users → Add New
2. Fill form:
   - username: testuser
   - password: TestPass@1234
   - role: pd
3. Click Create
4. New user can login with credentials
5. Dashboard loads correctly for new user
```

### Scenario 2: Weapon Registration & Revocation
```
1. Armeria user registers weapon:
   - serial: TEST-001
   - model: Combat Pistol
   - buyer: John Doe
2. Weapon appears in list
3. Search finds weapon by serial
4. Revocation submitted with reason
5. Status changes to revoked
6. Audit log shows action
```

### Scenario 3: Security Breach Attempt
```
1. Attacker tries: username' OR '1'='1'--
2. Login fails
3. Account doesn't exist error
4. No database error exposed
5. Attempt logged in security log
```

### Scenario 4: Permission Denial
```
1. External user requests /federal.php directly
2. Redirect to dashboard
3. Error message: "Unauthorized"
4. Action logged as unauthorized attempt
```

---

## 📊 Test Data

### Default Test Users

```
admin/Admin@1234        → admin (full access)
federal1/Federal@1234   → federal (federal + reports)
police1/Police@1234     → pd (reports only)
armeria1/Armeria@1234   → armeria60 (weapons only)
cid1/Cid@1234          → cid (reports only)
interno1/Interno@1234   → interno (internal affairs)
```

### Sample Weapons

```sql
INSERT INTO weapons (seriale, modello, nome_compratore, cognome_compratore, tipo_utente) 
VALUES 
('TEST-001', 'Combat Pistol', 'John', 'Doe', 10),
('TEST-002', 'Mini Uzi', 'Jane', 'Smith', 10),
('TEST-003', 'Assault Rifle', 'Mike', 'Johnson', 11);
```

### Sample Reports

```sql
INSERT INTO reports (titolo, descrizione, tipo, stato, creato_da) 
VALUES 
('Investigation #1', 'Details here...', 'investigativo', 'aperto', 2),
('Federal Case', 'Fed details...', 'federale', 'aperto', 3),
('Internal Matter', 'Internal details...', 'interno', 'aperto', 1);
```

---

## 🐛 Bug Report Template

Use questo template quando reporti bugs:

```
## Bug Title
[Chiaro titolo del problema]

## Description
[Descrizione dettagliata]

## Steps to Reproduce
1. [Passo 1]
2. [Passo 2]
3. [Ecc]

## Expected Behavior
[Cosa dovrebbe succedere]

## Actual Behavior
[Cosa succede invece]

## Screenshots
[Aggiungi screenshot se relevante]

## Environment
- PHP Version: 8.0
- MySQL Version: 5.7
- Browser: Chrome 100
- OS: Windows 11

## Severity
[ ] Critical (system down)
[ ] High (major feature broken)
[ ] Medium (feature partially broken)
[ ] Low (minor issue)
```

---

## ✅ Pre-Release Checklist

Prima di pubblicare una nuova versione:

- [ ] Tutti i test passano
- [ ] No console errors/warnings
- [ ] Database queries optimized
- [ ] Security review completato
- [ ] Documentation aggiornato
- [ ] CHANGELOG aggiornato
- [ ] Version number bumped
- [ ] No hardcoded credentials reste
- [ ] SSL/TLS configurato
- [ ] Backup system tested

---

**Aggiungi nuovi test quando scopri bugs!**
Questo aiuta a prevenire regressioni in futuro.
