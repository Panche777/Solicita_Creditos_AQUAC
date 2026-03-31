<?php

// ------------------------------
// BOOTSTRAP
// ------------------------------
require_once __DIR__ . '/app/Core/Autoload.php';
require_once __DIR__ . '/app/Core/Env.php';

Env::load(__DIR__ . '/.env');

set_exception_handler(['ErrorHandler', 'handleException']);

// ------------------------------
// INPUT TELEGRAM
// ------------------------------
$update = json_decode(file_get_contents("php://input"), true);

// ------------------------------
// LOG TELEGRAM
// ------------------------------
ErrorHandler::log("TELEGRAM_UPDATE", $update);

// Validar que exista callback
if (!isset($update['callback_query'])) {
    exit;
}

$data = $update['callback_query']['data'];
$chatId = $update['callback_query']['message']['chat']['id'];

// ------------------------------
// INSTANCIAS
// ------------------------------
$quac = new QuacService();

// ------------------------------
// APROBAR
// ------------------------------
if (strpos($data, 'approve_') !== false) {

    $id = explode('_', $data)[1];

    try {

        // Obtener datos reales
        $request = CreditRequest::find($id);

        if (!$request) {
            sendTelegramMessage($chatId, "❌ Error: solicitud no encontrada (#$id)");
            exit;
        }

        // Estado interno aprobado
        CreditRequest::updateStatus($id, 'APPROVED_INTERNAL');

        // ------------------------------
        // ENVÍO A QUAC (BÁSICO)
        // ------------------------------
        $quacResponse = $quac->send([
    'documento' => $request['documento'],
    'tipo_documento' => $request['tipo_documento'],
    'slug_business' => $request['slug_business'],
    'slug_store' => $request['slug_store']
]);

if (isset($quacResponse['url'])) {

    CreditRequest::updateStatus($id, 'LINK_GENERATED');

    sendTelegramMessage($chatId,
        "✅ QUAC generado\n\n" .
        "ID: $id\n" .
        "🔗 " . $quacResponse['url']
    );

} else {

    CreditRequest::updateStatus($id, 'ERROR_QUAC');

    sendTelegramMessage($chatId,
        "⚠️ QUAC no respondió correctamente (#$id)"
    );
}
        if (!$quacResponse) {
            CreditRequest::updateStatus($id, 'ERROR_QUAC');

            sendTelegramMessage($chatId, "⚠️ Error enviando a QUAC (#$id)");
            exit;
        }

        // Guardar respuesta completa
        CreditRequest::saveResponse($id, json_encode($quacResponse));

        // Estado enviado
        CreditRequest::updateStatus($id, 'SENT_TO_QUAC');

        sendTelegramMessage($chatId,
            "✅ Enviado a QUAC\n\nID: $id\nDoc: {$request['documento']}"
        );

    } catch (Exception $e) {

        CreditRequest::updateStatus($id, 'ERROR');

        ErrorHandler::log("ERROR_APPROVE", [
            'id' => $id,
            'error' => $e->getMessage()
        ]);

        sendTelegramMessage($chatId, "❌ Error crítico (#$id)");
    }
}

// ------------------------------
// RECHAZAR
// ------------------------------
if (strpos($data, 'reject_') !== false) {

    $id = explode('_', $data)[1];

    CreditRequest::updateStatus($id, 'REJECTED_INTERNAL');

    sendTelegramMessage($chatId, "❌ Solicitud #$id rechazada");
}

// ------------------------------
// FUNCIONES AUXILIARES
// ------------------------------
function sendTelegramMessage($chatId, $text) {

    $url = "https://api.telegram.org/bot" . $_ENV['TELEGRAM_BOT_TOKEN'] . "/sendMessage";

    file_get_contents($url . "?" . http_build_query([
        "chat_id" => $chatId,
        "text" => $text
    ]));
}