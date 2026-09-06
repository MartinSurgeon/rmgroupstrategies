<?php
try {
    $db = new PDO("mysql:host=localhost;dbname=rmgroupstrategies;charset=utf8mb4", "root", "");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $cols = $db->query("DESCRIBE rental_agreements")->fetchAll(PDO::FETCH_ASSOC);
    echo "rental_agreements columns:\n";
    foreach ($cols as $col) {
        echo "  " . $col['Field'] . " | " . $col['Type'] . " | Null:" . $col['Null'] . " | Default:" . var_export($col['Default'], true) . "\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
