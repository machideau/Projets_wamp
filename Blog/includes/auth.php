<?php
// includes/auth.php

session_start();

function est_connecte() {
    return isset($_SESSION['utilisateur']);
}

function est_admin() {
    return est_connecte() && $_SESSION['utilisateur']['role'] === 'admin';
}

function rediriger_si_non_connecte() {
    if (!est_connecte()) {
        header('Location: /login.php');
        exit;
    }
}

function rediriger_si_non_admin() {
    if (!est_admin()) {
        header('Location: /index.php');
        exit;
    }
}
?>
