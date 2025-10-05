<?php
require_once __DIR__ . '/../config/config.php';
require_once CONFIG_PATH . '/database.php';
require_once __DIR__ . '/../includes/auth.php';
include __DIR__ . '/header.php';
?>

<div class="container">
    <h2>Se connecter</h2>
    
    <?php Flash::display(); ?>

    <form method="POST" action="<?= BASE_URL ?>/index.php?action=login">
        <input type="hidden" name="csrf_token" value="<?= CSRF::generateToken() ?>">
        
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" class="form-control" name="email" id="email" required>
        </div>
        <div class="form-group">
            <label for="password">Mot de passe:</label>
            <input type="password" class="form-control" name="password" id="password" required>
        </div>
        <br>
        <div class="form-group">
            <a href="<?= BASE_URL ?>/views/inscription.php" class="btn btn-link">S'inscrire</a>
            <button type="submit" class="btn btn-primary">Se connecter</button>
        </div>
    </form>
</div>

<?php include __DIR__ . '/footer.php'; ?>
