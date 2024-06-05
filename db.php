<?php
define('DB_SERVER', '');
define('DB_PORT', '3306'); // MySQL'in varsayılan portu 3306'dır, fakat gerektiğinde bu portu değiştirebilirsiniz
define('DB_USERNAME', '');
define('DB_PASSWORD', '');
define('DB_NAME', '');

try {
    $pdo = new PDO("mysql:host=" . DB_SERVER . ";port=" . DB_PORT . ";dbname=" . DB_NAME, DB_USERNAME, DB_PASSWORD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("ERROR: Could not connect. " . $e->getMessage());
}
?>
