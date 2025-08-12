<?php
include 'db.php';
session_start();

$receiver_id = $_GET['receiver_id'];
$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("SELECT * FROM messages WHERE (sender_id = ? AND receiver_id = ?) OR (sender_id = ? AND receiver_id = ?)");
$stmt->execute([$user_id, $receiver_id, $receiver_id, $user_id]);
$messages = $stmt->fetchAll();

foreach ($messages as $msg) {
    echo $msg['sender_id'] == $user_id ? "<div class='message you'>{$msg['message']}</div>" : "<div class='message their'>{$msg['message']}</div>";
}
?>
