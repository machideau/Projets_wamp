<?php 
require_once 'auth.php';
if (session_status() === PHP_SESSION_NONE) session_start(); 
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Blog PHP</title>
    <link rel="stylesheet" href="css/style.css">
    <?php if (est_connecte()): ?>
    <script>
        let inactivityTime = 0;
        const inactivityLimit = 60000; // 1 minute en millisecondes
        let inactivityTimer;

        function resetInactivityTimer() {
            clearTimeout(inactivityTimer);
            inactivityTime = 0;
            inactivityTimer = setTimeout(checkInactivity, 1000);
        }

        function checkInactivity() {
            inactivityTime += 1000;
            if (inactivityTime >= inactivityLimit) {
                // Déconnexion automatique
                fetch('auto_logout.php')
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            window.location.href = 'index.php';
                        }
                    });
            } else {
                inactivityTimer = setTimeout(checkInactivity, 1000);
            }
        }

        // Événements pour détecter l'activité
        document.addEventListener('mousemove', resetInactivityTimer);
        document.addEventListener('mousedown', resetInactivityTimer);
        document.addEventListener('keypress', resetInactivityTimer);
        document.addEventListener('touchmove', resetInactivityTimer);
        document.addEventListener('scroll', resetInactivityTimer);

        // Démarrer le timer
        resetInactivityTimer();
    </script>
    <?php endif; ?>
</head>
<body>
    <div class="container">
        <header>
            <h1><a href="index.php">Mon Blog</a></h1>
            <nav>
                <?php if (est_connecte()): ?>
                    Bonjour, <?= htmlspecialchars($_SESSION['utilisateur']['nom_utilisateur']) ?> |
                    <a href="logout.php">Déconnexion</a>
                    <?php if (est_admin()): ?> |
                        <a href="admin.php">Admin</a>
                    <?php endif; ?>
                <?php else: ?>
                    <a href="login.php">Connexion</a> |
                    <a href="register.php">Inscription</a>
                <?php endif; ?>
            </nav>
        </header>
        <hr>