<?php
require_once __DIR__ . '/../config/database.php';

class User {
    private $db;
    
    public function __construct() {
        $this->db = connectDB();
    }
    
    public function create($name, $email, $password, $profile_photo = 'images/default-avatar.webp') {
        try {
            // Vérifier si l'email existe déjà
            if ($this->findByEmail($email)) {
                throw new Exception('Cet email est déjà utilisé');
            }
            
            // Hash du mot de passe
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            
            // Préparation de la requête
            $query = "INSERT INTO users (name, email, password, profile_photo) VALUES (:name, :email, :password, :profile_photo)";
            $stmt = $this->db->prepare($query);
            
            // Exécution de la requête
            $success = $stmt->execute([
                ':name' => $name,
                ':email' => $email,
                ':password' => $hashedPassword,
                ':profile_photo' => $profile_photo
            ]);
            
            if ($success) {
                return $this->db->lastInsertId();
            }
            
            return false;
            
        } catch (PDOException $e) {
            error_log("Erreur lors de la création de l'utilisateur: " . $e->getMessage());
            throw new Exception("Une erreur est survenue lors de la création du compte");
        }
    }
    
    public function login($email, $password) {
        try {
            $query = "SELECT * FROM users WHERE email = :email LIMIT 1";
            $stmt = $this->db->prepare($query);
            $stmt->execute([':email' => $email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$user || !password_verify($password, $user['password'])) {
                throw new Exception('Email ou mot de passe incorrect');
            }
            
            // Ne pas renvoyer le mot de passe
            unset($user['password']);
            return $user;
            
        } catch (PDOException $e) {
            error_log("Erreur lors de la connexion: " . $e->getMessage());
            throw new Exception("Une erreur est survenue lors de la connexion");
        }
    }

    public function initiatePasswordReset($email) {
        try {
            $user = $this->findByEmail($email);
            if (!$user) {
                return false;
            }

            $token = bin2hex(random_bytes(32));
            $expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));

            $query = "UPDATE users SET reset_token = :token, reset_token_expiry = :expiry WHERE email = :email";
            $stmt = $this->db->prepare($query);
            return $stmt->execute([
                ':token' => $token,
                ':expiry' => $expiry,
                ':email' => $email
            ]);
        } catch (PDOException $e) {
            error_log("Erreur lors de la réinitialisation: " . $e->getMessage());
            throw new Exception("Une erreur est survenue");
        }
    }

    public function resetPassword($token, $newPassword) {
        try {
            $query = "SELECT * FROM users WHERE reset_token = :token AND reset_token_expiry > NOW() LIMIT 1";
            $stmt = $this->db->prepare($query);
            $stmt->execute([':token' => $token]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$user) {
                throw new Exception('Token invalide ou expiré');
            }

            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $updateQuery = "UPDATE users SET password = :password, reset_token = NULL, reset_token_expiry = NULL WHERE id = :id";
            $updateStmt = $this->db->prepare($updateQuery);
            return $updateStmt->execute([
                ':password' => $hashedPassword,
                ':id' => $user['id']
            ]);
        } catch (PDOException $e) {
            error_log("Erreur lors de la réinitialisation du mot de passe: " . $e->getMessage());
            throw new Exception("Une erreur est survenue");
        }
    }

    public function findByEmail($email) {
        try {
            $query = "SELECT * FROM users WHERE email = :email LIMIT 1";
            $stmt = $this->db->prepare($query);
            $stmt->execute([':email' => $email]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur lors de la recherche par email: " . $e->getMessage());
            return false;
        }
    }
}
