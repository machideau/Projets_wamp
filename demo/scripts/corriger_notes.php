<?php
require_once __DIR__ . '/../config/config.php';
require_once CONFIG_PATH . '/database.php';

try {
    $db = Database::getInstance();
    
    // Créer une sauvegarde de la table notes
    $db->exec("CREATE TABLE notes_backup AS SELECT * FROM notes");
    
    // Début de la transaction
    $db->beginTransaction();
    
    // Récupérer toutes les notes
    $query = "SELECT * FROM notes";
    $stmt = $db->prepare($query);
    $stmt->execute();
    $notes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Vider la table notes
    $db->exec("TRUNCATE TABLE notes");
    
    // Préparer la requête d'insertion
    $insertQuery = "INSERT INTO notes (eleve_id, matiere_id, note_classe, note_devoir, note_composition, trimestre) 
                   VALUES (?, ?, ?, ?, ?, ?)";
    $insertStmt = $db->prepare($insertQuery);
    
    // Récupérer tous les élèves avec leur email et ID
    $queryEleves = "SELECT id, email FROM eleves";
    $stmtEleves = $db->prepare($queryEleves);
    $stmtEleves->execute();
    $eleves = $stmtEleves->fetchAll(PDO::FETCH_KEY_PAIR); // email => id
    
    $correctionCount = 0;
    $erreurs = [];
    
    // Pour chaque note
    foreach ($notes as $note) {
        // Si l'eleve_id est un email
        if (filter_var($note['eleve_id'], FILTER_VALIDATE_EMAIL)) {
            if (isset($eleves[$note['eleve_id']])) {
                // Remplacer l'email par l'ID réel de l'élève
                $insertStmt->execute([
                    $eleves[$note['eleve_id']],
                    $note['matiere_id'],
                    $note['note_classe'],
                    $note['note_devoir'],
                    $note['note_composition'],
                    $note['trimestre']
                ]);
                $correctionCount++;
            } else {
                $erreurs[] = "Email non trouvé : " . $note['eleve_id'];
            }
        } else {
            // Si c'est déjà un ID, vérifier qu'il existe
            $checkEleve = "SELECT id FROM eleves WHERE id = ?";
            $stmtCheck = $db->prepare($checkEleve);
            $stmtCheck->execute([$note['eleve_id']]);
            
            if ($stmtCheck->fetch()) {
                // Réinsérer la note telle quelle
                $insertStmt->execute([
                    $note['eleve_id'],
                    $note['matiere_id'],
                    $note['note_classe'],
                    $note['note_devoir'],
                    $note['note_composition'],
                    $note['trimestre']
                ]);
                $correctionCount++;
            } else {
                $erreurs[] = "ID élève non trouvé : " . $note['eleve_id'];
            }
        }
    }
    
    // Valider la transaction
    $db->commit();
    
    echo "Correction terminée.\n";
    echo "Nombre de notes corrigées : $correctionCount\n";
    
    if (!empty($erreurs)) {
        echo "\nErreurs rencontrées :\n";
        foreach ($erreurs as $erreur) {
            echo "- $erreur\n";
        }
    }
    
    echo "\nUne sauvegarde a été créée dans la table 'notes_backup'\n";
    
} catch (Exception $e) {
    // En cas d'erreur, annuler la transaction
    if ($db->inTransaction()) {
        $db->rollBack();
    }
    echo "Erreur : " . $e->getMessage() . "\n";
}