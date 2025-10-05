<?php
// includes/auth.php

// session_start();
require 'db.php';

// Pour les utilisateurs normaux
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function redirectIfNotLoggedIn() {
    if (!isLoggedIn()) {
        header('Location: login.php');
        exit;
    }
}

// Pour l'administrateur
function isAdminLoggedIn() {
    return isset($_SESSION['admin_id']);
}

function redirectIfNotAdmin() {
    if (!isAdminLoggedIn()) {
        header('Location: admin_login.php');
        exit;
    }
}
?>