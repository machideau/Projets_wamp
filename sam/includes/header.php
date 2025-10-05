<?php require_once 'config.php'; ?>
<!DOCTYPE html>
<html lang="fr" data-theme="<?php echo $theme; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sam le Dev - Portfolio</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <header class="header-area">
        <nav>
            <div class="logo" onclick="window.location.href='index.php'">
                <img src="images/logo.png" alt="Logo Portfolio" class="logo-img">
                <span class="logo-text">SAM le DEV</span>
            </div>

            <button class="hamburger" id="hamburger" aria-label="Menu">
                <span></span>
                <span></span>
                <span></span>
            </button>

            <div class="nav-menu">
                <ul class="nav-links">
                    <li><a href="index.php" <?php echo ($_SERVER['PHP_SELF'] == '/index.php') ? 'class="active"' : ''; ?>>Accueil</a></li>
                    <li><a href="#apropos">À Propos</a></li>
                    <li><a href="#competences">Compétences</a></li>
                    <li><a href="projets.php" <?php echo ($_SERVER['PHP_SELF'] == '/projets.php') ? 'class="active"' : ''; ?>>Projets</a></li>
                    <li><a href="#contact">Contact</a></li>
                </ul>
            </div>

            <div class="auth-container">
                <?php if (isLoggedIn()): ?>
                    <?php $user = getUserData(); ?>
                    <div class="profile-circle">
                        <img src="<?php echo htmlspecialchars($user['profile_photo']); ?>" alt="Profile" class="profile-image">
                        <div class="auth-menu">
                            <a href="profile.php" class="auth-menu-item">
                                <i class="fas fa-user"></i>
                                <span>Mon Profil</span>
                            </a>
                            <a href="auth/logout.php" class="auth-menu-item">
                                <i class="fas fa-sign-out-alt"></i>
                                <span>Déconnexion</span>
                            </a>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="profile-circle">
                        <img src="images/default-avatar.webp" alt="Profile" class="profile-image">
                        <div class="auth-menu">
                            <a href="auth/login.php" class="auth-menu-item">
                                <i class="fas fa-sign-in-alt"></i>
                                <span>Connexion</span>
                            </a>
                            <a href="auth/register.php" class="auth-menu-item">
                                <i class="fas fa-user-plus"></i>
                                <span>Inscription</span>
                            </a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </nav>
    </header>
</body>
</html> 