<?php
require_once __DIR__ . '/../config/config.php';
require_once CONFIG_PATH . '/database.php';
require_once MODELS_PATH . '/Utilisateur.php';
require_once __DIR__ . '/../includes/auth.php';

checkRole(['admin', 'directeur']);

$db = Database::getInstance();
$utilisateur = new Utilisateur($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    try {
        switch ($action) {
            case 'add':
                $utilisateur->ajouterEleve(
                    $_POST['nom'],
                    $_POST['prenom'],
                    $_POST['email'],
                    $_POST['date_naissance'],
                    $_POST['classe_id']
                );
                $_SESSION['message'] = "L'élève a été ajouté avec succès.";
                $_SESSION['message_type'] = "success";
                break;
                
            case 'edit':
                $utilisateur->modifierEleve(
                    $_POST['id'],
                    $_POST['nom'],
                    $_POST['prenom'],
                    $_POST['email'],
                    $_POST['date_naissance'],
                    $_POST['classe_id']
                );
                $_SESSION['message'] = "L'élève a été modifié avec succès.";
                $_SESSION['message_type'] = "success";
                break;
        }
    } catch (Exception $e) {
        $_SESSION['message'] = "Erreur : " . $e->getMessage();
        $_SESSION['message_type'] = "danger";
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'delete') {
    try {
        $utilisateur->supprimerEleve($_GET['id']);
        $_SESSION['message'] = "L'élève a été supprimé avec succès.";
        $_SESSION['message_type'] = "success";
    } catch (Exception $e) {
        $_SESSION['message'] = "Erreur lors de la suppression : " . $e->getMessage();
        $_SESSION['message_type'] = "danger";
    }
}

header('Location: gestion_eleves.php');
exit;