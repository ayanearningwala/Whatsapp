<?php
include 'db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $unique_number = '+786-' . (1000 + rand(1, 9999));

    $stmt = $pdo->prepare("INSERT INTO users (name, password, unique_number) VALUES (?, ?, ?)");
    $stmt->execute([$name, $password, $unique_number]);

    echo "Signup successful! Your Unique Number is: $unique_number";
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Signup</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #ece5dd; }
        .container { width: 300px; margin: 50px auto; padding: 20px; background: #fff; border-radius: 5px; }
        input, button { margin: 10px 0; width: 100%; padding: 8px; }
        button { background-color: #128C7E; color: white; border: none; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Signup</h2>
        <form method="POST">
            <input type="text" name="name" placeholder="Name" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Signup</button>
        </form>
    </div>
</body>
</html>
