<?php
$allowed_ips_file = 'allowed_ips.json';
$allowed_ips = file($allowed_ips_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

$visitor_ip = $_SERVER['REMOTE_ADDR'];

if (!in_array($visitor_ip, $allowed_ips)) {
    header('HTTP/1.1 403 Forbidden');
    echo '403 Forbidden - Erişim izniniz yok.';
    exit();
}

echo 'Hoş geldiniz, izin verilen IP adresinden giri yaptınız!';
?>
