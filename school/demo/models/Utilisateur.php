<?php
class Utilisateur {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Inscription d'un nouvel utilisateur
    public function inscrireUtilisateur($nom, $prenom, $email, $mot_de_passe, $telephone) {
        $hashed_password = password_hash($mot_de_passe, PASSWORD_BCRYPT);
        $query = "INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe, telephone) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$nom, $prenom, $email, $hashed_password, $telephone]);
    }

    // Inscription d'un nouvel utilisateur
    public function loginUtilisateur($email, $password) {
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        $query = "SELECT * FROM Utilisateurs WHERE email = ? AND mot_de_passe = ?";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$email, $hashed_password]);
    }

    // Associer un utilisateur à une classe
    public function associerClasse($utilisateur_id, $classe_id) {
        $query = "INSERT INTO enseignant_classes (utilisateur_id, classe_id) VALUES (?, ?)";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$utilisateur_id, $classe_id]);
    }

    // Associer un utilisateur à une matière
    public function associerMatiere($utilisateur_id, $matiere_id) {
        $query = "INSERT INTO enseignant_matieres (utilisateur_id, matiere_id) VALUES (?, ?)";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$utilisateur_id, $matiere_id]);
    }
 
    // Récupérer toutes les classes
    public function getClasses() {
        $query = "SELECT * FROM classes";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Récupérer les matières par classe
    public function getMatieresByClasse($classe_id) {
        $query = "SELECT m.id, m.nom_matiere 
                    FROM matieres m 
                    WHERE m.classe_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$classe_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // // Associer un utilisateur à une classe
    // public function associerClasse($utilisateur_id, $classe_id) {
    //     $query = "INSERT INTO enseignant_classes (utilisateur_id, classe_id) VALUES (?, ?)";
    //     $stmt = $this->db->prepare($query);
    //     return $stmt->execute([$utilisateur_id, $classe_id]);
    // }

    // // Associer un utilisateur à une matière
    // public function associerMatiere($utilisateur_id, $matiere_id) {
    //     $query = "INSERT INTO enseignant_matieres (utilisateur_id, matiere_id) VALUES (?, ?)";
    //     $stmt = $this->db->prepare($query);
    //     return $stmt->execute([$utilisateur_id, $matiere_id]);
    // }
}
?>