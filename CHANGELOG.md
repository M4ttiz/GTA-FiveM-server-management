# Changelog

Tutti i cambiamenti notevoli di questo progetto sono documentati in questo file.

Il formato è basato su [Keep a Changelog](https://keepachangelog.com/),
e il progetto segue [Semantic Versioning](https://semver.org/).

## [1.0.0] - 2026-03-22

### 🎉 Initial Release

#### ✨ Features
- **Sistema di Autenticazione**: Login sicuro con token CSRF e session management
- **Gestione Armi**: 
  - Registrazione e tracciamento licenze ballistiche
  - Revoca con log completo
  - Sistema seriali unici
- **Rapporti Investigativi**:
  - Creazione, lettura, aggiornamento rapporti
  - Stati: aperto, chiuso, in revisione
- **Federal Service**:
  - Gestione fascicoli federali
  - Accesso granulare per ruoli
- **Internal Affairs**:
  - Rapporti confidenziali
  - Audit trail completo
- **Gestione Aziende**:
  - Censimento attività
  - Gestione dipendenti
  - Relazioni tracciabili
- **Dashboard Admin**:
  - Panoramica sistema
  - Gestione utenti
  - Statistiche

#### 🔒 Security
- Prepared statements MySQL per prevenire SQL Injection
- Password hashing con PHP password functions
- CSRF token su tutte le form
- Session regenerate su login
- Input validation stricta

#### 💾 Database
- Schema MySQL normalizzato
- UTF-8MB4 charset (supporto emoji)
- Foreign key constraints
- Indici optimizzati

#### 📚 Documentation
- README completo con setup guide
- Code of Conduct
- Contributing guidelines
- Security policy
- Changelog

#### 🏗️ Project Structure
- Cartelle organizzate per domain
- Separazione concerns (public/, src/)
- Config centralizzato
- Database schema well-documented

### 🔄 Changed
- N/A (Initial Release)

### ❌ Removed
- N/A (Initial Release)

### 🐛 Fixed
- N/A (Initial Release)

---

## [1.1.0] - Coming Soon

### Plan
- [ ] Audit logging system
- [ ] Two-factor authentication
- [ ] API documentation (Swagger)
- [ ] Performance optimizations
- [ ] Mobile responsive improvements
- [ ] Multi-language support (IT/EN)

---

## How to Release

1. Update version in `package.json`
2. Update CHANGELOG.md
3. Create git tag: `git tag -a v1.0.0`
4. Push: `git push origin v1.0.0`
5. Create GitHub Release from tag
