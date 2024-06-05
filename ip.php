<?php
require_once 'db.php'; // Veritabanı bağlantısı

$visitor_ip = $_SERVER['REMOTE_ADDR'];

$stmt = $pdo->prepare("SELECT ip_address FROM ips");
$stmt->execute();
$allowed_ips = $stmt->fetchAll(PDO::FETCH_COLUMN);

if (!in_array($visitor_ip, $allowed_ips)) {
    header('HTTP/1.1 403 Forbidden');
    exit();
}

?>
