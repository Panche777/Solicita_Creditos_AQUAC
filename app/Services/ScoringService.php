<?php

class ScoringService {

    public function calculate($data) {

        $score = 0;

        // --------------------------
        // INGRESOS
        // --------------------------
        if ($data['ingresos'] >= 3000000) {
            $score += 40;
        } elseif ($data['ingresos'] >= 1500000) {
            $score += 25;
        } else {
            $score += 10;
        }

        // --------------------------
        // HISTORIAL CREDITICIO
        // --------------------------
        if (($data['historial'] ?? '') === 'bueno') {
            $score += 40;
        } elseif (($data['historial'] ?? '') === 'regular') {
            $score += 20;
        } else {
            $score += 5;
        }

        // --------------------------
        // NIVEL DE DEUDA
        // --------------------------
        if (isset($data['deudas']) && isset($data['ingresos'])) {

            $ratio = $data['deudas'] / max($data['ingresos'], 1);

            if ($ratio < 0.3) {
                $score += 20;
            } elseif ($ratio < 0.5) {
                $score += 10;
            } else {
                $score -= 10;
            }
        }

        // --------------------------
        // NORMALIZAR SCORE
        // --------------------------
        if ($score < 0) $score = 0;
        if ($score > 100) $score = 100;

        return $score;
    }
}