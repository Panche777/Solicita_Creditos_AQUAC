<?php

require_once __DIR__ . '/../Core/Database.php';

class CreditRequest {

    public static function create($data) {
        $db = Database::connect();

        $stmt = $db->prepare("INSERT INTO credit_requests (nombre, documento, score, status) VALUES (?, ?, ?, ?)");
        $stmt->execute([
            $data['nombre'],
            $data['documento'],
            $data['score'],
            'PENDING'
        ]);

        return $db->lastInsertId();
    }

    public static function updateStatus($id, $status) {
        $db = Database::connect();

        $stmt = $db->prepare("UPDATE credit_requests SET status=? WHERE id=?");
        $stmt->execute([$status, $id]);
    }
}