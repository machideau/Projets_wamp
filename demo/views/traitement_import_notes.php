<?php
require_once __DIR__ . '/../config/config.php';
require_once CONFIG_PATH . '/database.php';
require_once MODELS_PATH . '/Utilisateur.php';
require_once __DIR__ . '/../includes/auth.php';

checkRole(['enseignant']);

$db = Database::getInstance();
$utilisateur = new Utilisateur($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (!isset($_FILES['csvFile']) || $_FILES['csvFile']['error'] !== UPLOAD_ERR_OK) {
            throw new Exception("Erreur lors du téléchargement du fichier");
        }

        $classe_id = $_POST['classe_id'];
        $matiere_id = $_POST['matiere_id'];

        // Vérifier que l'enseignant a le droit d'ajouter des notes pour cette classe et cette matière
        if (!$utilisateur->peutNoterClasseMatiere($_SESSION['user']['id'], $classe_id, $matiere_id)) {
            throw new Exception("Vous n'êtes pas autorisé à ajouter des notes pour cette classe et cette matière");
        }

        $filename = $_FILES['csvFile']['tmp_name'];
        
        // Ouvrir le fichier CSV
        $handle = fopen($filename, "r");
        if ($handle === false) {
            throw new Exception("Impossible d'ouvrir le fichier");
        }

        // Lire l'en-tête
        $header = fgetcsv($handle);
        if ($header === false) {
            throw new Exception("Le fichier est vide");
        }

        // Vérifier les colonnes requises
        $requiredColumns = ['email_eleve', 'note_classe', 'note_devoir', 'note_composition', 'trimestre'];
        $headerMap = array_flip($header);
        foreach ($requiredColumns as $column) {
            if (!isset($headerMap[$column])) {
                throw new Exception("Colonne manquante : $column");
            }
        }

        $db->beginTransaction();
        $successCount = 0;
        $errorCount = 0;
        $errors = [];
        $lineNumber = 1;

        // Traiter chaque ligne
        while (($data = fgetcsv($handle)) !== false) {
            $lineNumber++;
            try {
                if (count($data) !== count($header)) {
                    throw new Exception("Nombre de colonnes incorrect");
                }

                $row = array_combine($header, $data);

                // Valider les notes (entre 0 et 20)
                $notes = ['note_classe', 'note_devoir', 'note_composition'];
                foreach ($notes as $note) {
                    if (!is_numeric($row[$note]) || $row[$note] < 0 || $row[$note] > 20) {
                        throw new Exception("La $note doit être entre 0 et 20");
                    }
                }

                // Valider le trimestre
                if (!in_array($row['trimestre'], [1, 2, 3])) {
                    throw new Exception("Le trimestre doit être 1, 2 ou 3");
                }

                // Vérifier que l'élève existe et appartient à la classe
                $eleve = $utilisateur->getEleveByEmail($row['email_eleve']);
                if (!$eleve || $eleve['classe_id'] != $classe_id) {
                    throw new Exception("Élève non trouvé dans cette classe : " . $row['email_eleve']);
                }

                // Ajouter les notes
                $utilisateur->ajouterNotes(
                    $eleve['id'],
                    $matiere_id,
                    $row['note_classe'],
                    $row['note_devoir'],
                    $row['note_composition'],
                    $row['trimestre']
                );

                $successCount++;
            } catch (Exception $e) {
                $errorCount++;
                $errors[] = "Ligne $lineNumber : " . $e->getMessage();
            }
        }

        fclose($handle);

        if ($errorCount === 0) {
            $db->commit();
            $_SESSION['message'] = "Les notes de $successCount élèves ont été importées avec succès.";
            $_SESSION['message_type'] = "success";
        } else {
            $db->rollBack();
            $_SESSION['message'] = "Import terminé avec des erreurs : $errorCount erreur(s), $successCount succès.\n" . 
                                 implode("\n", $errors);
            $_SESSION['message_type'] = "warning";
        }

    } catch (Exception $e) {
        if ($db->inTransaction()) {
            $db->rollBack();
        }
        $_SESSION['message'] = "Erreur : " . $e->getMessage();
        $_SESSION['message_type'] = "danger";
    }
}

header('Location: import_notes.php');
exit;
