<?php
require_once MODELS_PATH . '/Utilisateur.php';
require_once __DIR__ . '/../includes/utils.php';

class ConnexionController {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function handleRequest() {
        $utilisateur = new Utilisateur($this->db);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Vérification CSRF
            if (!CSRF::verifyToken($_POST['csrf_token'] ?? '')) {
                Flash::set('danger', 'Session invalide, veuillez réessayer');
                header('Location: ' . BASE_URL . '/index.php?action=login');
                exit;
            }

            // Validation des données
            $validator = new Validator();
            $rules = [
                'email' => ['required' => true, 'email' => true],
                'password' => ['required' => true, 'min' => 6]
            ];

            if (!$validator->validate($_POST, $rules)) {
                foreach ($validator->getErrors() as $error) {
                    Flash::set('danger', $error);
                }
                header('Location: ' . BASE_URL . '/index.php?action=login');
                exit;
            }

            $email = $_POST['email'];
            $password = $_POST['password'];

            Logger::log('info', 'Tentative de connexion', ['email' => $email]);

            try {
                if ($utilisateur->loginUtilisateur($email, $password)) {
                    Logger::log('info', 'Connexion réussie', [
                        'email' => $email,
                        'role' => $_SESSION['user']['role']
                    ]);

                    Flash::set('success', 'Connexion réussie !');
                    
                    switch ($_SESSION['user']['role']) {
                        case 'admin':
                        case 'directeur':
                            header('Location: ' . BASE_URL . '/views/enseignants.php');
                            break;
                        case 'enseignant':
                            if ($_SESSION['user']['statut'] === 'approved') {
                                header('Location: ' . BASE_URL . '/views/enseignant/dashboard.php');
                            } else {
                                Flash::set('warning', 'Votre compte est en attente d\'approbation.');
                                header('Location: ' . BASE_URL . '/index.php?action=login');
                            }
                            break;
                        default:
                            Flash::set('danger', 'Rôle non reconnu');
                            header('Location: ' . BASE_URL . '/index.php?action=login');
                    }
                    exit;
                }
            } catch (Exception $e) {
                Logger::log('warning', 'Échec de connexion', ['email' => $email, 'error' => $e->getMessage()]);
                Flash::set('danger', $e->getMessage());
                header('Location: ' . BASE_URL . '/index.php?action=login');
                exit;
            }
        }
    }
}
?>
