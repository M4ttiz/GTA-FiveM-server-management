const express = require('express');
const mysql = require('mysql2');
const session = require('express-session');
const path = require('path');

const app = express();

// Middleware per leggere i dati inviati dal login
app.use(express.json());
app.use(express.static(path.join(__dirname, 'public')));

// Configurazione Sessione
app.use(session({
    secret: 'secret-key-fivem',
    resave: false,
    saveUninitialized: false
}));

// Connessione Database
const db = mysql.createConnection({
    host: 'localhost',
    user: 'root',
    password: '', // Solitamente vuota su XAMPP
    database: 'fivem_gestionale'
});

db.connect(err => {
    if (err) {
        console.error("❌ ERRORE DB: Controlla che XAMPP sia attivo!", err.message);
    } else {
        console.log("✅ DATABASE CONNESSO!");
    }
});

// Rotta Login con Log di controllo
app.post('/api/login', (req, res) => {
    const { username, password } = req.body;
    
    console.log(`--- Tentativo di login: ${username} ---`);

    if (!username || !password) {
        return res.status(400).json({ success: false, message: "Mancano dati" });
    }

    db.query('SELECT * FROM users WHERE username = ? AND password = ?', [username, password], (err, results) => {
        if (err) {
            console.error("❌ Errore nella Query:", err.message);
            return res.status(500).json({ success: false, message: "Errore database" });
        }

        if (results && results.length > 0) {
            req.session.user = results[0];
            console.log(`✅ Login Successo: ${username}`);
            res.json({ success: true, redirect: '/dashboard.html' });
        } else {
            console.log(`⚠️ Login Fallito: Credenziali errate per ${username}`);
            res.status(401).json({ success: false, message: "Credenziali errate" });
        }
    });
});

app.listen(3000, () => {
    console.log("\n🚀 SERVER PRONTO: http://localhost:3000");
    console.log("Guarda qui sotto quando premi il tasto login nel browser...\n");
});