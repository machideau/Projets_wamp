<?php
require_once __DIR__ . '/../config/config.php';
require_once CONFIG_PATH . '/database.php';
require_once MODELS_PATH . '/Utilisateur.php';
require_once __DIR__ . '/../includes/auth.php';

// Vérifier que l'utilisateur est connecté et a le bon rôle
checkRole(['admin', 'directeur']);

$db = Database::getInstance();
$utilisateur = new Utilisateur($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    try {
        switch ($action) {
            case 'add':
                $nom_matiere = trim($_POST['nom_matiere']);
                $classe_id = $_POST['classe_id'];
                if (empty($nom_matiere)) {
                    throw new Exception("Le nom de la matière ne peut pas être vide.");
                }
                
                $success = $utilisateur->ajouterMatiere($nom_matiere, $classe_id);
                if ($success) {
                    $_SESSION['message'] = "La matière a été ajoutée avec succès.";
                    $_SESSION['message_type'] = "success";
                }
                break;

            case 'edit':
                $matiere_id = $_POST['matiere_id'];
                $nom_matiere = trim($_POST['nom_matiere']);
                $classe_id = $_POST['classe_id'];
                if (empty($nom_matiere)) {
                    throw new Exception("Le nom de la matière ne peut pas être vide.");
                }
                
                $success = $utilisateur->modifierMatiere($matiere_id, $nom_matiere, $classe_id);
                if ($success) {
                    $_SESSION['message'] = "La matière a été modifiée avec succès.";
                    $_SESSION['message_type'] = "success";
                }
                break;

            case 'delete':
                $matiere_id = $_POST['matiere_id'];
                $success = $utilisateur->supprimerMatiere($matiere_id);
                if ($success) {
                    $_SESSION['message'] = "La matière a été supprimée avec succès.";
                    $_SESSION['message_type'] = "success";
                }
                break;

            default:
                throw new Exception("Action non valide.");
        }
    } catch (Exception $e) {
        $_SESSION['message'] = $e->getMessage();
        $_SESSION['message_type'] = "danger";
    }
}

header('Location: gestion_matieres.php');
exit;
