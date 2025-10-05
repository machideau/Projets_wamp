<?php
require_once __DIR__ . '/../config/database.php';

class Message {
    private $db;
    
    public function __construct() {
        $this->db = connectDB();
    }
    
    public function create($name, $email, $subject, $message) {
        try {
            $query = "INSERT INTO messages (name, email, subject, message) 
                     VALUES (:name, :email, :subject, :message)";
            $stmt = $this->db->prepare($query);
            
            return $stmt->execute([
                ':name' => htmlspecialchars($name),
                ':email' => filter_var($email, FILTER_SANITIZE_EMAIL),
                ':subject' => htmlspecialchars($subject),
                ':message' => htmlspecialchars($message)
            ]);
        } catch (PDOException $e) {
            error_log("Erreur lors de l'envoi du message: " . $e->getMessage());
            throw new Exception("Une erreur est survenue lors de l'envoi du message");
        }
    }
    
    public function getAllMessages() {
        try {
            $query = "SELECT * FROM messages ORDER BY created_at DESC";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération des messages: " . $e->getMessage());
            throw new Exception("Une erreur est survenue");
        }
    }
    
    public function markAsRead($id) {
        try {
            $query = "UPDATE messages SET status = 'read' WHERE id = :id";
            $stmt = $this->db->prepare($query);
            return $stmt->execute([':id' => $id]);
        } catch (PDOException $e) {
            error_log("Erreur lors du marquage du message: " . $e->getMessage());
            throw new Exception("Une erreur est survenue");
        }
    }
    
    public function deleteMessage($id) {
        try {
            $query = "DELETE FROM messages WHERE id = :id";
            $stmt = $this->db->prepare($query);
            return $stmt->execute([':id' => $id]);
        } catch (PDOException $e) {
            error_log("Erreur lors de la suppression du message: " . $e->getMessage());
            throw new Exception("Une erreur est survenue");
        }
    }
} 