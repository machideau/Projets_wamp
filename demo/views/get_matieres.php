<?php
require_once __DIR__ . '/../config/config.php';
require_once CONFIG_PATH . '/database.php';
require_once MODELS_PATH . '/Utilisateur.php';

header('Content-Type: application/json');

if (!isset($_GET['classe_id'])) {
    http_response_code(400);
    echo json_encode(['error' => 'classe_id manquant']);
    exit;
}

try {
    $db = Database::getInstance();
    $utilisateur = new Utilisateur($db);
    $classe_id = $_GET['classe_id'];
    
    // Récupérer toutes les matières pour cette classe
    $matieres = $utilisateur->getMatieresByClasse($classe_id);
    
    // Récupérer les matières déjà prises par d'autres enseignants
    $matieresPrises = $utilisateur->getMatieresDejaPrises($classe_id);
    
    // Identifiants des matières déjà prises
    $idsMatieresPrises = array_column($matieresPrises, 'matiere_id');
    
    // Ajouter l'information sur les matières déjà prises
    foreach ($matieres as &$matiere) {
        $matiere['deja_prise'] = in_array($matiere['id'], $idsMatieresPrises);
        
        // Ajouter le nom de l'enseignant qui a pris cette matière
        if ($matiere['deja_prise']) {
            foreach ($matieresPrises as $mp) {
                if ($mp['matiere_id'] == $matiere['id']) {
                    $matiere['enseignant'] = $mp['prenom'] . ' ' . $mp['nom'];
                    break;
                }
            }
        }
    }
    
    echo json_encode($matieres);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>
