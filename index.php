<?php
include 'db.php';

$unique_number = ""; // Default value for unique number
$show_link = false; // Flag to show the WhatsApp link

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    
    // Generate unique number
    $stmt = $pdo->query("SELECT COUNT(*) AS count FROM users");
    $count = $stmt->fetch(PDO::FETCH_ASSOC)['count'] + 1001;
    $unique_number = "+786-$count";  // Generate unique number
    
    // Save user to database
    $stmt = $pdo->prepare("INSERT INTO users (name, password, unique_number) VALUES (?, ?, ?)");
    $stmt->execute([$name, $password, $unique_number]);
    
    $show_link = true; // Set flag to show the WhatsApp link after signup
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup</title>
    <style>
        body { font-family: Arial, sans-serif; }
        form { width: 300px; margin: 0 auto; }
        input, button { margin: 10px 0; padding: 10px; width: 100%; }
    </style>
</head>
<body>
    <h2>Signup</h2>
    <form method="POST">
        <input type="text" name="name" placeholder="Name" required><br>
        <input type="password" name="password" placeholder="Password" required><br>
        <button type="submit">Signup</button>
    </form>

    <?php if ($show_link): ?>
        <h3>You've successfully signed up!</h3>
        <p>Your unique number is: <strong><?= $unique_number ?></strong></p>
        <p>To continue, please enter your unique number below:</p>
        
        <!-- Input field for unique number to open WhatsApp -->
        <form method="POST" action="">
            <input type="text" name="entered_number" placeholder="Enter your unique number" required>
            <button type="submit">Submit</button>
        </form>

        <?php if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['entered_number']) && $_POST['entered_number'] == $unique_number): ?>
            <p>If you want to go to WhatsApp, click below:</p>
            <a href="https://wa.me/<?= str_replace("-", "", $unique_number) ?>" target="_blank">
                Click here to open WhatsApp and start messaging
            </a>
        <?php elseif ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['entered_number']) && $_POST['entered_number'] != $unique_number): ?>
            <p style="color: red;">The unique number you entered is incorrect. Please try again.</p>
        <?php endif; ?>
    <?php endif; ?>
</body>
</html>
