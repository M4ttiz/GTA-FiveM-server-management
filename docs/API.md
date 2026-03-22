# 📡 API Documentation - Gestionale-FiveM

Documentazione completa degli endpoint API disponibili.

---

## 🔑 Authentication

Tutti gli endpoint (tranne `/login`) richiedono autenticazione tramite sessione PHP.

### Login

```http
POST /public/index.php
Content-Type: application/x-www-form-urlencoded

username=admin&password=Admin%401234
```

**Response:**
```json
{
  "success": true,
  "message": "Login successful",
  "user": {
    "id": 1,
    "username": "admin",
    "role": "admin"
  }
}
```

---

## 👥 Users API

### List Users
```http
GET /public/users.php
```

**Response:**
```json
{
  "success": true,
  "users": [
    {
      "id": 1,
      "username": "admin",
      "ruolo": "admin",
      "email": "admin@fivem.local",
      "created_at": "2026-03-22 10:00:00",
      "is_active": 1
    }
  ]
}
```

### Create User
```http
POST /public/users.php?action=create
Content-Type: application/x-www-form-urlencoded

username=newuser&password=NewPass%40123&ruolo=pd&email=user%40fivem.local
```

**Required Fields:**
- `username` (string, 3-50 chars)
- `password` (string, min 12 chars, must include uppercase, numbers, symbols)
- `ruolo` (string: admin|federal|pd|armeria60|cid|interno)

**Response:**
```json
{
  "success": true,
  "message": "User created successfully",
  "user_id": 5
}
```

### Update User
```http
POST /public/users.php?action=update&id=1
```

**Fields (optional):**
- `email`
- `ruolo`
- `is_active`

### Delete User (Soft Delete)
```http
POST /public/users.php?action=delete&id=1
```

---

## 🔫 Weapons API

### List Weapons
```http
GET /public/weapons.php
```

**Query Parameters:**
- `revocata`: 0|1 (filter by status)
- `search`: string (search by buyer name or serial)
- `page`: number (pagination)

**Response:**
```json
{
  "success": true,
  "weapons": [
    {
      "id": 1,
      "seriale": "ARM-001",
      "modello": "Combat Pistol",
      "nome_compratore": "John",
      "cognome_compratore": "Doe",
      "data_registrazione": "2026-03-15 14:30:00",
      "revocata": 0,
      "registrato_da": 2
    }
  ],
  "total": 42,
  "page": 1
}
```

### Register Weapon
```http
POST /public/weapons.php?action=create
Content-Type: application/x-www-form-urlencoded

seriale=ARM-043&modello=Combat%20Pistol&nome_compratore=John&cognome_compratore=Doe&csrf_token=abc123def456
```

**Required Fields:**
- `seriale` (unique)
- `modello`
- `nome_compratore`
- `cognome_compratore`
- `csrf_token`

**Optional Fields:**
- `tipo_arma` (pistola|fucile|shotgun|sniper)
- `note`

**Response:**
```json
{
  "success": true,
  "message": "Weapon registered successfully",
  "weapon_id": 43
}
```

### Revoke Weapon
```http
POST /public/weapons.php?action=revoke&id=43
Content-Type: application/x-www-form-urlencoded

motivo_revoca=Licenza%20scaduta&csrf_token=abc123def456
```

**Required Fields:**
- `id` (weapon id)
- `motivo_revoca` (reason)
- `csrf_token`

**Response:**
```json
{
  "success": true,
  "message": "Weapon revoked successfully",
  "revocation_date": "2026-03-22 15:45:00"
}
```

---

## 📋 Reports API

### List Reports
```http
GET /public/reports.php
```

**Query Parameters:**
- `stato`: aperto|chiuso|archiviato
- `tipo`: investigativo|federale|interno
- `priorita`: bassa|normale|alta|critica
- `assegnato_a`: user_id
- `search`: string (search in title/description)

**Response:**
```json
{
  "success": true,
  "reports": [
    {
      "id": 1,
      "titolo": "Investigation Report #001",
      "tipo": "investigativo",
      "stato": "aperto",
      "priorita": "alta",
      "creato_da": 2,
      "assegnato_a": 3,
      "data_creazione": "2026-03-20 10:00:00",
      "data_modifica": "2026-03-22 15:00:00"
    }
  ]
}
```

### Create Report
```http
POST /public/reports.php?action=create
Content-Type: application/x-www-form-urlencoded

titolo=New%20Investigation&descrizione=Details%20here&tipo=investigativo&priorita=alta&csrf_token=abc123def456
```

**Required Fields:**
- `titolo`
- `descrizione`
- `tipo` (investigativo|federale|interno)
- `csrf_token`

