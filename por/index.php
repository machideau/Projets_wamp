<?php
// index.php

require 'includes/db.php';
require 'includes/admin.php';

$skills = getSkills();
$projects = getProjects();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Portfolio de SAM-LE-DEV</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <h1>Bienvenue sur le portfolio de SAM-LE-DEV</h1>

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

    <a href="login.php">Se connecter</a>
</body>
</html>