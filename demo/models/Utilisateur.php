<?php
class Utilisateur {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Authentification
    public function loginUtilisateur($email, $password) {
        // Vérifier d'abord si l'email existe
        if (!$this->emailExists($email)) {
            throw new Exception("Cet email n'existe pas dans notre base de données.");
        }

        $query = "SELECT u.*, r.nom as role_nom 
                  FROM utilisateurs u 
                  JOIN roles r ON u.role_id = r.id 
                  WHERE u.email = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['mot_de_passe'])) {
            $_SESSION['user'] = [
                'id' => $user['id'],
                'nom' => $user['nom'],
                'prenom' => $user['prenom'],
                'email' => $user['email'],
                'role' => $user['role_nom'],
                'statut' => $user['statut'] ?? 'pending'
            ];
            return true;
        }
        throw new Exception("Mot de passe incorrect");
    }

    // Gestion des utilisateurs
    public function inscrireUtilisateur($nom, $prenom, $email, $mot_de_passe, $telephone) {
        // Vérifier si l'email existe déjà
        if ($this->emailExists($email)) {
            throw new Exception("Cet email est déjà utilisé. Veuillez en choisir un autre.");
        }

        $hashed_password = password_hash($mot_de_passe, PASSWORD_BCRYPT);
        
        $query = "SELECT id FROM roles WHERE nom = 'enseignant'";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $role = $stmt->fetch(PDO::FETCH_ASSOC);
        $role_id = $role['id'];

        $query = "INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe, telephone, role_id) 
                 VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$nom, $prenom, $email, $hashed_password, $telephone, $role_id]);
    }

    public function getUtilisateurById($id) {
        $query = "SELECT u.*, r.nom as role_nom 
                 FROM utilisateurs u 
                 JOIN roles r ON u.role_id = r.id 
                 WHERE u.id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAllEnseignants() {
        $query = "SELECT u.*, r.nom as role_nom
                 FROM utilisateurs u
                 JOIN roles r ON u.role_id = r.id
                 WHERE r.nom = 'enseignant'
                 ORDER BY u.date_inscription DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Gestion des classes
    public function getClasses() {
        $query = "SELECT id, nom_classe FROM classes ORDER BY nom_classe";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getClasseById($classe_id) {
        $query = "SELECT * FROM classes WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$classe_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getNombreElevesParClasse($classe_id) {
        $query = "SELECT COUNT(*) as count FROM eleves WHERE classe_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$classe_id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'];
    }

    // Gestion des élèves
    public function ajouterEleve($nom, $prenom, $email, $date_naissance, $classe_id) {
        // Essayer de convertir la date dans le bon format
        $date_formatee = $this->convertirFormatDate($date_naissance);
        
        if (!$date_formatee) {
            throw new Exception("Format de date invalide. Utilisez JJ/MM/AAAA ou AAAA-MM-JJ");
        }

        $query = "INSERT INTO eleves (nom, prenom, email, date_naissance, classe_id) 
                 VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$nom, $prenom, $email, $date_formatee, $classe_id]);
    }

    public function modifierEleve($id, $nom, $prenom, $email, $date_naissance, $classe_id) {
        $query = "UPDATE eleves 
                 SET nom = ?, prenom = ?, email = ?, date_naissance = ?, classe_id = ? 
                 WHERE id = ?";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$nom, $prenom, $email, $date_naissance, $classe_id, $id]);
    }

    public function getElevesByClasse($classe_id) {
        $query = "SELECT * FROM eleves WHERE classe_id = ? ORDER BY nom, prenom";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$classe_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getEleveByEmail($email) {
        $query = "SELECT * FROM eleves WHERE email = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Gestion des matières
    public function getMatieresEnseignant($enseignant_id) {
        $query = "SELECT DISTINCT m.id, m.nom_matiere 
                 FROM matieres m 
                 JOIN enseignant_matieres em ON m.id = em.matiere_id 
                 WHERE em.utilisateur_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$enseignant_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getMatieresDejaPrises($classe_id) {
        try {
            $query = "SELECT DISTINCT 
                        em.matiere_id,
                        u.nom,
                        u.prenom 
                     FROM enseignant_matieres em
                     JOIN utilisateurs u ON em.utilisateur_id = u.id
                     JOIN enseignant_classes ec ON u.id = ec.utilisateur_id
                     WHERE ec.classe_id = ?";
            
            $stmt = $this->db->prepare($query);
            $stmt->execute([$classe_id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur dans getMatieresDejaPrises: " . $e->getMessage());
            throw new Exception("Erreur lors de la récupération des matières prises");
        }
    }

    public function getMatieresByClasse($classe_id) {
        try {
            $query = "SELECT DISTINCT m.id, m.nom_matiere
                     FROM matieres m
                     WHERE m.classe_id = ?";
            
            $stmt = $this->db->prepare($query);
            $stmt->execute([$classe_id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur dans getMatieresByClasse: " . $e->getMessage());
            throw new Exception("Erreur lors de la récupération des matières");
        }
    }

    public function getMatieresEnseignantParClasse($user_id, $classe_id) {
        $query = "SELECT DISTINCT m.id, m.nom_matiere
                 FROM matieres m
                 JOIN enseignant_matieres em ON m.id = em.matiere_id
                 JOIN enseignant_classes ec ON em.utilisateur_id = ec.utilisateur_id
                 JOIN enseignant_matieres_classes emc ON (em.utilisateur_id = emc.utilisateur_id 
                                                 AND m.id = emc.matiere_id 
                                                 AND ec.classe_id = emc.classe_id)
                 WHERE em.utilisateur_id = ? 
                 AND ec.classe_id = ?
                 ORDER BY m.nom_matiere";
                 
        $stmt = $this->db->prepare($query);
        $stmt->execute([$user_id, $classe_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Gestion des associations
    public function associerClasse($utilisateur_id, $classe_id) {
        $query = "INSERT INTO enseignant_classes (utilisateur_id, classe_id) VALUES (?, ?)";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$utilisateur_id, $classe_id]);
    }

    public function associerMatiere($utilisateur_id, $matiere_id) {
        $query = "INSERT INTO enseignant_matieres (utilisateur_id, matiere_id) VALUES (?, ?)";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$utilisateur_id, $matiere_id]);
    }

    public function associerMatiereClasse($utilisateur_id, $matiere_id, $classe_id) {
        try {
            // Vérifier si l'association n'existe pas déjà
            $check_query = "SELECT COUNT(*) FROM enseignant_matieres_classes 
                           WHERE utilisateur_id = ? AND matiere_id = ? AND classe_id = ?";
            $check_stmt = $this->db->prepare($check_query);
            $check_stmt->execute([$utilisateur_id, $matiere_id, $classe_id]);
            
            if ($check_stmt->fetchColumn() == 0) {
                $query = "INSERT INTO enseignant_matieres_classes 
                         (utilisateur_id, matiere_id, classe_id) 
                         VALUES (?, ?, ?)";
                $stmt = $this->db->prepare($query);
                return $stmt->execute([$utilisateur_id, $matiere_id, $classe_id]);
            }
            return true;
        } catch (PDOException $e) {
            error_log("Erreur dans associerMatiereClasse: " . $e->getMessage());
            throw new Exception("Erreur lors de l'association matière-classe");
        }
    }

    // Gestion des notes
    public function ajouterNotes($eleve_id, $matiere_id, $note_classe, $note_devoir, $note_composition, $trimestre) {
        $query = "INSERT INTO notes (eleve_id, matiere_id, note_classe, note_devoir, note_composition, trimestre) 
                 VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$eleve_id, $matiere_id, $note_classe, $note_devoir, $note_composition, $trimestre]);
    }

    public function getNotesEleve($eleve_id, $trimestre) {
        $query = "SELECT n.*, m.nom_matiere 
                 FROM notes n 
                 JOIN matieres m ON n.matiere_id = m.id 
                 WHERE n.eleve_id = ? AND n.trimestre = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$eleve_id, $trimestre]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getNotesByEmail($email, $trimestre) {
        try {
            // Vérifier d'abord si l'élève existe avec cet email
            $query_eleve = "SELECT id FROM eleves WHERE email = ?";
            $stmt_eleve = $this->db->prepare($query_eleve);
            $stmt_eleve->execute([$email]);
            $eleve = $stmt_eleve->fetch(PDO::FETCH_ASSOC);
            
            if (!$eleve) {
                error_log("Aucun élève trouvé avec l'email: $email");
                return [];
            }
            
            $eleve_id = $eleve['id'];
            
            // Utiliser directement l'ID de l'élève pour chercher les notes
            $query = "SELECT n.*, m.nom_matiere 
                     FROM notes n 
                     JOIN matieres m ON n.matiere_id = m.id
                     WHERE n.eleve_id = ? AND n.trimestre = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$eleve_id, $trimestre]);
            $notes = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            error_log("Notes récupérées pour l'élève (email: $email, id: $eleve_id): " . count($notes));
            return $notes;
        } catch (Exception $e) {
            error_log("Erreur dans getNotesByEmail: " . $e->getMessage());
            return [];
        }
    }

    public function getMoyennesEleve($eleve_id, $trimestre) {
        $query = "SELECT 
                    m.nom_matiere,
                    AVG((note_classe + note_devoir + note_composition) / 3) as moyenne
                 FROM notes n 
                 JOIN matieres m ON n.matiere_id = m.id 
                 WHERE n.eleve_id = ? AND n.trimestre = ?
                 GROUP BY m.id, m.nom_matiere";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$eleve_id, $trimestre]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Vérifications et autorisations
    public function peutNoterClasseMatiere($enseignant_id, $classe_id, $matiere_id) {
        $query = "SELECT COUNT(*) as count 
                 FROM enseignant_classes ec
                 JOIN enseignant_matieres em ON ec.utilisateur_id = em.utilisateur_id
                 WHERE ec.utilisateur_id = ? 
                 AND ec.classe_id = ? 
                 AND em.matiere_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$enseignant_id, $classe_id, $matiere_id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'] > 0;
    }

    public function getClassesEnseignant($enseignant_id) {
        $query = "SELECT DISTINCT c.id, c.nom_classe 
                 FROM classes c 
                 JOIN enseignant_classes ec ON c.id = ec.classe_id 
                 WHERE ec.utilisateur_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$enseignant_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Gestion des statuts
    public function updateStatut($utilisateur_id, $nouveau_statut) {
        $query = "UPDATE utilisateurs SET statut = ? WHERE id = ?";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$nouveau_statut, $utilisateur_id]);
    }

    public function supprimerMatiere($matiere_id) {
        try {
            if (!$this->canDeleteMatiere($matiere_id)) {
                throw new Exception("Impossible de supprimer la matière car elle est utilisée par des enseignants ou des classes.");
            }

            $this->db->beginTransaction();

            // Supprimer les références dans enseignant_matieres_classes
            $delete_emc = "DELETE FROM enseignant_matieres_classes WHERE matiere_id = ?";
            $stmt_emc = $this->db->prepare($delete_emc);
            $stmt_emc->execute([$matiere_id]);

            // Supprimer les références dans enseignant_matieres
            $delete_em = "DELETE FROM enseignant_matieres WHERE matiere_id = ?";
            $stmt_em = $this->db->prepare($delete_em);
            $stmt_em->execute([$matiere_id]);

            // Finalement, supprimer la matière
            $query = "DELETE FROM matieres WHERE id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$matiere_id]);

            $this->db->commit();
            return true;

        } catch (PDOException $e) {
            $this->db->rollBack();
            error_log("Erreur lors de la suppression de la matière : " . $e->getMessage());
            throw new Exception("Une erreur est survenue lors de la suppression de la matière.");
        }
    }

    public function ajouterMatiere($nom_matiere, $classe_id) {
        try {
            $query = "INSERT INTO matieres (nom_matiere, classe_id) VALUES (?, ?)";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$nom_matiere, $classe_id]);
            return true;
        } catch (PDOException $e) {
            error_log("Erreur lors de l'ajout de la matière : " . $e->getMessage());
            throw new Exception("Impossible d'ajouter la matière.");
        }
    }

    public function modifierMatiere($matiere_id, $nom_matiere, $classe_id) {
        try {
            $query = "UPDATE matieres SET nom_matiere = ?, classe_id = ? WHERE id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$nom_matiere, $classe_id, $matiere_id]);
            
            if ($stmt->rowCount() === 0) {
                throw new Exception("La matière n'existe pas.");
            }
            return true;
        } catch (PDOException $e) {
            error_log("Erreur lors de la modification de la matière : " . $e->getMessage());
            throw new Exception("Impossible de modifier la matière.");
        }
    }

    private function convertirFormatDate($date) {
        // Supprimer les espaces éventuels
        $date = trim($date);
        
        // Formats possibles
        $formats = [
            'd/m/Y',    // 31/12/2023
            'd-m-Y',    // 31-12-2023
            'Y-m-d',    // 2023-12-31
            'd.m.Y',    // 31.12.2023
            'Y/m/d'     // 2023/12/31
        ];
        
        foreach ($formats as $format) {
            $d = DateTime::createFromFormat($format, $date);
            if ($d && $d->format($format) === $date) {
                return $d->format('Y-m-d');
            }
        }
        
        return false;
    }

    private function canDeleteMatiere($matiere_id) {
        try {
            // Vérifier les références dans enseignant_matieres_classes
            $query_emc = "SELECT COUNT(*) FROM enseignant_matieres_classes WHERE matiere_id = ?";
            $stmt_emc = $this->db->prepare($query_emc);
            $stmt_emc->execute([$matiere_id]);
            if ($stmt_emc->fetchColumn() > 0) {
                return false;
            }

            // Vérifier les références dans enseignant_matieres
            $query_em = "SELECT COUNT(*) FROM enseignant_matieres WHERE matiere_id = ?";
            $stmt_em = $this->db->prepare($query_em);
            $stmt_em->execute([$matiere_id]);
            if ($stmt_em->fetchColumn() > 0) {
                return false;
            }

            return true;
        } catch (PDOException $e) {
            error_log("Erreur lors de la vérification des références de la matière : " . $e->getMessage());
            return false;
        }
    }

    public function emailExists($email) {
        $query = "SELECT COUNT(*) FROM utilisateurs WHERE email = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$email]);
        return $stmt->fetchColumn() > 0;
    }
}
?>
