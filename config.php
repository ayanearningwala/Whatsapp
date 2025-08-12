<?php
define('DB_HOST', 'localhost');
define('DB_NAME', 'dbrzbllz0kcvpb');
define('DB_USER', 'uge1jimygn4gy');
define('DB_PASS', 'wwkxsiva40rx');

// Establish the connection
try {
    $pdo = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Could not connect to the database: " . $e->getMessage());
}
?>
