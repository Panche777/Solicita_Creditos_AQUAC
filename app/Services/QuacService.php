<?php

class QuacService {

    public function send($request) {

        $payload = [
            'client_document_type' => $request['tipo_documento'] ?? 'CC',
            'client_document' => $request['documento'],
            'slug_business' => $request['slug_business'] ?? 'stirpe', // ⚠️ AJUSTAR
        ];

        // opcional
        if (!empty($request['slug_store'])) {
            $payload['slug_store'] = $request['slug_store'];
        }

        $ch = curl_init();

        curl_setopt_array($ch, [
            CURLOPT_URL => "https://apipreaprobado.quac.co/api/v1/preapproved/front/create-credit-request",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query($payload),
            CURLOPT_HTTPHEADER => [
                "Authorization: Bearer " . $_ENV['QUAC_TOKEN'],
                "X-Requested-With: XMLHttpRequest",
                "Content-Type: application/x-www-form-urlencoded"
            ]
        ]);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            ErrorHandler::log("CURL_ERROR", curl_error($ch));
        }

        curl_close($ch);

        return $response ? json_decode($response, true) : null;
    }
}