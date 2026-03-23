-- ============================================
-- SCRIPT SINCRONIZZAZIONE ARMI FIVEM → GESTIONALE
-- ============================================
-- Questo script sincronizza le armi acquistate nel server FiveM 
-- con il database del Gestionale
--
-- Installazione:
-- 1. Copia questo script in resources/
-- 2. Aggiungi "ensure weapon_sync" in server.cfg
-- 3. Configura i parametri sottostanti
-- ============================================

-- ============ CONFIGURAZIONE ============

local GESTIONALE_URL = "http://localhost/gestionale_fivem_master/gestionale-fivem/public/api_sync_weapons.php"
local API_KEY = "your-secret-api-key-here-change-this"  -- DEVE CORRISPONDERE A api_sync_weapons.php
local SERVER_NAME = "main_server"  -- Nome univoco del server

-- ============ FUNZIONE DI SINCRONIZZAZIONE ============

local function SyncWeaponToGestionale(playerID, playerName, weaponModel, weaponSerial, price, citizenID)
    
    -- Prepara i dati
    local data = {
        player_name = playerName,
        weapon_model = weaponModel,
        weapon_serial = weaponSerial,
        server_name = SERVER_NAME,
        price = price or 0,
        citizen_id = citizenID or nil
    }
    
    -- Header HTTP
    local headers = {
        ['Content-Type'] = 'application/json',
        ['X-API-Key'] = API_KEY
    }
    
    -- Effettua richiesta HTTP
    PerformHttpRequest(
        GESTIONALE_URL,
        function(errorCode, resultData, resultHeaders)
            
            -- Gestisci risposta
            if errorCode == 201 or errorCode == 200 then
                local response = json.decode(resultData)
                if response.success then
                    -- ✅ Sincronizzazione riuscita
                    TriggerClientEvent('chat:addMessage', playerID, {
                        color = {0, 255, 0},
                        multiline = true,
                        args = {"GESTIONALE", "🔫 Arma registrata nel database! ID: " .. response.weapon_id}
                    })
                    
                    print("^2[GESTIONALE SYNC] ✅ Arma sincronizzata - Giocatore: " .. playerName .. " | ID: " .. response.weapon_id .. "^7")
                else
                    -- ❌ Errore nella risposta
                    TriggerClientEvent('chat:addMessage', playerID, {
                        color = {255, 0, 0},
                        multiline = true,
                        args = {"GESTIONALE", "❌ Errore: " .. (response.error or "Errore sconosciuto")}
                    })
                    
                    print("^1[GESTIONALE SYNC] ❌ Errore: " .. (response.error or "Unknown error") .. "^7")
                end
            else
                -- ❌ Errore di connessione
                print("^1[GESTIONALE SYNC] ❌ Errore HTTP " .. errorCode .. ": " .. resultData .. "^7")
                TriggerClientEvent('chat:addMessage', playerID, {
                    color = {255, 0, 0},
                    multiline = true,
                    args = {"GESTIONALE", "❌ Errore di sincronizzazione (HTTP " .. errorCode .. ")"}
                })
            end
        end,
        'POST',
        json.encode(data),
        headers
    )
end

-- ============ COMANDO REGISTRAZIONE ARMA ============
-- Uso: /registerweapon <model> <serial> [price] [citizen_id]
-- Esempio: /registerweapon PISTOL_1 ABC123XYZ 5000 CITZ_123

RegisterCommand('registerweapon', function(source, args, rawCommand)
    
    -- Valida argomenti
    if #args < 2 then
        TriggerClientEvent('chat:addMessage', source, {
            color = {255, 0, 0},
            multiline = true,
            args = {"GESTIONALE", "❌ Uso: /registerweapon <model> <serial> [price] [citizen_id]"}
        })
        return
    end
    
    local playerName = GetPlayerName(source)
    local weaponModel = args[1]
    local weaponSerial = args[2]
    local price = tonumber(args[3]) or 0
    local citizenID = args[4] or nil
    
    -- Sincronizza
    print("^3[GESTIONALE SYNC] 📤 Sincronizzazione in corso...")
    SyncWeaponToGestionale(source, playerName, weaponModel, weaponSerial, price, citizenID)
    
end, false)

-- ============ EVENTO INTEGRAZIONE (Opzionale) ============
-- Gli altri script possono triggerare questo evento per sincronizzare
-- TriggerEvent('gestionale:syncWeapon', playerID, playerName, model, serial, price, citizenID)

RegisterNetEvent('gestionale:syncWeapon')
AddEventHandler('gestionale:syncWeapon', function(playerID, playerName, weaponModel, weaponSerial, price, citizenID)
    SyncWeaponToGestionale(playerID, playerName, weaponModel, weaponSerial, price, citizenID)
end)

-- ============ CALLBACK PER ARMI ACQUISTATE ============
-- Integrazione con eventuale sistema di negozi/armeria
-- Usa questa callback quando un giocatore compra un'arma

AddEventHandler('gestionale:onWeaponPurchased', function(playerID, playerName, weaponModel, weaponSerial, price, citizenID)
    print("^5[GESTIONALE] 🛒 Arma acquistata: " .. playerName .. " - " .. weaponModel .. "^7")
    SyncWeaponToGestionale(playerID, playerName, weaponModel, weaponSerial, price, citizenID)
end)

-- ============ STARTUP ============

print("^2[GESTIONALE] ✅ Script di sincronizzazione armi caricato!^7")
print("^3[GESTIONALE] 📍 Endpoint: " .. GESTIONALE_URL .. "^7")
print("^3[GESTIONALE] 📛 Server: " .. SERVER_NAME .. "^7")
