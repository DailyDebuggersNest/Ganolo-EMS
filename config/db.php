<?php
$host = 'localhost'; // Adjust if needed
$dbname = 'testems_database';
$username = 'root'; // Your DB username
$password = ''; // Your DB password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>