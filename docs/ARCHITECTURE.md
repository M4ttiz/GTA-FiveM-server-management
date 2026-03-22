# 🏗️ Architecture - Gestionale-FiveM

Overview architetturale dell'applicazione.

---

## 🎯 Design Patterns & Principles

### SOLID Principles
- **S**ingle Responsibility: Ogni file ha un compito
- **O**pen/Closed: Facilità estensione senza modifiche
- **L**iskov Substitution: Eredità corretta
- **I**nterface Segregation: Interfacce granulari
- **D**ependency Inversion: Dipendenze dal basso in alto

### Architecture Layers

```
┌─────────────────────────────────────┐
│        Presentation Layer           │
│   (HTML/CSS/JS - Frontend)          │
├─────────────────────────────────────┤
│        Application Layer            │
│   (PHP Controllers/Logic)           │
├─────────────────────────────────────┤
│        Data Access Layer            │
│   (Database Queries)                │
├─────────────────────────────────────┤
│        Database Layer               │
│   (MySQL/MariaDB)                   │
└─────────────────────────────────────┘
```

---

## 📂 Directory Structure

```
gestionale-fivem/
│
├── public/                          # Web root (served to client)
│   ├── index.php                   # Login entry point
│   ├── dashboard.php               # Admin dashboard
│   │
│   ├── [Feature Files]
│   ├── users.php                   # User management
│   ├── weapons.php                 # Ballistics system
│   ├── reports.php                 # General reports
│   ├── aziende.php                 # Companies registry
│   ├── federal.php                 # Federal service
│   ├── internal.php                # Internal affairs
│   ├── logout.php                  # Logout handler
│   │
│   ├── [Assets] 
│   ├── style.css                   # Main stylesheet
│   ├── app.js                      # Frontend JavaScript
│   └── db.php                      # Database class
│
├── database.sql                     # Initial schema
├── server.js                        # Node.js (optional)
├── package.json                     # Dependencies
├── README.md                        # Main documentation
│
├── [Root Config]
├── .gitignore                       # Git exclude rules
├── .editorconfig                    # Editor settings
├── LICENSE                          # MIT License
├── CODE_OF_CONDUCT.md
├── CONTRIBUTING.md
├── SECURITY.md
├── CHANGELOG.md
│
└── docs/                            # Documentation files
    ├── README.md
    ├── QUICKSTART.md
    ├── CONFIGURATION.md
    ├── DATABASE.md
    ├── ARCHITECTURE.md
    ├── API.md
    ├── USER_GUIDE.md
    ├── TESTING.md
    ├── TROUBLESHOOTING.md
    └── images/
```

---

## 🔄 Request Flow

```
1. User Action
   └─→ Browser sends HTTP request
   
2. HTTP Request Received
   └─→ Apache/PHP processes request
   
3. Authentication Check
   ├─→ Session validation
   ├─→ Permission check
   └─→ Redirect if unauthorized
   
4. Process Business Logic
   ├─→ Validate input
   ├─→ Prepare SQL query
   └─→ Execute prepared statement
   
5. Database Query
   └─→ MySQL processes query
      └─→ Returns resultset
   
6. Process Response
   ├─→ Format data
   ├─→ Apply security filters
   └─→ Generate HTML/JSON
   
7. Send Response
   └─→ Browser renders page
      └─→ Load CSS, JS assets
      └─→ Execute frontend logic
```

---

## 🔐 Security Architecture

### Input Validation Flow

```
┌──────────────────────────────────────────┐
│  1. Client-side Validation (JS)          │
│     (Immediate feedback, not security!)  │
└──────────────────────────────────────────┘
              ↓
┌──────────────────────────────────────────┐
│  2. Server-side Validation (PHP)         │
│     - Type checking                      │
│     - Length validation                  │
│     - Format validation                  │
└──────────────────────────────────────────┘
              ↓
┌──────────────────────────────────────────┐
│  3. Prepared Statements (SQL)            │
│     - Prevents SQL Injection             │
│     - Parameterized queries              │
└──────────────────────────────────────────┘
              ↓
┌──────────────────────────────────────────┐
│  4. Output Escaping (HTML)               │
│     - htmlspecialchars()                 │
│     - JSON encoding                      │
└──────────────────────────────────────────┘
```

### Authentication & Session

```
Login Process:
1. User submits username + password
2. Hash password with submitted data
3. Query: SELECT * FROM users WHERE username=?
4. Compare hashes: password_verify()
5. If match:
   └─→ session_regenerate_id()
   └─→ Set $_SESSION['user_id']
   └─→ Set $_SESSION['role']
6. Redirect to dashboard

Session Validation:
1. Check $_SESSION exists
2. Verify CSRF token on POST
3. Check user_id in database
4. Verify user is_active = 1
5. Check last_activity (timeout)
6. Allow request
```

---

## 💾 Data Model

### Core Entities

