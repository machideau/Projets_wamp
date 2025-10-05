<?php
require_once __DIR__ . '/../config/config.php';
require_once CONFIG_PATH . '/database.php';
require_once MODELS_PATH . '/Utilisateur.php';
require_once __DIR__ . '/../includes/auth.php';

checkRole(['admin', 'directeur']);

$db = Database::getInstance();
$utilisateur = new Utilisateur($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (!isset($_FILES['csvFile']) || $_FILES['csvFile']['error'] !== UPLOAD_ERR_OK) {
            throw new Exception("Erreur lors du téléchargement du fichier");
        }

        $defaultClasseId = !empty($_POST['classe_id']) ? $_POST['classe_id'] : null;
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
        $requiredColumns = ['nom', 'prenom', 'email', 'date_naissance'];
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
                
                // Utiliser la classe_id du CSV ou la valeur par défaut
                $classeId = isset($row['classe_id']) && !empty($row['classe_id']) 
                    ? $row['classe_id'] 
                    : $defaultClasseId;

                if (empty($classeId)) {
                    throw new Exception("classe_id non spécifié");
                }

                // Valider la date
                $dateFormat = '/^\d{2}\/\d{2}\/\d{4}$|^\d{4}-\d{2}-\d{2}$|^\d{2}-\d{2}-\d{4}$/';
                if (!preg_match($dateFormat, $row['date_naissance'])) {
                    throw new Exception("Format de date invalide. Utilisez JJ/MM/AAAA, AAAA-MM-JJ ou JJ-MM-AAAA");
                }

                $utilisateur->ajouterEleve(
                    $row['nom'],
                    $row['prenom'],
                    $row['email'],
                    date('Y-m-d', strtotime(str_replace('/', '-', $row['date_naissance']))),
                    $classeId
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
            $_SESSION['message'] = "$successCount élèves ont été importés avec succès.";
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

header('Location: import_eleves.php');
exit;
