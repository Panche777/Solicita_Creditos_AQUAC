<?php

require_once __DIR__ . '/../app/Core/Autoload.php';

$auth = new AuthController();
$auth->logout();

header("Location: login.php");