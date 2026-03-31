<?php

require_once 'app/Core/Autoload.php';

$db = Database::connect();

$db->exec("
CREATE TABLE IF NOT EXISTS credit_requests (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nombre TEXT,
    documento TEXT,
    score INTEGER,
    status TEXT
);
");

echo "OK";