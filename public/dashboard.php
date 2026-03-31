<?php

require_once __DIR__ . '/../app/Core/Autoload.php';
require_once __DIR__ . '/../app/Core/Env.php';

Env::load(__DIR__ . '/../.env');

$auth = new AuthController();

if (!$auth->check()) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
</head>
<body>

<h1>Bienvenido al sistema</h1>

<a href="logout.php">Cerrar sesión</a>

</body>
</html>