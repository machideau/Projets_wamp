<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créez votre compte enseignant</title>
    <!-- Mise à jour des liens Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- liens bootstrap en local -->
    <link rel="stylesheet" href="../css/all.min.css">
    <link rel="stylesheet" href="../css/bootstrap.css">
    <link rel="stylesheet" href="../css/styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
        }
        .navbar {
            background-color: #007bff;
        }
        .navbar-brand, .nav-link {
            color: white !important;
        }
        .container {
            max-width: 600px;
            margin: 100px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .hidden {
            display: none;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="<?= BASE_URL ?>/index.php">Gestion Scolaire</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/index.php">Accueil</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/views/enseignants.php">Enseignants</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/views/gestion_matieres.php">Gestion Matières</a></li>
                <?php if (!isLoggedIn()): ?>
                    <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/views/inscription.php">Inscription</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/views/connexion.php">Connexion</a></li>
                <?php endif; ?>
                <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/views/gestion_eleves.php">Gestion Élèves</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/views/import_eleves.php">Import Élèves</a></li>
                <?php if (isEnseignant()): ?>
                    <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/views/import_notes.php">Import Notes</a></li>
                <?php endif; ?>
                <?php if (isAdmin() || isDirecteur()): ?>
                    <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/views/telecharger_bulletins.php">Bulletins</a></li>
                <?php endif; ?>
                <?php if (isLoggedIn()): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL ?>/logout.php" style="color: #ff4444 !important;">
                            Déconnexion
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
<script src="<?= BASE_URL ?>/js/bootstrap.js"></script>
</body>
</html>
