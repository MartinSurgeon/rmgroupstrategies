<?php
try {
    $db = new PDO("mysql:host=localhost;charset=utf8mb4", "root", "");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connected as root!\n";
    $dbs = $db->query("SHOW DATABASES")->fetchAll(PDO::FETCH_COLUMN);
    echo "Databases: " . implode(", ", $dbs) . "\n";
} catch (Exception $e) {
    echo "Root connection failed: " . $e->getMessage() . "\n";
}
