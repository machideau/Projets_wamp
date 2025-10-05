<?php
// dashboard.php

require 'includes/auth.php';
redirectIfNotLoggedIn();

require 'includes/db.php';
require 'includes/admin.php';

// Récupérer les compétences et projets
$skills = getSkills();
$projects = getProjects();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Tableau de bord - SAM-LE-DEV</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <!-- Barre de navigation -->
    <nav style="background-color: #00BFFF; padding: 10px; text-align: center;">
        <a href="dashboard.php" style="color: white; margin: 0 10px; text-decoration: none;">Accueil</a>
        <a href="add_skill.php" style="color: white; margin: 0 10px; text-decoration: none;">Ajouter une compétence</a>
        <a href="add_project.php" style="color: white; margin: 0 10px; text-decoration: none;">Ajouter un projet</a>
        <a href="logout.php" style="color: white; margin: 0 10px; text-decoration: none;">Déconnexion</a>
    </nav>

    <h1>Tableau de bord</h1>

    <section id="skills">
        <h2>Compétences</h2>
        <ul>
            <?php foreach ($skills as $skill): ?>
                <li><?= htmlspecialchars($skill['skill_name']) ?> - <?= htmlspecialchars($skill['description']) ?></li>
            <?php endforeach; ?>
        </ul>
    </section>

    <section id="projects">
        <h2>Projets</h2>
        <div class="gallery">
            <?php foreach ($projects as $project): ?>
                <div class="project">
                    <img src="<?= htmlspecialchars($project['image_url']) ?>" alt="<?= htmlspecialchars($project['title']) ?>">
                    <p><?= htmlspecialchars($project['description']) ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
</body>
</html>