<?php
function checkRole($allowed_roles) {
    // Vérifier si l'utilisateur est connecté
    if (!isset($_SESSION['user'])) {
        $_SESSION['error'] = "Vous devez vous connecter pour accéder à cette page.";
        header('Location: ' . BASE_URL . '/index.php?action=login');
        exit;
    }

    // Vérifier si le rôle de l'utilisateur est autorisé
    if (!in_array($_SESSION['user']['role'], $allowed_roles)) {
        $_SESSION['error'] = "Vous n'avez pas les droits nécessaires pour accéder à cette page.";
        header('Location: ' . BASE_URL . '/index.php?action=login');
        exit;
    }
}

function isLoggedIn() {
    return isset($_SESSION['user']);
}

function getCurrentUserRole() {
    return $_SESSION['user']['role'] ?? null;
}

function isEnseignant() {
    return isset($_SESSION['user']) && $_SESSION['user']['role'] === 'enseignant';
}

function isAdmin() {
    return isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin';
}

function isDirecteur() {
    return isset($_SESSION['user']) && $_SESSION['user']['role'] === 'directeur';
}
?>





