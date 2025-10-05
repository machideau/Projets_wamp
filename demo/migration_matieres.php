<?php
require_once __DIR__ . '/config/config.php';
require_once CONFIG_PATH . '/database.php';
require_once __DIR__ . '/includes/auth.php';

// Vérifier que l'utilisateur est connecté et a le bon rôle
checkRole(['admin', 'directeur']);

try {
    $db = Database::getInstance();
    
    echo "<h1>Migration des matières des enseignants</h1>";
    
    // Démarrer une transaction
    $db->beginTransaction();
    
    // Récupérer toutes les associations existantes dans enseignant_matieres_classes
    $query = "SELECT DISTINCT utilisateur_id, matiere_id FROM enseignant_matieres_classes";
    $stmt = $db->prepare($query);
    $stmt->execute();
    $associations = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $count = 0;
    
    // Pour chaque association, vérifier si elle existe déjà dans enseignant_matieres
    foreach ($associations as $association) {
        $utilisateur_id = $association['utilisateur_id'];
        $matiere_id = $association['matiere_id'];
        
        // Vérifier si l'association existe déjà
        $checkQuery = "SELECT COUNT(*) FROM enseignant_matieres 
                      WHERE utilisateur_id = ? AND matiere_id = ?";
        $checkStmt = $db->prepare($checkQuery);
        $checkStmt->execute([$utilisateur_id, $matiere_id]);
        $exists = $checkStmt->fetchColumn() > 0;
        
        // Si l'association n'existe pas, l'ajouter
        if (!$exists) {
            $insertQuery = "INSERT INTO enseignant_matieres (utilisateur_id, matiere_id) 
                           VALUES (?, ?)";
            $insertStmt = $db->prepare($insertQuery);
            $insertStmt->execute([$utilisateur_id, $matiere_id]);
            $count++;
        }
    }
    
    // Valider la transaction
    $db->commit();
    
    echo "<div style='padding: 20px; background-color: #d4edda; border-radius: 5px; margin-top: 20px;'>";
    echo "<p>Migration terminée avec succès!</p>";
    echo "<p>$count nouvelles associations ont été ajoutées à la table enseignant_matieres.</p>";
    echo "<p><a href='index.php'>Retour à l'accueil</a></p>";
    echo "</div>";
    
} catch (Exception $e) {
    // En cas d'erreur, annuler la transaction
    if (isset($db)) {
        $db->rollBack();
    }
    
    echo "<div style='padding: 20px; background-color: #f8d7da; border-radius: 5px; margin-top: 20px;'>";
    echo "<p>Erreur durant la migration: " . $e->getMessage() . "</p>";
    echo "<p><a href='index.php'>Retour à l'accueil</a></p>";
    echo "</div>";
}
?> 