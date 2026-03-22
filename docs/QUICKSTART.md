# 🚀 Quick Start - Gestionale-FiveM

Questo guide vi guiderà per iniziare in **meno di 5 minuti**.

## ✅ Checklist Pre-Installazione

- [ ] XAMPP installato (PHP 8.0+, MySQL)
- [ ] Git installato
- [ ] Browser moderno disponibile
- [ ] Porte 80 (Apache) e 3306 (MySQL) libere

## 📥 Passaggio 1: Clone Repository (1 min)

```bash
cd C:\xampp\htdocs
git clone https://github.com/M4ttiz/GTA-FiveM-server-management.git
cd gestionale-fivem
```

## 🗄️ Passaggio 2: Setup Database (2 min)

### Opzione A: PhpMyAdmin (GUI)
```
1. Apri: http://localhost/phpmyadmin
2. Click "Nuovo" → Crea database "fivem_gestionale"
3. Seleziona DB → Click "Importa"
4. Scegli file: gestionale-fivem/database.sql
5. Click "Vai" → Completo ✅
```

### Opzione B: MySQL CLI
```bash
cd gestionale-fivem
mysql -u root -p < database.sql

# Se accetta (senza password su XAMPP):
mysql -u root < database.sql
```

### ✓ Verifica Installazione
```sql
mysql> USE fivem_gestionale;
mysql> SHOW TABLES;
-- Dovresti vedere: users, weapons, reports, aziende, dipendenti
```

## 🌐 Passaggio 3: Avvia il Server (1 min)

1. **Avvia XAMPP Control Panel**
   - Start Apache
   - Start MySQL

2. **Accedi all'applicazione**
   ```
   http://localhost/gestionale-fivem
   ```

## 🔑 Passaggio 4: Login Iniziale (30 sec)

**Credenziali Default:**
```
Username: admin
Password: Admin@1234
```

⚠️ **IMPORTANTE**: Cambia password dopo primo login!
- Vai in: Admin → Profilo → Modifica Password

## ✨ Pronto!

Congratulazioni! 🎉 Gestionale-FiveM è online.

### Prossimi Step:
1. 🔐 **Sicurezza**: Leggi [SECURITY.md](../SECURITY.md)
2. 🔧 **Configurazione**: Vedi [CONFIGURATION.md](CONFIGURATION.md)
3. 👥 **Utenti**: Crea ruoli e utenti aggiuntivi
4. 📚 **Documentazione**: Leggi [Full Docs](README.md)

---

## 🆘 Troubleshooting

### ❌ "Can't connect to MySQL"
```
Soluzione: Verifica che MySQL sia avviato in XAMPP
Check: http://localhost/phpmyadmin deve essere accessibile
```

### ❌ "Page not found"
```
Soluzione: Assicurati file siano in C:\xampp\htdocs\gestionale-fivem
Verifica: http://localhost/gestionale-fivem/public/index.php
```

### ❌ "Login failed"
```
Soluzione: Reimporta database.sql
Check: Table 'users' contiene admin record
```

### ❌ "PHP Version Error"
```
Soluzione: XAMPP deve avere PHP 8.0+
Check: http://localhost/index.php → Verifica versione PHP
```

---

**Hai problemi?** Vedi [TROUBLESHOOTING.md](TROUBLESHOOTING.md) per soluzioni dettagliate.
