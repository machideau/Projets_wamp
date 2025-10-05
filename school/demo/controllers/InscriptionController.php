<?php
require_once 'models/Utilisateur.php';
require_once 'config/database.php';
// require_once 'demo/models/Utilisateur.php';

class InscriptionController {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function handleRequest() {
        $utilisateur = new Utilisateur($this->db);
        global $classes;
        $classes = $utilisateur->getClasses();  

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom = $_POST['nom'];
            $prenom = $_POST['prenom'];
            $email = $_POST['email'];
            $mot_de_passe = $_POST['mot_de_passe'];
            $telephone = $_POST['telephone'];
            $classe_id = $_POST['classe_id'];
            $matieres = $_POST['matieres'] ?? [];

            // Inscrire l'utilisateur
            $success = $utilisateur->inscrireUtilisateur($nom, $prenom, $email, $mot_de_passe, $telephone);
            if ($success) {
                $utilisateur_id = $this->db->lastInsertId();

                // Associer la classe
                $utilisateur->associerClasse($utilisateur_id, $classe_id);

                // Associer les matières
                foreach ($matieres as $matiere_id) {
                    $utilisateur->associerMatiere($utilisateur_id, $matiere_id);
                }

                header('Location: connexion.php');
            } else {
                echo "<script>alert('Erreur lors de l\'inscription.');</script>";
            }
            // echo 'no call';
        } else {
            // echo 'call';
            // global $classes;
            // $classes = $utilisateur->getClasses();
            // // var_dump($classes);
            // // include 'get_matieres.php';
            // include 'inscription.php';
        }
    }
    
}
?>