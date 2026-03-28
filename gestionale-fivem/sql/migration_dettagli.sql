-- ========================================
-- MIGRATION: Estensione tabelle per dettagli
-- Data: 2026-03-28
-- ========================================

-- 1️⃣ ESTENSIONE WEAPONS: Aggiungere campi dettagliati
ALTER TABLE weapons ADD COLUMN (
    calibro VARCHAR(50) DEFAULT NULL,
    tipo_munizione VARCHAR(100) DEFAULT NULL,
    condizione VARCHAR(50) DEFAULT 'buona',
    data_registrazione TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    note_tecniche TEXT DEFAULT NULL,
    proprietario_nome VARCHAR(100) DEFAULT NULL,
    proprietario_cognome VARCHAR(100) DEFAULT NULL,
    location VARCHAR(100) DEFAULT NULL
) AFTER modello;

-- 2️⃣ ESTENSIONE REPORTS: Aggiungere campi dettagliati
ALTER TABLE reports ADD COLUMN (
    data_creazione TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    priorita VARCHAR(20) DEFAULT 'media',
    tipo_indagine VARCHAR(50) DEFAULT 'standard',
    numero_vittime INT DEFAULT 0,
    importo_stimato DECIMAL(15,2) DEFAULT 0,
    location_crimine VARCHAR(200) DEFAULT NULL,
    agenti_coinvolti TEXT DEFAULT NULL
) AFTER categoria;

-- 3️⃣ ESTENSIONE AZIENDE: Aggiungere campi dettagliati
ALTER TABLE aziende ADD COLUMN (
    indirizzo VARCHAR(255) DEFAULT NULL,
    telephone VARCHAR(20) DEFAULT NULL,
    email VARCHAR(100) DEFAULT NULL,
    tipo_business VARCHAR(50) DEFAULT 'commerciale',
    data_registrazione TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    stato VARCHAR(20) DEFAULT 'attiva',
    numero_dipendenti INT DEFAULT 0,
    note_azienda TEXT DEFAULT NULL
) AFTER titolare;

-- 4️⃣ ESTENSIONE DIPENDENTI: Aggiungere campi dettagliati
ALTER TABLE dipendenti ADD COLUMN (
    data_assunzione DATE DEFAULT NULL,
    salario DECIMAL(12,2) DEFAULT NULL,
    telefono VARCHAR(20) DEFAULT NULL,
    email VARCHAR(100) DEFAULT NULL,
    note_dipendente TEXT DEFAULT NULL
) AFTER ruolo;

-- Indici per performance
CREATE INDEX idx_weapons_seriale ON weapons(seriale);
CREATE INDEX idx_reports_creato_da ON reports(creato_da);
CREATE INDEX idx_reports_stato ON reports(stato);
CREATE INDEX idx_aziende_stato ON aziende(stato);
CREATE INDEX idx_dipendenti_azienda ON dipendenti(azienda_id);
