# Gestionale-FiveM 🚔
**FiveM Server Management System - Community Edition**

[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](LICENSE)
[![GitHub Stars](https://img.shields.io/github/stars/M4ttiz/GTA-FiveM-server-management?style=flat)](../../stargazers)
[![Contributions Welcome](https://img.shields.io/badge/Contributions-Welcome-brightgreen.svg)](CONTRIBUTING.md)
[![Code of Conduct](https://img.shields.io/badge/Code%20of%20Conduct-Respected-blue)](CODE_OF_CONDUCT.md)

> Un **sistema completo e professionale** di gestione web per server FiveM roleplay in GTA V. Progettato per amministratori server che necessitano di strumenti potenti, sicuri e scalabili.

![Gestionale-FiveM Dashboard](docs/images/dashboard.png)

---

## 📋 Sommario Veloce

| Sezione | Link |
|---------|------|
| 🚀 Quick Start | [Setup in 5 minuti](docs/QUICKSTART.md) |
| 📖 Full Documentation | [Docs completa](docs/README.md) |
| 🔒 Sicurezza | [Security Policy](SECURITY.md) |
| 🤝 Contributori | [CONTRIBUTING.md](CONTRIBUTING.md) |
| 📝 Changelog | [CHANGELOG.md](CHANGELOG.md) |

---

## ✨ Caratteristiche Principali

### 🔫 Gestione Armi (Ballistics)
- ✅ Registrazione licenze con seriali unici
- ✅ Tracciamento storico completo
- ✅ Revoca istantanea con log audit
- ✅ Filtri avanzati e ricerca
- ✅ Export dati in CSV/PDF

### 📋 Rapporti Investigativi
- ✅ Creazione rapporti strutturati
- ✅ Stati workflow: aperto → chiuso/archiviato
- ✅ Assegnazione investigatori
- ✅ Archivio searchable
- ✅ Timeline investigations

### ⚖️ Federal Service
- ✅ Fascicoli federali dedicati
- ✅ Accesso granulare per ruoli
- ✅ Confidenzialità garantita
- ✅ Notifiche real-time

### 🛡️ Internal Affairs
- ✅ Rapporti confidenziali
- ✅ Investigazioni interne
- ✅ Escalation protocol
- ✅ Audit trail immutabile

### 🏢 Gestione Aziende
- ✅ Censimento attività
- ✅ Database dipendenti
- ✅ Relazioni tracciabili
- ✅ Statistiche occupazionali

### ⚙️ Admin Dashboard
- ✅ Overview sistema real-time
- ✅ Gestione utenti e ruoli
- ✅ Log audit centralizzato
- ✅ Statistiche KPI
- ✅ System health monitoring

---

## 🛡️ Sicurezza Implementata

✅ **SQL Injection Prevention**: Prepared statements su tutte le query  
✅ **XSS Protection**: Input validation e output escaping  
✅ **CSRF Protection**: Token su tutte le form  
✅ **Password Security**: Argon2ID hashing  
✅ **Session Management**: Regenerate su login, timeout configurabile  
✅ **Rate Limiting**: Protection against brute force  
✅ **Audit Logging**: Traccia completa di tutte le azioni admin  
✅ **Data Encryption**: Support per fields sensibili

> 📖 Leggi la [Security Policy](SECURITY.md) per dettagli completi.

---

## 📊 Tech Stack

| Layer | Technology |
|-------|-----------|
| **Frontend** | HTML5 • CSS3 • Vanilla JavaScript |
| **Backend** | PHP 8.0+ |
| **Database** | MySQL/MariaDB 5.7+ |
| **Server** | Apache (in XAMPP) |
| **Charset** | UTF-8MB4 (emoji support) |
| **Node.js** | ^14.0.0 (optional utilities) |

---

## 🚀 Quick Start

### ✅ Prerequisiti
```bash
✓ XAMPP instalato (o PHP 8.0+ + MySQL)
✓ Git
✓ Browser moderno (Chrome, Firefox, Edge)
```

### 📥 Installazione (5 minuti)

#### 1. Clone Repository
```bash
git clone https://github.com/M4ttiz/GTA-FiveM-server-management.git
cd gestionale-fivem
```

#### 2. Setup Database
```bash
# Apri PhpMyAdmin
# http://localhost/phpmyadmin

# Importa schema
1. Create DB: "fivem_gestionale"
2. Esegui: gestione-fivem/database.sql
3. Verifica tabelle create
```

#### 3. Configura Server PHP
```bash
# In XAMPP, copia cartella
cp -r gestionale-fivem C:\xampp\htdocs\gestionale-fivem

# Accedi a: http://localhost/gestionale-fivem
```

#### 4. Login Iniziale
```
Username: admin
Password: admin

⚠️ CAMBIA PASSWORD DOPO PRIMO LOGIN!
```

### 🎯 Prossimi Step
- 📖 Leggi [Complete Setup Guide](docs/SETUP.md)
- 🔧 Configura [Environment Variables](docs/CONFIGURATION.md)
- 👥 Crea utenti aggiuntivi
- 🔐 Configura HTTPS in produzione

---

## 📚 Documentazione Completa

### Per Utenti
- 📖 [Setup & Installation](docs/SETUP.md)
- ⚙️ [Configuration Guide](docs/CONFIGURATION.md)
- 📊 [User Guide](docs/USER_GUIDE.md)
- 🎯 [Troubleshooting](docs/TROUBLESHOOTING.md)

### Per Developer
- 🏗️ [Architecture](docs/ARCHITECTURE.md)
- 📡 [API Documentation](docs/API.md)
- 🧪 [Testing Guide](docs/TESTING.md)
- 🔄 [Database Schema](docs/DATABASE.md)

### Per Community
- 🤝 [Contributing](CONTRIBUTING.md)
- 📋 [Code of Conduct](CODE_OF_CONDUCT.md)
- 🔒 [Security Policy](SECURITY.md)

---

## 🏗️ Struttura Progetto

```
gestionale-fivem/
├── public/                          # Files serviti al client
│   ├── index.php                   # Entry point
│   ├── dashboard.php               # Admin dashboard
│   ├── users.php                   # User management
│   ├── weapons.php                 # Ballistics system
│   ├── reports.php                 # Investigations
│   ├── aziende.php                 # Companies
│   ├── federal.php                 # Federal service
│   ├── internal.php                # Internal affairs
│   ├── logout.php                  # Logout handler
│   ├── app.js                      # Frontend logic
│   ├── style.css                   # Styling
│   └── db.php                      # Database class
├── database.sql                     # Schema database
├── server.js                        # Node.js entry (optional)
├── package.json                     # Dependencies
└── README.md                        # This file
```

---

## 👥 Ruoli & Permessi

### System Roles
| Ruolo | Armi | Reports | Federal | Internal | Admin |
|-------|------|---------|---------|----------|-------|
| `admin` | ✅ | ✅ | ✅ | ✅ | ✅ |
| `federal` | - | ✅ | ✅ | - | - |
| `pd` | - | ✅ | - | - | - |
| `armeria60` | ✅ | - | - | - | - |
| `cid` | - | ✅ | - | - | - |

### Permission Model
- **RBAC** (Role-Based Access Control) granulare
- **Field-level security** per dati sensibili
- **Action audit** di tutte le operazioni
- **Escalation workflows** per decisioni critiche

---

## 🤝 Contributing

Contribuzioni sono benvenute! Segui questi step:

1. **Fork** il repository
2. **Create** branch: `git checkout -b feature/amazing-feature`
3. **Commit**: `git commit -m 'feat: add amazing feature'`
4. **Push**: `git push origin feature/amazing-feature`
5. **Open** Pull Request

Leggi [CONTRIBUTING.md](CONTRIBUTING.md) per regole dettagliate.

---

## 📝 Licenza

Questo progetto è licensiato sotto [MIT License](LICENSE).

**Importante**: FiveM ha le proprie Terms of Service:
[fivem.net/terms](https://fivem.net/terms)

---

## ❤️ Crediti & Comunità

### Creato per
- **FiveM Developers** che necessitano admin tools professionali
- **Roleplay Communities** che vogliono gestare il server correttamente

### Supporta il Progetto
- ⭐ Dai una stella  
- 🐛 Report bugs
- 💡 Suggerisci features
- 🤝 Contribuisci codice
- 📣 Condividi con altri server

---

## 📞 Support & Contact

| Tipo | Come |
|------|------|
| 🐛 **Bugs** | [Issues](../../issues) |
| 💡 **Features** | [Discussions](../../discussions) |
| 🔒 **Security** | Email privata vedi [SECURITY.md](SECURITY.md) |
| ❓ **Questions** | [Q&A Discussions](../../discussions?discussions_q=category%3AQ%26A) |

---

## 🗺️ Roadmap

Vedi [CHANGELOG.md](CHANGELOG.md) per piano di sviluppo:

- **v1.1**: Audit logging system (+advanced search)
- **v1.2**: Two-factor auth
- **v1.3**: API REST completa + Swagger
- **v1.4**: Mobile app companion
- **v2.0**: Microservices architecture

---

## 📜 Disclaimer

> **Gestionale-FiveM** è un progetto open-source community. Non è affiliato ufficialmente 
> con [Rockstar Games](https://www.rockstargames.com/) o [FiveM](https://fivem.net/).
> 
> Usalo responsabilmente e in conformità con le policy del tuo server FiveM.

---

<div align="center">

**Fatto con ❤️ per la FiveM Community**

[⭐ Star us on GitHub](../../) • [🐛 Report Issues](../../issues) • [💬 Discuss](../../discussions)

</div>