```graphql
User {
  id: ID!
  username: String! @unique
  password: String! (hashed)
  ruolo: String! (role)
  email: String
  created_at: DateTime
  last_login: DateTime
  is_active: Boolean
}

Weapon {
  id: ID!
  seriale: String! @unique
  modello: String!
  nome_compratore: String!
  cognome_compratore: String!
  registrato_da: User!
  data_registrazione: DateTime
  revocata: Boolean
  revocato_da: User
  data_revoca: DateTime
  motivo_revoca: String
}

Report {
  id: ID!
  titolo: String!
  descrizione: String!
  tipo: String! (investigativo|federale|interno)
  stato: String! (aperto|chiuso|archiviato)
  priorita: String (bassa|normale|alta|critica)
  creato_da: User!
  assegnato_a: User
  data_creazione: DateTime
  data_modificazione: DateTime
  data_chiusura: DateTime
}

Azienda {
  id: ID!
  nome: String! @unique
  proprietario: String
  tipo_attivita: String
  dipendenti: [Dipendente]
  attiva: Boolean
}

Dipendente {
  id: ID!
  azienda: Azienda!
  nome: String!
  cognome: String
  ruolo_azienda: String
  documento_id: String
  stipendio_netto: Int
}
```

---

## 🔌 API Architecture

### REST Endpoints

```
[Module] / [Resource] / [Action]

GET    /public/api/users      → List all users
GET    /public/api/users/{id} → Get specific user
POST   /public/api/users      → Create new user
PUT    /public/api/users/{id} → Update user
DELETE /public/api/users/{id} → Delete user

GET    /public/api/weapons           → List weapons
POST   /public/api/weapons/revoke    → Revoke weapon
GET    /public/api/reports?status=   → Filter reports
```

### Response Format

```json
{
  "success": true,
  "data": {
    "id": 1,
    "username": "admin"
  },
  "message": "User created successfully"
}
```

---

## 🧩 Module Structure

### Weapons Module
```
Purpose: Gestione licenze ballistiche
Purpose: Track armi registrate
Permissions: armeria60, admin
Database: weapons table
Endpoints:
  - GET /weapons              → List
  - POST /api/weapons/create  → Add new
  - POST /api/weapons/revoke  → Revoke
  - GET /api/weapons/search   → Search
```

### Reports Module
```
Purpose: Store investigative reports
Types: investigativo, federale, interno
Permissions: Varies by type
Database: reports table
States: aperto → chiuso → archiviato
```

### Users Module
```
Purpose: User and role management
Admin-only access
Database: users table
Can manage: username, email, role, status
```

---

## 🔄 Workflow Examples

### Weapon Registration Flow
```
1. Admin accesses weapons.php
2. Fills form with weapon details
3. Submits POST request
4. Server validates input
5. Prepared statement inserts record
6. System logs action (audit_logs)
7. Success message displayed
8. User redirected to weapons list
```

### Report Creation Flow
```
1. User fills report form
2. Selects type (investigativo/federale/interno)
3. Submits with CSRF token
4. Server validates:
   - User has permission for type
   - All required fields present
   - Input sanitized
5. Insert to reports table
6. Set stato = 'aperto'
7. Log action to audit_logs
8. Redirect to report detail view
```

---

## 📦 Dependencies

### PHP Built-in (No Installation Needed)
- MySQLi (database)
- Session (authentication)
- JSON (data format)
- DateTime (timestamps)

### Node.js (Optional)
```json
{
  "express": "^5.2.1",
  "mysql2": "^3.20.0",
  "express-session": "^1.19.0",
  "body-parser": "^2.2.2"
}
```

---

## 🚀 Scalability Considerations

### Current Architecture (Single Server)
- ✅ Good for: Small to medium FiveM servers
- ⚠️ Bottleneck: Database on same machine
- 📈 Supports: ~1000+ concurrent users

### For Large Scale (Future)

1. **Database Separation**
   - Move MySQL to dedicated server
   - Add read replicas

2. **Caching Layer**
   - Redis for session/query cache
   - Reduces database load

3. **Load Balancing**
   - Multiple PHP application servers
   - Sticky sessions for state

4. **CDN**
   - Static assets (CSS, JS) on CDN
   - Reduce server bandwidth

5. **Monitoring**
   - New Relic / Datadog
   - Application Performance Monitoring

---

## 🧪 Testing Architecture

### Unit Tests
```
Test: Database class functions
Test: Validation functions
Test: Permission checking
```

### Integration Tests
```
Test: Full request flow
Test: Database queries
Test: File operations
```

### Security Tests
```
Test: SQL Injection resistance
Test: XSS protection
Test: CSRF token validation
Test: Authentication bypass attempts
```

---

## 📊 Performance Metrics

### Target Metrics
- **Page Load**: < 2 seconds
- **Database Query**: < 100ms
- **API Response**: < 500ms
- **Uptime**: 99.9%

### Monitoring
- Query performance logs
- Slow query detection
- Error rate monitoring
- User activity metrics

---

**Next**: Leggi [API.md](API.md) per endpoints dettagliati.
