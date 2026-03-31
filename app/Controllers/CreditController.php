<?php

require_once __DIR__ . '/../Services/ScoringService.php';
require_once __DIR__ . '/../Services/TelegramService.php';
require_once __DIR__ . '/../Models/CreditRequest.php';

class CreditController {

    public function create($input) {

        $scoring = new ScoringService();
        $score = $scoring->calculate($input);

        $input['score'] = $score;

        $id = CreditRequest::create($input);

        $telegram = new TelegramService();
        $telegram->send([
            'id' => $id,
            'nombre' => $input['nombre'],
            'documento' => $input['documento'],
            'score' => $score
        ]);

        return [
            "status" => "CREATED",
            "score" => $score
        ];
    }
}