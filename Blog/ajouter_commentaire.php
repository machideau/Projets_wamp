<?php
require_once 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $article_id = intval($_POST['article_id']);
    $auteur = trim($_POST['auteur']);
    $contenu = trim($_POST['contenu']);

    if (!empty($auteur) && !empty($contenu)) {
        $stmt = $conn->prepare("INSERT INTO commentaires (article_id, auteur, contenu) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $article_id, $auteur, $contenu);
        $stmt->execute();
    }
    header("Location: article.php?id=$article_id");
    exit;
} else {
    echo "Requête invalide.";
}
