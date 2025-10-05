<?php
require_once __DIR__ . '/config/config.php';
require_once CONFIG_PATH . '/database.php';
require_once CONTROLLERS_PATH . '/InscriptionController.php';
require_once CONTROLLERS_PATH . '/ConnexionController.php';

$db = Database::getInstance();

$action = $_GET['action'] ?? 'login';

try {
    switch ($action) {
        case 'inscription':
            $controller = new InscriptionController($db);
            $controller->handleRequest();
            break;
        case 'login':
            $controller = new ConnexionController($db);
            $controller->handleRequest();
            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                include VIEWS_PATH . '/connexion.php';
            }
            break;
        default:
            include VIEWS_PATH . '/connexion.php';
            break;
    }
} catch (Exception $e) {
    $_SESSION['error'] = $e->getMessage();
    header('Location: ' . BASE_URL . '/views/error.php');
    exit;
}
?>
