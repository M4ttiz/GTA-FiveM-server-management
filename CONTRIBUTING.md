# 🤝 Guide Contribuzione

Grazie per l'interesse nel contribuire a **Gestionale-FiveM**! Questo documento fornisce 
le linee guida per contribuire al progetto.

## Come Iniziare

### 1. Fork e Clone
```bash
git clone https://github.com/M4ttiz/GTA-FiveM-server-management.git
cd gestionale-fivem
```

### 2. Setup Locale
```bash
# Installa dipendenze
npm install

# Configura database (vedi docs/SETUP.md)
# Importa schema: database.sql
```

### 3. Crea un Branch
```bash
git checkout -b feature/tua-feature
# o
git checkout -b fix/tuo-fix
```

## Processo di Contribuzione

### Regole di Commit
- ✅ **Titolo**: Massimo 50 caratteri, chiaro e descrittivo
- ✅ **Corpo**: Spiega il "cosa" e il "perché" (non il "come")
- ✅ **Ticket**: Referenzia issue: `Fixes #123`

**Esempio:**
```
feat: aggiunta sistema log audit per tutte le azioni

- Registra tutte le operazioni degli admin
- Include timestamp, utente e tipo di azione
- Salva in tabella 'audit_logs' con indici
- Aggiunge endpoint GET /api/audit-logs

Fixes #45
```

### Prefissi Commit Standard
- `feat:` - Nuova feature
- `fix:` - Correzione bug
- `docs:` - Documentazione
- `style:` - Formattazione (non cambia logica)
- `refactor:` - Restructuring del codice
- `perf:` - Miglioramenti di performance
- `test:` - Test o test improvement
- `chore:` - Build, deps, tools

## Linee Guida Codice

### PHP
```php
// ✅ Giusto
function validateUserInput(string $input): bool {
    return !empty(trim($input)) && strlen($input) <= 255;
}

// ❌ Evitare
function validate($input) {
    return $input != "";
}
```

### JavaScript
```javascript
// ✅ Usa const/let, non var
const fetchUsers = async () => {
    const response = await fetch('/api/users');
    return response.json();
};

// ✅ Arrow functions
const handleClick = (e) => { /* ... */ };
```

## Testing

Prima di fare push, assicurati che:

```bash
# 1. PHP > 8.0
php --version

# 2. No SQL Injection vulnerabilities
# Usa prepared statements sempre

# 3. No exposed credentials
grep -r "password" .env || echo "✅ No hardcoded passwords"

# 4. No console.log in production code
grep -r "console.log" public/app.js
```

## Pull Request

### Descrizione PR
```markdown
## 📝 Descrizione
Brief descrizione dei cambiamenti

## 🎯 Tipo di Cambio
- [x] 🐛 Bug fix
- [ ] ✨ Nuova feature
- [ ] 📚 Documentazione
- [ ] ♻️ Refactoring

## ✅ Checklist
- [ ] Ho letto CONTRIBUTING.md
- [ ] I miei commit seguono le regole di stile
- [ ] Ho testato i cambiamenti localmente
- [ ] Ho aggiunto tests (se applicabile)
- [ ] Ho aggiornato la documentazione
- [ ] Ho verificato no breaking changes

## 🔗 Related Issues
Fixes #123
```

## Regole di Stile

### Database
- Nomi tabelle LOWERCASE: `users`, `audit_logs`
- Nomi colonne SNAKE_CASE: `user_id`, `created_at`
- Sempre: `CHARSET utf8mb4`, FK constraints, indici

### Sicurezza
- **NO** SQL injection: usa sempre prepared statements
- **NO** hardcoded passwords/secrets
- Valida SEMPRE input utente
- Usa password hashing (`password_hash()`)
- Token CSRF in ogni form

## Review Process

1. Un maintainer rivede il tuo PR
2. Possono richiedere cambiamenti
3. Una volta approvato, verrà merged
4. Grazie! 🎉

## Domande?

- 📖 Leggi la [Documentazione](docs/)
- 🐛 Apri un'[Issue](../../issues)
- 💬 Discussioni nella sezione [Discussioni](../../discussions)

---

**Grazie per contribuire a Gestionale-FiveM! Il vostro feedback rende il progetto migliore.**
