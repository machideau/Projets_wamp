<?php
require_once __DIR__ . '/../config/security.php';
require_once __DIR__ . '/../models/Message.php';

// Vérifier si l'utilisateur est admin
// is_admin();

$messageModel = new Message();
$messages = $messageModel->getAllMessages();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration - Messages</title>
    <link rel="stylesheet" href="../css/admin.css">
</head>
<body class="admin-panel">
    <div class="admin-container">
        <h1>Gestion des Messages</h1>
        
        <div class="messages-list">
            <?php foreach ($messages as $message): ?>
            <div class="message-card <?php echo $message['status']; ?>">
                <div class="message-header">
                    <h3><?php echo htmlspecialchars($message['subject']); ?></h3>
                    <span class="date"><?php echo date('d/m/Y H:i', strtotime($message['created_at'])); ?></span>
                </div>
                <div class="message-content">
                    <p><strong>De:</strong> <?php echo htmlspecialchars($message['name']); ?> 
                       (<?php echo htmlspecialchars($message['email']); ?>)</p>
                    <p><?php echo nl2br(htmlspecialchars($message['message'])); ?></p>
                </div>
                <div class="message-actions">
                    <button onclick="markAsRead(<?php echo $message['id']; ?>)"
                            class="btn <?php echo $message['status'] === 'read' ? 'disabled' : ''; ?>">
                        Marquer comme lu
                    </button>
                    <button onclick="deleteMessage(<?php echo $message['id']; ?>)" 
                            class="btn btn-danger">
                        Supprimer
                    </button>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <script>
    async function markAsRead(id) {
        try {
            const response = await fetch('../api/messages/mark-read.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ id, csrf_token: '<?php echo generate_csrf_token(); ?>' })
            });
            
            if (response.ok) {
                location.reload();
            }
        } catch (error) {
            console.error('Erreur:', error);
        }
    }

    async function deleteMessage(id) {
        if (!confirm('Voulez-vous vraiment supprimer ce message ?')) return;
        
        try {
            const response = await fetch('../api/messages/delete.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ id, csrf_token: '<?php echo generate_csrf_token(); ?>' })
            });
            
            if (response.ok) {
                location.reload();
            }
        } catch (error) {
            console.error('Erreur:', error);
        }
    }
    </script>
</body>
</html> 