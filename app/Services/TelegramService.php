<?php

class TelegramService {

    public function send($data) {

        $message = "🚨 Nueva solicitud\n\n"
            . "👤 {$data['nombre']}\n"
            . "💳 {$data['documento']}\n"
            . "📊 Score: {$data['score']}";

        $keyboard = [
            "inline_keyboard" => [
                [
                    ["text" => "✅ Aprobar", "callback_data" => "approve_{$data['id']}"],
                    ["text" => "❌ Rechazar", "callback_data" => "reject_{$data['id']}"]
                ]
            ]
        ];

        file_get_contents("https://api.telegram.org/bot{$_ENV['TELEGRAM_BOT_TOKEN']}/sendMessage?" . http_build_query([
            "chat_id" => $_ENV['TELEGRAM_CHAT_ID'],
            "text" => $message,
            "reply_markup" => json_encode($keyboard)
        ]));
    }
}