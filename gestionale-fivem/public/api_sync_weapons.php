<?php
/**
 * API ENDPOINT - Sincronizzazione Armi FiveM → Database Gestionale
 * 
 * Riceve richieste POST dal Server FiveM e aggiunge le armi al database
 * 
 * Uso da FiveM Script (Lua):
 * PerformHttpRequest('http://localhost/gestionale_fivem_master/gestionale-fivem/public/api_sync_weapons.php', 
 *     RequestHandler, 'POST', json.encode(data), headers)
 */

header('Content-Type: application/json');
include('db.php');

// ============ CONFIGURAZIONE SICUREZZA ============
define('API_KEY', 'your-secret-api-key-here-change-this');  // CAMBIA QUESTA CHIAVE!
define('ALLOWED_SERVERS', ['main_server', 'test_server']); // Whitelist server

// ============ VALIDAZIONE RICHIESTA ============

// 1. Controlla metodo HTTP
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    die(json_encode(['success' => false, 'error' => 'Method not allowed. Use POST.']));
}

// 2. Controlla Content-Type
$content_type = $_SERVER['CONTENT_TYPE'] ?? '';
if (strpos($content_type, 'application/json') === false) {
    http_response_code(400);
    die(json_encode(['success' => false, 'error' => 'Content-Type must be application/json']));
}

// 3. Leggi il body JSON
$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (!$data) {
    http_response_code(400);
    die(json_encode(['success' => false, 'error' => 'Invalid JSON format']));
}

// 4. Valida API Key
$api_key = $_SERVER['HTTP_X_API_KEY'] ?? $_GET['api_key'] ?? null;
if (!$api_key || $api_key !== API_KEY) {
    
    // LOG TENTATIVO DI ACCESSO NON AUTORIZZATO
    log_audit(
        'sync_weapons_failed',
        'weapons_api',
        "Tentativo API non autorizzato. IP: " . $_SERVER['REMOTE_ADDR'],
        null,
        'denied'
    );
    
    http_response_code(401);
    die(json_encode(['success' => false, 'error' => 'Unauthorized. Invalid API Key.']));
}

// ============ VALIDAZIONE DATI RICHIESTI ============

$required_fields = ['player_name', 'weapon_model', 'weapon_serial', 'server_name'];
$missing_fields = [];

foreach ($required_fields as $field) {
    if (!isset($data[$field]) || empty($data[$field])) {
        $missing_fields[] = $field;
    }
}

if (!empty($missing_fields)) {
    http_response_code(400);
    die(json_encode([
        'success' => false,
        'error' => 'Missing required fields: ' . implode(', ', $missing_fields)
    ]));
}

// ============ SANITIZZAZIONE E VALIDAZIONE ============

// Sanitizza input
$player_name = trim($data['player_name']);
$weapon_model = trim($data['weapon_model']);
$weapon_serial = trim($data['weapon_serial']);
$server_name = trim($data['server_name']);
$price = isset($data['price']) ? (float)$data['price'] : 0;
$citizen_id = isset($data['citizen_id']) ? trim($data['citizen_id']) : null;

// Validazione lunghezza
if (strlen($player_name) > 100) {
    http_response_code(400);
    die(json_encode(['success' => false, 'error' => 'Player name too long (max 100 chars)']));
}

if (strlen($weapon_serial) > 50) {
    http_response_code(400);
    die(json_encode(['success' => false, 'error' => 'Weapon serial too long (max 50 chars)']));
}

// Valida che il seriale sia UNICO
$check_serial = "SELECT id FROM weapons WHERE seriale = ?";
$stmt = $conn->prepare($check_serial);
$stmt->bind_param("s", $weapon_serial);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    http_response_code(409);
    die(json_encode([
        'success' => false,
        'error' => 'Weapon with this serial already exists in database'
    ]));
}

// ============ INSERIMENTO NEL DATABASE ============

$ip_address = $_SERVER['REMOTE_ADDR'];
$data_ora = date('Y-m-d H:i:s');

$sql = "INSERT INTO weapons (nome_compratore, seriale, modello, data_ora, server_sync, citizen_id, prezzo) 
        VALUES (?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    http_response_code(500);
    die(json_encode([
        'success' => false,
        'error' => 'Database error: ' . $conn->error
    ]));
}

$stmt->bind_param("ssssssd", $player_name, $weapon_serial, $weapon_model, $data_ora, $server_name, $citizen_id, $price);

if ($stmt->execute()) {
    $weapon_id = $conn->insert_id;
    
    // ✅ LOG AUDIT - Sincronizzazione riuscita
    log_audit(
        'sync_weapons',
        'weapons_api',
        json_encode([
            'player' => $player_name,
            'model' => $weapon_model,
            'serial' => $weapon_serial,
            'server' => $server_name,
            'price' => $price,
            'citizen_id' => $citizen_id
        ]),
        $weapon_id,
        'success'
    );
    
    http_response_code(201);
    echo json_encode([
        'success' => true,
        'message' => 'Weapon added successfully',
        'weapon_id' => $weapon_id,
        'timestamp' => $data_ora
    ]);
} else {
    // ❌ LOG AUDIT - Errore inserimento
    log_audit(
        'sync_weapons_error',
        'weapons_api',
        "Errore inserimento: " . $conn->error,
        null,
        'error'
    );
    
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Failed to insert weapon: ' . $conn->error
    ]);
}

$stmt->close();
$conn->close();
?>
