<?php
// add_skill.php

session_start();
require 'includes/auth.php';
redirectIfNotAdmin(); // Vérifie si l'admin est connecté

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

require 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $skill_name = htmlspecialchars($_POST['skill_name']);
    $description = htmlspecialchars($_POST['description']);

    $stmt = $pdo->prepare("INSERT INTO skills (skill_name, description) VALUES (:skill_name, :description)");
    $stmt->execute(['skill_name' => $skill_name, 'description' => $description]);

    echo "Compétence ajoutée avec succès !";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter une compétence</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <h1>Ajouter une compétence</h1>
    <form action="add_skill.php" method="POST">
        <input type="text" name="skill_name" placeholder="Nom de la compétence" required>
        <textarea name="description" placeholder="Description"></textarea>
        <button type="submit">Ajouter</button>
    </form>
</body>
</html>