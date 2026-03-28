# Gestionale-FiveM - Professional FiveM Management System

<div align="center">

![Gestionale-FiveM](https://img.shields.io/badge/FiveM-Management%20System-blue)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](LICENSE)
[![GitHub Stars](https://img.shields.io/github/stars/M4ttiz/GTA-FiveM-server-management?style=social)](../../stargazers)
[![Issues](https://img.shields.io/github/issues/M4ttiz/GTA-FiveM-server-management)](../../issues)

**A complete, professional, and scalable web management system for FiveM roleplay servers.**

[Features](#-features) • [Quick Start](#-quick-start) • [Documentation](#-documentation) • [Contributing](#-contributing)

</div>

---

## 📊 Overview

**Gestionale-FiveM** is a comprehensive web-based management system designed for FiveM servers. It provides administrators and staff with powerful tools to manage weapons (ballistics), investigate reports, handle internal affairs, manage companies, and oversee users with granular role-based access control.

**Status**: ✅ Production Ready  
**Version**: 1.0.0  
**Last Updated**: March 22, 2026

---

## ✨ Features

### 🔫 Ballistics Management
- Register and track weapon licenses with unique serials
- Instant revocation with complete audit trail
- Advanced search and filtering
- Export to CSV/PDF

### 📋 Investigation Reports
- Create structured investigative reports
- Workflow states: open → closed → archived
- Investigator assignment and tracking
- Historical timeline view
- Searchable archive

### ⚖️ Federal Service
- Dedicated federal case management
- Granular role-based access control
- Secure confidential information
- Real-time notifications

### 🛡️ Internal Affairs
- Confidential internal investigations
- Escalation protocols
- Immutable audit trail
- Admin-only visibility

### 🏢 Company Management
- Business registry and census
- Employee database
- Relational tracking
- Occupational statistics

### ⚙️ Admin Dashboard
- Real-time system overview
- User and role management
- Centralized audit logging
- KPI statistics
- System health monitoring

---

## 🛡️ Security Features

✅ **SQL Injection Prevention** - Prepared statements on all queries  
✅ **XSS Protection** - Input validation and output escaping  
✅ **CSRF Protection** - Tokens on all forms  
✅ **Password Security** - Argon2ID hashing  
✅ **Session Management** - Secure session handling with timeouts  
✅ **Rate Limiting** - Brute force attack protection  
✅ **Audit Logging** - Complete action tracking  
✅ **Data Encryption** - Sensitive field encryption support  

---

## 🚀 Quick Start

### Prerequisites
```bash
✓ XAMPP (or PHP 8.0+ & MySQL 5.7+)
✓ Git
✓ Modern web browser
```

### Installation (5 minutes)

```bash
# 1. Clone repository
git clone https://github.com/M4ttiz/GTA-FiveM-server-management.git
cd GTA-FiveM-server-management

# 2. Setup database
# Open PhpMyAdmin: http://localhost/phpmyadmin
# Create database: fivem_gestionale
# Import: gestionale-fivem/database.sql

# 3. Access application
# http://localhost/gestionale-fivem/public/index.php

# 4. Login with defaults
# Username: admin
# Password: Admin@1234
# ⚠️ Change password after first login!
```

For detailed setup, see [Quick Start Guide](docs/QUICKSTART.md)

---

## 📚 Documentation

| Topic | Link |
|-------|------|
| 📖 **Full Documentation Index** | [docs/README.md](docs/README.md) |
| 🚀 **Quick Start (5 min)** | [docs/QUICKSTART.md](docs/QUICKSTART.md) |
| 📖 **Setup Guide** | [docs/SETUP.md](docs/SETUP.md) |
| 👤 **User Guide** | [docs/USER_GUIDE.md](docs/USER_GUIDE.md) |
| ⚙️ **Configuration** | [docs/CONFIGURATION.md](docs/CONFIGURATION.md) |
| 🏗️ **Architecture** | [docs/ARCHITECTURE.md](docs/ARCHITECTURE.md) |
| 📡 **API Documentation** | [docs/API.md](docs/API.md) |
| 🗄️ **Database Schema** | [docs/DATABASE.md](docs/DATABASE.md) |
| 🧪 **Testing** | [docs/TESTING.md](docs/TESTING.md) |
| 🆘 **Troubleshooting** | [docs/TROUBLESHOOTING.md](docs/TROUBLESHOOTING.md) |
| 🔒 **Security Policy** | [SECURITY.md](SECURITY.md) |
| 🤝 **Contributing** | [CONTRIBUTING.md](CONTRIBUTING.md) |

---

## 🏗️ Project Structure

```
<repository-root>/
├── docs/                  # Documentazione (include docs/gestionale-fivem/ per note tecniche)
├── gestionale-fivem/
│   ├── public/            # Document root Apache
│   ├── resources/fivem/   # Script Lua per il server FiveM
│   ├── sql/               # Migrazioni SQL opzionali
│   ├── database.sql       # Schema principale
│   ├── package.json
│   └── server.js
├── README.md
└── ...
```

---

## 👥 User Roles

| Role | Description | Permissions |
|------|-------------|------------|
| **admin** | System administrator | All modules, user management, audit logs |
| **federal** | Federal agent | Federal service, reports, investigations |
| **pd** | Police department | Investigation reports, basic search |
| **armeria60** | Weapons manager | Ballistics system, weapon registration/revocation |
| **cid** | Civilian | Reports submission (read-only access) |
| **interno** | Internal affairs | Confidential internal investigations |

---

## 📊 Tech Stack

| Layer | Technology |
|-------|-----------|
| **Frontend** | HTML5, CSS3, Vanilla JavaScript |
| **Backend** | PHP 8.0+ |
| **Database** | MySQL/MariaDB 5.7+ |
| **Server** | Apache (XAMPP) |
| **Charset** | UTF-8MB4 |
| **Node.js** | v14.0.0+ (optional utilities) |

---

## 🤝 Contributing

Contributions are welcome! Please read our [Contributing Guidelines](CONTRIBUTING.md) and [Code of Conduct](CODE_OF_CONDUCT.md).

### Quick Start for Contributors

```bash
# 1. Fork and clone
git clone https://github.com/YOUR_USERNAME/GTA-FiveM-server-management.git

# 2. Create feature branch
git checkout -b feature/amazing-feature

# 3. Make changes and commit
git commit -m 'feat: add amazing feature'

# 4. Push and create Pull Request
git push origin feature/amazing-feature
```

See [CONTRIBUTING.md](CONTRIBUTING.md) for detailed guidelines.

---

## 🔒 Security

Found a security vulnerability? **Please do not open a public issue!**

Report privately via [SECURITY.md](SECURITY.md)

---

## 📝 License

This project is licensed under the [MIT License](LICENSE).

**Note**: FiveM has its own Terms of Service: [fivem.net/terms](https://fivem.net/terms)

---

## 🗺️ Roadmap

- **v1.1** - Audit logging system, advanced search
- **v1.2** - Two-factor authentication
- **v1.3** - REST API + Swagger documentation
- **v1.4** - Mobile companion app
- **v2.0** - Microservices architecture

See [CHANGELOG.md](CHANGELOG.md) for development progress.

---

## 📞 Support

| Type | Method |
|------|--------|
| 🐛 **Bugs** | [GitHub Issues](../../issues) |
| 💡 **Features** | [GitHub Discussions](../../discussions) |
| 🔒 **Security** | See [SECURITY.md](SECURITY.md) |
| ❓ **Questions** | [Q&A Discussions](../../discussions?discussions_q=category%3AQ%26A) |

---

## ❤️ Show Your Support

- ⭐ Star this repository
- 🐛 Report bugs and issues
- 💡 Suggest new features
- 🤝 Contribute code
- 📣 Share with your FiveM community

---

<div align="center">

**Built with ❤️ for the FiveM Community**

[⭐ Star us on GitHub](../../) • [🐛 Report Issues](../../issues) • [💬 Discuss Features](../../discussions)

</div>
