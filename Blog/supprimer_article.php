<?php
session_start();
require_once 'includes/db.php';
// require_once 'includes/fonctions.php';
require_once 'includes/auth.php';

// Vérifie que l'utilisateur est admin
rediriger_si_non_admin();

// Vérifie qu'un ID est passé en GET
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: admin.php');
    exit;
}

$article_id = intval($_GET['id']);

// Supprimer l'article
$stmt = $conn->prepare("DELETE FROM articles WHERE id = ?");
$stmt->bind_param("i", $article_id);

if ($stmt->execute()) {
    $_SESSION['message'] = "Article supprimé avec succès.";
} else {
    $_SESSION['message'] = "Erreur lors de la suppression de l'article.";
}

// Redirection vers le tableau de bord admin
header('Location: admin.php');
exit;
