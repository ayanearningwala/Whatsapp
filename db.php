<?php
$host = 'localhost'; // Server address
$dbname = 'dbrzbllz0kcvpb'; // Database name
$username = 'uge1jimygn4gy'; // Database username
$password = 'wwkxsiva40rx'; // Database password

// Create a PDO instance
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Could not connect to the database $dbname :" . $e->getMessage());
}
?>
