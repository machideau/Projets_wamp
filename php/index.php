<?php
include "php/session.php";
$user = getUserInfo();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="all.min.css">
    <!-- <link rel="stylesheet" href="bootstrap.css"> -->
    <link rel="stylesheet" href="style.css">
    <link rel="shortcut icon" href="uploads/icon/logo.png" type="image/x-icon">
    <title>Learn PHP</title>
</head>
<body>
    <div class="navbar">
        <div class="nav-content">
            <div class="logo" href="index.php">
                <span>SAMUEL</span>
            </div>
            <ul>
                <?php if($user): ?>
                    <li class="profile-container">
                        <div class="profile-info">
                            <a href="uploads/profiles/<?php echo $user['profile_image']; ?>"><img src="uploads/profiles/<?php echo $user['profile_image']; ?>" alt="Profile" class="profile-image"></a>
                            <span class="user-name"><?php echo $user['name']; ?></span>
                        </div>
                        <a href="update.php" class="update-btn">
                            <span>Modifier le compte</span>
                        </a>
                        <a href="delete.php" class="delete-btn">
                            <span>Supprimer le compte</span>
                        </a>
                        <a href="php/logout.php" class="logout-btn">
                            <span>Déconnexion</span>
                        </a>
                    </li>
                <?php else: ?>
                    <li><a href="login.html" class="nav-btn login-btn"> Connexion</a></li>
                    <li><a href="register.html" class="nav-btn register-btn"> Enregistrement</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>

    <div class="container">
        <div class="welcome-content">
            <h1>
                <div class="welcome-text">Bienvenue sur</div>
                <strong class="platform-name">
                   SAM, <span>La grosse tete</span>
                </strong>
            </h1>
            <p class="subtitle">Apprenons a coder avec SAM le DEV !!!</p>
            <?php if(!$user): ?>
                <div class="cta-buttons">
                    <a href="register.html" class="cta-btn primary">Commencer maintenant</a>
                    <a href="login.html" class="cta-btn secondary">Déjà membre ?</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
