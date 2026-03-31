<?php

require_once __DIR__ . '/../Models/User.php';

class AuthController {

    public function login($username, $password) {

        $user = User::findByUsername($username);

        if (!$user) {
            return ['error' => 'Usuario no existe'];
        }

        if (!password_verify($password, $user['password'])) {
            return ['error' => 'Contraseña incorrecta'];
        }

        session_start();
        $_SESSION['user'] = $user['id'];

        return ['success' => true];
    }

    public function check() {
        session_start();
        return isset($_SESSION['user']);
    }

    public function logout() {
        session_start();
        session_destroy();
    }
}