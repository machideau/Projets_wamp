<?php
// add_project.php

session_start();
require 'includes/auth.php';
redirectIfNotAdmin(); // Vérifie si l'admin est connecté
require 'includes/db.php';
require 'includes/admin.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = htmlspecialchars($_POST['title']);
    $description = htmlspecialchars($_POST['description']);
    $image_url = htmlspecialchars($_POST['image_url']);
    $project_link = htmlspecialchars($_POST['project_link']);

    // Ajouter le projet à la base de données
    addProject($title, $description, $image_url, $project_link);
    echo "Projet ajouté avec succès !";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter un projet</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <!-- Barre de navigation Admin -->
    <nav style="background-color: #00BFFF; padding: 10px; text-align: center;">
        <a href="admin_dashboard.php" style="color: white; margin: 0 10px; text-decoration: none;">Accueil Admin</a>
        <a href="add_skill.php" style="color: white; margin: 0 10px; text-decoration: none;">Ajouter une compétence</a>
        <a href="add_project.php" style="color: white; margin: 0 10px; text-decoration: none;">Ajouter un projet</a>
        <a href="admin_logout.php" style="color: white; margin: 0 10px; text-decoration: none;">Déconnexion Admin</a>
    </nav>

    <h1>Ajouter un projet</h1>
    <form action="add_project.php" method="POST">
        <input type="text" name="title" placeholder="Titre du projet" required>
        <textarea name="description" placeholder="Description"></textarea>
        <input type="url" name="image_url" placeholder="URL de l'image">
        <input type="url" name="project_link" placeholder="Lien du projet">
        <button type="submit">Ajouter</button>
    </form>
</body>
</html>