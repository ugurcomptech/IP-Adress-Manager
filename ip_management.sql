CREATE DATABASE ip_management;
USE ip_management;

CREATE TABLE ips (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ip_address VARCHAR(45) NOT NULL,
    expiry_date DATETIME,
    creation_date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    expiry_seconds INT UNSIGNED NOT NULL DEFAULT 0
);
