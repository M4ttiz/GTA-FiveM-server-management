# 📚 Documentazione Gestionale-FiveM

Benvenuto nella documentazione completa di **Gestionale-FiveM**!

## 🎯 Scegli il Tuo Percorso

### 👤 Sono un **Utente/Amministratore**
Vuoi usare il sistema per gestire il tuo server FiveM.

**Start here:**
1. [🚀 Quick Start](QUICKSTART.md) - Setup in 5 minuti
2. [⚙️ Configuration](CONFIGURATION.md) - Personalizza il sistema
3. [📖 User Guide](USER_GUIDE.md) - Come usare ogni modulo
4. [🆘 Troubleshooting](TROUBLESHOOTING.md) - Risolvi problemi

### 👨‍💻 Sono un **Developer**
Vuoi contribuire code o estendere il sistema.

**Start here:**
1. [🏗️ Architecture](ARCHITECTURE.md) - Come è strutturato
2. [📡 API Documentation](API.md) - Endpoints e metodi
3. [🗄️ Database Schema](DATABASE.md) - Struttura dati
4. [🧪 Testing Guide](TESTING.md) - Come testare
5. [CONTRIBUTING.md](#) - Come contribuire

### 🏃 Ho **Poco Tempo** (5-10 min)
Solo le informazioni essenziali.

**Fast Track:**
- [🚀 Quick Start](QUICKSTART.md) - Installa e accedi
- [🔑 Default Credentials](#default-credentials) - Info login
- [📝 File Structure](#file-structure) - Dove trovare cose

---

## 📄 File Structure

```
docs/
├── README.md              👈 Sei qui
├── QUICKSTART.md          File principale per setup
├── CONFIGURATION.md       Variabili e settings
├── USER_GUIDE.md          Come usare il sistema
├── TROUBLESHOOTING.md     Risolvi problemi
├── ARCHITECTURE.md        Overview tecnico
├── DATABASE.md            Schema e relazioni
├── API.md                 Endpoints HTTP
├── TESTING.md             Test e QA
└── images/                Screenshots per docs
```

---

## 🔑 Default Credentials

**First User (Admin):**
```
Username: admin
Password: Admin@1234
```

⚠️ **MUST CHANGE** after first login!

**Test Users** (for development):
```
arma60:arma60   → Role: armeria60
cid1:cid1       → Role: cid
```

---

## 📖 Quick Reference

### Sistema File Principale

| File | Scopo |
|------|-------|
| `index.php` | Pagina login |
| `dashboard.php` | Admin dashboard |
| `users.php` | Gestione utenti |
| `weapons.php` | Sistema armi |
| `reports.php` | Rapporti |
| `aziende.php` | Aziende |
| `federal.php` | Federal service |
| `internal.php` | Internal affairs |
| `db.php` | Database class |
| `app.js` | Frontend logic |
| `style.css` | Styling |

### Tabelle Database Principale

| Tabella | Uso |
|---------|-----|
| `users` | Credenziali e ruoli |
| `weapons` | Registri armi |
| `reports` | Rapporti investigativi |
| `aziende` | Dati aziendali |
| `dipendenti` | Dati dipendenti |

---

## 🔐 Security Basics

- ✅ **Sempre**: Use HTTPS in production
- ✅ **Sempre**: Cambia default credentials
- ✅ **Sempre**: Mantieni PHP aggiornato
- ✅ **Sempre**: Backup database regolarmente
- ❌ **NON**: Committa .env con credentials
- ❌ **NON**: Shareae database credentials

Vedi [SECURITY.md](../SECURITY.md) per policy completa.

---

## 🤔 Domande Frequenti

### P: Come aggiungere un nuovo utente?
**R**: Dashboard → Users → Add New User

### P: Come cambio password?
**R**: Profilo (top-right) → Change Password

### P: Come backup il database?
**R**: Vedi [CONFIGURATION.md](CONFIGURATION.md#backup)

### P: Come implemento una nuova feature?
**R**: Leggi [CONTRIBUTING.md](../CONTRIBUTING.md)

### P: Quale versione PHP ho?
**R**: Dashboard → System Info (mostra versioni)

---

## 📞 Getting Help

| Problema | Soluzione |
|----------|-----------|
| Setup issues | [QUICKSTART.md](QUICKSTART.md) |
| How-to questions | [USER_GUIDE.md](USER_GUIDE.md) |
| Configuration | [CONFIGURATION.md](CONFIGURATION.md) |
| Technical details | [ARCHITECTURE.md](ARCHITECTURE.md) |
| Something's broken | [TROUBLESHOOTING.md](TROUBLESHOOTING.md) |
| Contributing | [CONTRIBUTING.md](../CONTRIBUTING.md) |

---

## 💡 Tips & Tricks

### Productivity Tips
- Use **CTRL+F** searchbar per cercare rapporti velocemente
- **Filter panel** su weapons/reports per restricton dati
- **Export CSV** per analisi in Excel
- **Bulk actions** per operazioni multiple

### Admin Tips
- Review **System Log** quotidianamente
- Setup **Database Backups** giornaliari
- Monitor **User Activity** per security
- Mantieni **Audit Trail** per compliance

---

## 📈 Best Practices

### For System Administrators
1. Backup database giornalmente
2. Review access logs settimanalmente
3. Update credentials regolarmente
4. Monitor disk space
5. Pianifica maintenance

### For Users
1. Non shareae password
2. Logout quando finisci
3. Report suspicious activity
4. Manage your profile info
5. Ask admin se avai dubbi

---

## 🔄 Version Info

**Current Version**: 1.0.0  
**Release Date**: 2026-03-22  
**PHP Requirement**: 8.0+  
**MySQL Requirement**: 5.7+  

Vedi [CHANGELOG.md](../CHANGELOG.md) per storici delle versioni.

---

<div align="center">

**Need More Help?**

📖 [Full Docs](README.md) • 🐛 [Report Issue](../../issues) • 💬 [Discussions](../../discussions)

</div>
