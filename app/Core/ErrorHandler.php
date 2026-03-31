<?php

class ErrorHandler {

    public static function log($message, $context = []) {

        $log = [
            'date' => date('Y-m-d H:i:s'),
            'message' => $message,
            'context' => $context
        ];

        file_put_contents(
            __DIR__ . '/../../storage/logs/error.log',
            json_encode($log) . PHP_EOL,
            FILE_APPEND
        );
    }

    public static function handleException($e) {

        self::log($e->getMessage(), [
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ]);

        http_response_code(500);

        echo json_encode([
            'status' => 'error',
            'message' => 'Error interno del servidor'
        ]);

        exit;
    }
}