**Optional Fields:**
- `priorita` (default: normale)
- `assegnato_a` (user_id)

**Response:**
```json
{
  "success": true,
  "message": "Report created successfully",
  "report_id": 12
}
```

### Update Report
```http
POST /public/reports.php?action=update&id=12
Content-Type: application/x-www-form-urlencoded

stato=chiuso&note_private=Investigation%20complete&csrf_token=abc123def456
```

**Fields (optional):**
- `titolo`
- `descrizione`
- `stato`
- `priorita`
- `assegnato_a`
- `note_private` (admin only)

### Get Report Detail
```http
GET /public/reports.php?id=12
```

---

## 🏢 Companies API

### List Companies
```http
GET /public/aziende.php
```

**Response:**
```json
{
  "success": true,
  "aziende": [
    {
      "id": 1,
      "nome": "Cluckin Bell Burgershot",
      "proprietario": "Boss Name",
      "tipo_attivita": "Ristorante",
      "email": "boss@cluckinbell.fivem",
      "licenza_numero": "LIC-001",
      "data_apertura": "2025-01-10",
      "attiva": 1,
      "dipendenti_count": 5
    }
  ]
}
```

### Create Company
```http
POST /public/aziende.php?action=create
```

**Fields:**
- `nome` (required, unique)
- `proprietario`
- `tipo_attivita`
- `email`
- `licenza_numero`

### Get Employees
```http
GET /public/aziende.php?id=1&dipendenti=1
```

---

## 🔒 Federal Service API

### List Federal Cases
```http
GET /public/federal.php
```

**Permissions:** Only federal users and admin

**Response:**
```json
{
  "success": true,
  "cases": [
    {
      "id": 1,
      "titolo": "Federal Investigation #001",
      "stato": "aperto",
      "priorita": "critica",
      "creato_da": 3,
      "assegnato_a": 3,
      "data_creazione": "2026-03-15 09:00:00"
    }
  ]
}
```

---

## 🛡️ Internal Affairs API

### List Internal Reports
```http
GET /public/internal.php
```

**Permissions:** Only internal affairs users and admin

**Response:**
```json
{
  "success": true,
  "reports": [...]
}
```

---

## ⚡ Error Responses

### Bad Request
```json
{
  "success": false,
  "error": "VALIDATION_ERROR",
  "message": "Username must be between 3 and 50 characters",
  "field": "username"
}
```

### Unauthorized
```json
{
  "success": false,
  "error": "UNAUTHORIZED",
  "message": "You do not have permission to access this resource"
}
```

### Not Found
```json
{
  "success": false,
  "error": "NOT_FOUND",
  "message": "Resource not found"
}
```

### Server Error
```json
{
  "success": false,
  "error": "DATABASE_ERROR",
  "message": "An internal server error occurred",
  "request_id": "ABC123DEF456"
}
```

---

## 🔐 Security Notes

### CSRF Protection
Tutti gli endpoint POST richiedono un valido CSRF token:
```html
<input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
```

### Rate Limiting
- Max 100 requests per minute per IP
- Max 10 login attempts per 5 minutes

### Sensitive Data
- Password mai ritornato nelle response
- `note_private` visibile solo a admin
- Federal/Internal reports hanno access control

---

## 📚 Code Examples

### JavaScript Fetch
```javascript
fetch('/public/weapons.php', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/x-www-form-urlencoded',
    },
    body: new URLSearchParams({
        'action': 'create',
        'seriale': 'ARM-100',
        'modello': 'Combat Pistol',
        'nome_compratore': 'John',
        'cognome_compratore': 'Doe',
        'csrf_token': document.querySelector('[name="csrf_token"]').value
    })
})
.then(r => r.json())
.then(data => {
    if (data.success) {
        console.log('Weapon created:', data.weapon_id);
    } else {
        alert('Error: ' + data.message);
    }
});
```

### PHP cURL
```php
$ch = curl_init();
curl_setopt_array($ch, [
    CURLOPT_URL => 'http://localhost/gestionale-fivem/public/weapons.php?action=create',
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => http_build_query([
        'seriale' => 'ARM-100',
        'modello' => 'Combat Pistol',
        'nome_compratore' => 'John',
        'cognome_compratore' => 'Doe',
        'csrf_token' => $_SESSION['csrf_token']
    ]),
    CURLOPT_RETURNTRANSFER => true
]);

$response = curl_exec($ch);
$data = json_decode($response, true);
```

---

**Next**: Leggi [USER_GUIDE.md](USER_GUIDE.md) per guide di utilizzo.
