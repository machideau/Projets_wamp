<?php
require_once __DIR__ . '/../config/config.php';
require_once MODELS_PATH . '/Utilisateur.php';
require_once CONFIG_PATH . '/database.php';

class InscriptionController {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function handleRequest() {
        $utilisateur = new Utilisateur($this->db);
        $classes = $utilisateur->getClasses();  

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $nom = $_POST['nom'];
                $prenom = $_POST['prenom'];
                $email = $_POST['email'];
                $mot_de_passe = $_POST['mot_de_passe'];
                $telephone = $_POST['telephone'];
                $classes_ids = $_POST['classe_id'] ?? [];
                $matieres = $_POST['matieres'] ?? [];

                $this->db->beginTransaction();

                // Inscrire l'utilisateur
                $success = $utilisateur->inscrireUtilisateur($nom, $prenom, $email, $mot_de_passe, $telephone);
                if ($success) {
                    $utilisateur_id = $this->db->lastInsertId();

                    // Associer les classes et leurs matières respectives
                    foreach ($classes_ids as $classe_id) {
                        $utilisateur->associerClasse($utilisateur_id, $classe_id);

                        if (isset($matieres[$classe_id])) {
                            foreach ($matieres[$classe_id] as $matiere_id) {
                                // Associer la matière à l'enseignant (table enseignant_matieres)
                                $utilisateur->associerMatiere($utilisateur_id, $matiere_id);
                                
                                // Associer la matière-classe à l'enseignant (table enseignant_matieres_classes)
                                $utilisateur->associerMatiereClasse($utilisateur_id, $matiere_id, $classe_id);
                            }
                        }
                    }

                    $this->db->commit();
                    Flash::set('success', 'Inscription réussie ! Votre compte est en attente d\'approbation.');
                    header('Location: ' . BASE_URL . '/index.php?action=login');
                    exit;
                }
            } catch (Exception $e) {
                $this->db->rollBack();
                Flash::set('danger', $e->getMessage());
                header('Location: ' . BASE_URL . '/views/inscription.php');
                exit;
            }
        }
    }
}
?>
