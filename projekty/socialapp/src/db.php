<?php
// src/db.php
$config = require __DIR__ . '/../config/config.php';
$dsn = "mysql:host={$config['db']['host']};dbname={$config['db']['dbname']};charset=utf8mb4";
try {
$db = new PDO($dsn, $config['db']['user'], $config['db']['pass'], [
PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
]);
} catch (PDOException $e) {
die('DB connection failed: ' . $e->getMessage());
}