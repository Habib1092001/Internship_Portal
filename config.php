<?php
// config.php
$host = "localhost";
$dbname = "internship_portal";
$dbuser = "root";
$dbpass = "";

try {
    $pdo = new PDO("mysql:host={$host};dbname={$dbname};charset=utf8", $dbuser, $dbpass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (PDOException $e) {
    // In production, do not echo errors. Log them.
    die("Database connection failed: " . $e->getMessage());
}
