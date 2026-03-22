# 🔒 Politica di Sicurezza

## Segnalazione Vulnerabilità

Se scopri una vulnerabilità di sicurezza, **per favore non aprire un issue pubblico**.
Segnalala privatamente:

### 📧 Contatti per Security Issues
- **Email**: [pasqualimattia898@gmail.com]
- **PGP Key**: (disponibile su richiesta)

### ℹ️ Include nella segnalazione:
1. Descrizione della vulnerabilità
2. Versione affetta
3. Passi per riprodurre
4. Impatto potenziale
5. Suggerimenti di fix (se hai)

## Processo di Risposta

1. **Conferma**: Entro 48 ore
2. **Assessment**: Entro 1 settimana
3. **Fix**: Priorità basata su severity
4. **Release**: Patch release urgente
5. **Disclosure**: Coordinate after release

## Security Best Practices

### ✅ Implementate in Gestionale-FiveM

#### Input Validation
```php
// ✅ Prepared statements (NO SQL Injection)
$stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
```

#### Authentication
```php
// ✅ Password hashing
$hash = password_hash($password, PASSWORD_ARGON2ID);

// ✅ Session security
session_regenerate_id(true); // Dopo login
```

#### CSRF Protection
```php
// ✅ Token nelle form
<form method="POST">
    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
</form>
```

#### Dependencies
- Mantieni aggiornati: `npm audit`, `composer audit`
- Reviewa security advisories
- Pianifica upgrade major versions

### 🔐 Headers di Sicurezza
```php
// ✅ Dovrebbero essere implementati
header("X-Content-Type-Options: nosniff");
header("X-Frame-Options: DENY");
header("X-XSS-Protection: 1; mode=block");
header("Strict-Transport-Security: max-age=31536000; includeSubDomains");
```

## Compliance

### Standards Seguiti
- **OWASP Top 10**: Protezioni implementate
- **CWE**: Common Weakness Enumeration checkslist
- **FiveM TOS**: Completo

### Audit
- Code reviewed prima di merge
- Dependencies monitorate
- Log audit mantengono traccia azioni

## Versioni Supportate

| Versione | Status | Fine Supporto |
|----------|--------|--------------|
| 1.x | ✅ Active | 2027-03-22 |
| < 1.0 | ❌ Unsupported | N/A |

## Requisiti Sistema

### Minimali per Sicurezza
- **PHP**: 8.0 o superiore
- **MySQL**: 5.7+ (UTF-8MB4)
- **SSL/TLS**: Obbligatorio in produzione
- **HTTPS**: Configurato propertamente

### Hardening Checklist
```
[ ] SSL/TLS abilitato
[ ] Credenziali cambiate da default
[ ] Database in rete privata
[ ] Backups automatici abilitati
[ ] Log monitoring configurato
[ ] Firewall rules impostati
[ ] Rate limiting implementato
[ ] File permissions corrette (644, 755)
```

## Monitoraggio Continuo

1. **Dependencies**: `npm audit` settimanale
2. **Logs**: Monitora `/logs` per suspicious activity
3. **Database**: Verifica query lente
4. **Performance**: Resources monitoring
5. **Access**: Review auth logs

## Grazie

Apprezziamo chi ci aiuta a mantenere Gestionale-FiveM sicuro e affidabile.

---

*Last Updated: 2026-03-22*
