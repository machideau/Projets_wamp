<?php
require_once 'C:/wamp64/www/Ecole/demo/models/Utilisateur.php';
// require_once 'demo/models/Utilisateur.php';

class ConnexionController {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function handleRequest() {
        $utilisateur = new Utilisateur($this->db);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];
            $password = $_POST['password'];

            // Inscrire l'utilisateur
            $success = $utilisateur->loginUtilisateur($email, $password);
            if ($success) {
                header ('Location: views/inscription.php');
            } else {
                include 'views/connexion.php';
            }
        } else {
            include 'views/connexion.php';
        }
    }
}
?>