<?php
require_once __DIR__ . '/../config/config.php';
require_once CONFIG_PATH . '/database.php';
require_once MODELS_PATH . '/Utilisateur.php';
require_once __DIR__ . '/../includes/auth.php';

// Vérifier que l'utilisateur est un enseignant
checkRole(['enseignant']);

try {
    if (!isset($_GET['classe_id'])) {
        throw new Exception('Classe ID non spécifié');
    }

    $db = Database::getInstance();
    $utilisateur = new Utilisateur($db);
    
    $classe_id = intval($_GET['classe_id']);
    $user_id = $_SESSION['user']['id'];
    
    // Récupérer les matières de l'enseignant pour cette classe spécifique
    $matieres = $utilisateur->getMatieresEnseignantParClasse($user_id, $classe_id);
    
    header('Content-Type: application/json');
    echo json_encode($matieres);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}