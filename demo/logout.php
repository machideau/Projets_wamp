<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/utils.php';

// Détruire la session
session_start();
session_destroy();

// Message de confirmation
Flash::set('success', 'Vous avez été déconnecté avec succès.');

// Redirection vers la page de connexion
header('Location: ' . BASE_URL . '/index.php?action=login');
exit;