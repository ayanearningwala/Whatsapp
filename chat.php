<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$contacts = $pdo->query("SELECT * FROM users WHERE id != $user_id")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Chat</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            margin: 0; 
            display: flex; 
            background: #ece5dd; 
            height: 100vh; 
        }

        /* Contacts Section */
        #contacts { 
            width: 30%; 
            background: #fff; 
            overflow-y: auto; 
            border-right: 1px solid #ccc; 
            display: flex; 
            flex-direction: column;
        }

        #search-bar {
            padding: 10px;
            background: #f1f1f1;
            border-bottom: 1px solid #ccc;
        }

        #search-bar input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .contact { 
            padding: 15px; 
            border-bottom: 1px solid #ddd; 
            cursor: pointer; 
        }

        .contact:hover { 
            background: #f1f1f1; 
        }

        /* Chat Section */
        #chat { 
            width: 70%; 
            display: flex; 
            flex-direction: column; 
            height: 100%; 
        }

        #chat-header { 
            background: #075e54; 
            color: #fff; 
            padding: 15px; 
            text-align: center; 
        }

        #chat-window { 
            flex: 1; 
            overflow-y: auto; 
            padding: 10px; 
            background: #e5ddd5; 
        }

        .message { 
            margin: 5px; 
            padding: 8px 12px; 
            border-radius: 8px; 
            max-width: 60%; 
        }

        .message.you { 
            background: #dcf8c6; 
            align-self: flex-end; 
            text-align: right; 
        }

        .message.their { 
            background: #fff; 
            align-self: flex-start; 
            text-align: left; 
        }

        /* Message Form */
        #messageForm { 
            display: flex; 
            border-top: 1px solid #ccc; 
            background: #fff; 
            padding: 10px; 
        }

        #messageForm input[type="text"] { 
            flex: 1; 
            padding: 10px; 
            border: 1px solid #ccc; 
            border-radius: 5px; 
            margin-right: 10px; 
        }

        #messageForm button { 
            padding: 10px 20px; 
            background: #128C7E; 
            color: #fff; 
            border: none; 
            border-radius: 5px; 
            cursor: pointer; 
        }

        #messageForm button:hover { 
            background: #075e54; 
        }
    </style>
</head>
<body>
    <!-- Contacts Section -->
    <div id="contacts">
        <h3 style="text-align: center; background: #128C7E; color: white; padding: 10px;">Contacts</h3>
        <div id="search-bar">
            <input type="text" id="contact-search" placeholder="Search by name or number...">
        </div>
        <div id="contact-list">
            <?php foreach ($contacts as $contact): ?>
                <div class="contact" data-name="<?= strtolower($contact['name']) ?>" data-number="<?= $contact['unique_number'] ?>" onclick="loadChat(<?= $contact['id'] ?>, '<?= $contact['name'] ?>')">
                    <?= $contact['name'] ?> (<?= $contact['unique_number'] ?>)
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Chat Section -->
    <div id="chat">
        <div id="chat-header">Select a Contact</div>
        <div id="chat-window"></div>
        <form id="messageForm">
            <input type="hidden" id="receiver_id">
            <input type="text" id="message" placeholder="Type a message..." required>
            <button type="submit">Send</button>
        </form>
    </div>

    <script>
        let currentChatUser = '';

        // Function to Load Chat
        function loadChat(receiverId, receiverName) {
            document.getElementById('receiver_id').value = receiverId;
            document.getElementById('chat-header').innerText = receiverName;
            
            fetch('load_chat.php?receiver_id=' + receiverId)
                .then(response => response.text())
                .then(data => {
                    document.getElementById('chat-window').innerHTML = data;
                    document.getElementById('chat-window').scrollTop = document.getElementById('chat-window').scrollHeight;
                });
        }

        // Message Send Event
        document.getElementById('messageForm').addEventListener('submit', function(e) {
            e.preventDefault();
            let message = document.getElementById('message').value;
            let receiverId = document.getElementById('receiver_id').value;

            fetch('send_message.php', {
                method: 'POST',
                body: new URLSearchParams({ message, receiver_id: receiverId })
            }).then(() => {
                loadChat(receiverId, currentChatUser);
                document.getElementById('message').value = '';
            });
        });

        // Search Contacts
        document.getElementById('contact-search').addEventListener('input', function() {
            let searchValue = this.value.toLowerCase();
            let contacts = document.querySelectorAll('.contact');

            contacts.forEach(contact => {
                let name = contact.getAttribute('data-name');
                let number = contact.getAttribute('data-number');

                if (name.includes(searchValue) || number.includes(searchValue)) {
                    contact.style.display = 'block';
                } else {
                    contact.style.display = 'none';
                }
            });
        });
    </script>
</body>
</html>
