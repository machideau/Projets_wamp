<?php
require_once __DIR__ . '/../config/config.php';
require_once CONFIG_PATH . '/database.php';
require_once MODELS_PATH . '/Utilisateur.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $db = Database::getInstance();
    $utilisateur = new Utilisateur($db);
    
    $enseignant_id = $_POST['enseignant_id'];
    $action = $_POST['action'];
    
    $statut = ($action === 'approve') ? 'approved' : 'rejected';
    
    $query = "UPDATE utilisateurs SET statut = ? WHERE id = ?";
    $stmt = $db->prepare($query);
    $stmt->execute([$statut, $enseignant_id]);
    
    header('Location: enseignants.php');
    exit;
}