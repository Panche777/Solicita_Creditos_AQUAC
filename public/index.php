<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// ------------------------------
// BOOTSTRAP
// ------------------------------
require_once __DIR__ . '/../app/Core/Autoload.php';
require_once __DIR__ . '/../app/Core/Env.php';

Env::load(__DIR__ . '/../.env');

set_exception_handler(['ErrorHandler', 'handleException']);

// ------------------------------
// HEADERS
// ------------------------------
header('Content-Type: application/json');

// ------------------------------
// INPUT (puedes cambiar a POST)
// ------------------------------
$data = [
    'nombre' => $_GET['nombre'] ?? 'Carlos',
    'documento' => $_GET['documento'] ?? '123456',
    'ingresos' => $_GET['ingresos'] ?? 3000000,
    'historial' => $_GET['historial'] ?? 'bueno',
    'deudas' => $_GET['deudas'] ?? 500000
];

// ------------------------------
// CONTROLLER
// ------------------------------
$controller = new CreditController();

$response = $controller->create($data);

// ------------------------------
// OUTPUT
// ------------------------------
echo json_encode($response);