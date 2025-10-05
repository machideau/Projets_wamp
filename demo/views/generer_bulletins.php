<?php
require_once __DIR__ . '/../config/config.php';
require_once CONFIG_PATH . '/database.php';
require_once MODELS_PATH . '/Utilisateur.php';
require_once __DIR__ . '/../includes/auth.php';
require_once ROOT_PATH . '/vendor/fpdf/fpdf.php';

// Vérification de l'existence de FPDF
if (!class_exists('FPDF')) {
    $_SESSION['message'] = "Erreur: La bibliothèque FPDF n'est pas disponible. Contactez l'administrateur.";
    $_SESSION['message_type'] = "danger";
    header('Location: telecharger_bulletins.php');
    exit;
}

checkRole(['admin', 'directeur']);

if (!isset($_GET['classe_id']) || !isset($_GET['trimestre'])) {
    $_SESSION['message'] = "Paramètres manquants";
    $_SESSION['message_type'] = "danger";
    header('Location: telecharger_bulletins.php');
    exit;
}

$classe_id = $_GET['classe_id'];
$trimestre = $_GET['trimestre'];

$db = Database::getInstance();
$utilisateur = new Utilisateur($db);

// Récupérer les informations de la classe
$classe = $utilisateur->getClasseById($classe_id);
$eleves = $utilisateur->getElevesByClasse($classe_id);

// Vérifier que des élèves existent pour cette classe
if (empty($eleves)) {
    $_SESSION['message'] = "Aucun élève trouvé dans cette classe";
    $_SESSION['message_type'] = "warning";
    header('Location: telecharger_bulletins.php');
    exit;
}

// Créer un dossier temporaire avec chemin absolu
// Utiliser un chemin fixe pour le débogage temporaire
$temp_base = sys_get_temp_dir(); 
if (!is_writable($temp_base)) {
    // Si le dossier temporaire système n'est pas accessible en écriture, essayer un dossier local
    $temp_base = __DIR__ . '/../temp';
    if (!file_exists($temp_base)) {
        mkdir($temp_base, 0777, true);
    }
}

$temp_dir = $temp_base . '/bulletins_' . uniqid();
if (!mkdir($temp_dir, 0777, true)) {
    $_SESSION['message'] = "Erreur: Impossible de créer le dossier temporaire. Vérifiez les permissions.";
    $_SESSION['message_type'] = "danger";
    header('Location: telecharger_bulletins.php');
    exit;
}

// Vérifier si le répertoire est accessible en écriture
if (!is_writable($temp_dir)) {
    $_SESSION['message'] = "Erreur: Le dossier temporaire n'est pas accessible en écriture. Vérifiez les permissions.";
    $_SESSION['message_type'] = "danger";
    header('Location: telecharger_bulletins.php');
    exit;
}

// Créer un dossier permanent pour les bulletins à la racine du site
$bulletins_dir = ROOT_PATH . '/bulletins';
if (!file_exists($bulletins_dir)) {
    mkdir($bulletins_dir, 0777, true);
}

// Dossier spécifique pour cette classe et ce trimestre
$classe_dir = $bulletins_dir . '/' . sanitizeFileName($classe['nom_classe']) . '_Trimestre' . $trimestre;
if (file_exists($classe_dir)) {
    // Nettoyer le dossier s'il existe déjà
    $old_files = glob($classe_dir . '/*');
    foreach ($old_files as $file) {
        @unlink($file);
    }
} else {
    mkdir($classe_dir, 0777, true);
}

// Vérifier si le répertoire est accessible en écriture
if (!is_writable($classe_dir)) {
    $_SESSION['message'] = "Erreur: Le dossier pour les bulletins n'est pas accessible en écriture. Vérifiez les permissions.";
    $_SESSION['message_type'] = "danger";
    header('Location: telecharger_bulletins.php');
    exit;
}

// Fichier de log pour débogage
$log_file = $classe_dir . '/debug.log';
file_put_contents($log_file, "Début de génération - " . date('Y-m-d H:i:s') . "\n");
file_put_contents($log_file, "Dossier classe: $classe_dir\n", FILE_APPEND);
file_put_contents($log_file, "Dossier temporaire: $temp_dir\n", FILE_APPEND);
file_put_contents($log_file, "Classe: {$classe['nom_classe']}, Trimestre: $trimestre\n", FILE_APPEND);
file_put_contents($log_file, "Nombre d'élèves: " . count($eleves) . "\n", FILE_APPEND);
file_put_contents($log_file, "Version PHP: " . phpversion() . "\n", FILE_APPEND);
file_put_contents($log_file, "Extensions chargées: " . implode(", ", get_loaded_extensions()) . "\n", FILE_APPEND);
file_put_contents($log_file, "Classe FPDF existe: " . (class_exists('FPDF') ? 'Oui' : 'Non') . "\n", FILE_APPEND);

// Analyse de la structure des tables de la base de données
file_put_contents($log_file, "\n--- ANALYSE DE LA STRUCTURE DES DONNÉES ---\n", FILE_APPEND);

// Structure de la table notes
try {
    $query = "DESCRIBE notes";
    $stmt = $db->prepare($query);
    $stmt->execute();
    $notes_structure = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    file_put_contents($log_file, "Structure de la table notes:\n", FILE_APPEND);
    foreach ($notes_structure as $column) {
        file_put_contents($log_file, "  - {$column['Field']}: {$column['Type']}" .
                         ($column['Key'] ? " (KEY: {$column['Key']})" : "") . "\n", FILE_APPEND);
    }
} catch (Exception $e) {
    file_put_contents($log_file, "Erreur lors de l'accès à la structure de la table notes: " . $e->getMessage() . "\n", FILE_APPEND);
}

// Vérifier si la table notes contient des données
try {
    $query = "SELECT COUNT(*) as count FROM notes";
    $stmt = $db->prepare($query);
    $stmt->execute();
    $notes_count = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    
    file_put_contents($log_file, "Nombre total d'enregistrements dans la table notes: $notes_count\n", FILE_APPEND);
    
    if ($notes_count > 0) {
        // Échantillon des données
        $query = "SELECT * FROM notes LIMIT 3";
        $stmt = $db->prepare($query);
        $stmt->execute();
        $notes_sample = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        file_put_contents($log_file, "Échantillon des données de notes:\n", FILE_APPEND);
        foreach ($notes_sample as $note) {
            file_put_contents($log_file, "  - ID: {$note['id']}, Élève ID: {$note['eleve_id']}, " .
                             "Matière ID: {$note['matiere_id']}, Trimestre: {$note['trimestre']}\n", FILE_APPEND);
        }
    }
} catch (Exception $e) {
    file_put_contents($log_file, "Erreur lors de l'accès aux données de la table notes: " . $e->getMessage() . "\n", FILE_APPEND);
}

// Vérifier la correspondance entre les élèves et les notes
try {
    file_put_contents($log_file, "\n--- VÉRIFICATION DES CORRESPONDANCES ÉLÈVES-NOTES ---\n", FILE_APPEND);
    
    // Récupérer tous les IDs élèves uniques dans la table notes
    $query = "SELECT DISTINCT eleve_id FROM notes";
    $stmt = $db->prepare($query);
    $stmt->execute();
    $eleves_ids_notes = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    file_put_contents($log_file, "Élèves IDs trouvés dans la table notes: " . implode(", ", $eleves_ids_notes) . "\n", FILE_APPEND);
    
    // Récupérer tous les IDs élèves de la classe
    $eleves_ids_classe = array_column($eleves, 'id');
    file_put_contents($log_file, "Élèves IDs dans la classe actuelle: " . implode(", ", $eleves_ids_classe) . "\n", FILE_APPEND);
    
    // Vérifier l'intersection
    $ids_communs = array_intersect($eleves_ids_notes, $eleves_ids_classe);
    file_put_contents($log_file, "Élèves IDs communs: " . implode(", ", $ids_communs) . "\n", FILE_APPEND);
    
    // Vérifier si les emails des élèves sont utilisés comme eleve_id dans la table notes
    file_put_contents($log_file, "Vérification si les emails sont utilisés comme ID dans la table notes:\n", FILE_APPEND);
    foreach ($eleves as $eleve) {
        if (in_array($eleve['email'], $eleves_ids_notes)) {
            file_put_contents($log_file, "  - MATCH TROUVÉ: L'email de l'élève '{$eleve['nom']} {$eleve['prenom']}' ({$eleve['email']}) est utilisé comme eleve_id dans la table notes.\n", FILE_APPEND);
        }
    }
    
} catch (Exception $e) {
    file_put_contents($log_file, "Erreur lors de la vérification des correspondances: " . $e->getMessage() . "\n", FILE_APPEND);
}

// Générer les bulletins pour chaque élève
$pdf_generes = 0;
$eleves_sans_notes = [];

file_put_contents($log_file, "\n--- DÉBUT DE LA GÉNÉRATION DES BULLETINS ---\n", FILE_APPEND);

// Récupérer d'abord tous les identifiants d'élèves dans la table notes pour ce trimestre
try {
    $query = "SELECT DISTINCT eleve_id FROM notes WHERE trimestre = ?";
    $stmt = $db->prepare($query);
    $stmt->execute([$trimestre]);
    $eleves_ids_notes_trimestre = $stmt->fetchAll(PDO::FETCH_COLUMN);
    file_put_contents($log_file, "Élèves IDs trouvés dans la table notes pour ce trimestre: " . implode(", ", $eleves_ids_notes_trimestre) . "\n", FILE_APPEND);
} catch (Exception $e) {
    file_put_contents($log_file, "Erreur lors de la récupération des IDs élèves pour ce trimestre: " . $e->getMessage() . "\n", FILE_APPEND);
    $eleves_ids_notes_trimestre = [];
}

foreach ($eleves as $eleve) {
    file_put_contents($log_file, "\nTraitement de l'élève: {$eleve['nom']} {$eleve['prenom']} (ID: {$eleve['id']}, Email: {$eleve['email']})\n", FILE_APPEND);
    
    // Essayer différentes méthodes pour récupérer les notes
    $notes = [];
    
    // 1. Essayer avec l'ID
    if (in_array($eleve['id'], $eleves_ids_notes_trimestre)) {
        file_put_contents($log_file, "  - Essai avec l'ID de l'élève ({$eleve['id']})\n", FILE_APPEND);
        $notes = $utilisateur->getNotesEleve($eleve['id'], $trimestre);
        file_put_contents($log_file, "  - Notes trouvées avec ID: " . count($notes) . "\n", FILE_APPEND);
    }
    
    // 2. Si aucune note trouvée avec l'ID, essayer avec l'email
    if (count($notes) == 0 && !empty($eleve['email'])) {
        // Vérifier si l'email est utilisé comme ID dans la table notes
        if (in_array($eleve['email'], $eleves_ids_notes_trimestre)) {
            file_put_contents($log_file, "  - L'email ({$eleve['email']}) est utilisé comme ID dans la table notes. Récupération directe des notes.\n", FILE_APPEND);
            
            // Récupération directe avec une requête SQL personnalisée
            try {
                $query = "SELECT n.*, m.nom_matiere 
                         FROM notes n 
                         JOIN matieres m ON n.matiere_id = m.id 
                         WHERE n.eleve_id = ? AND n.trimestre = ?";
                $stmt = $db->prepare($query);
                $stmt->execute([$eleve['email'], $trimestre]);
                $notes = $stmt->fetchAll(PDO::FETCH_ASSOC);
                file_put_contents($log_file, "  - Notes trouvées avec email comme ID: " . count($notes) . "\n", FILE_APPEND);
            } catch (Exception $e) {
                file_put_contents($log_file, "  - Erreur lors de la récupération des notes avec email comme ID: " . $e->getMessage() . "\n", FILE_APPEND);
            }
        } else {
            // Essayer de trouver un ID correspondant à l'email
            file_put_contents($log_file, "  - Essai avec la méthode getNotesByEmail\n", FILE_APPEND);
            $notes = $utilisateur->getNotesByEmail($eleve['email'], $trimestre);
            file_put_contents($log_file, "  - Notes trouvées avec getNotesByEmail: " . count($notes) . "\n", FILE_APPEND);
        }
    }
    
    // Si toujours pas de notes, ajouter à la liste des élèves sans notes
    if (count($notes) == 0) {
        $eleves_sans_notes[] = $eleve['nom'] . ' ' . $eleve['prenom'];
        file_put_contents($log_file, "  - AUCUNE NOTE TROUVÉE pour cet élève\n", FILE_APPEND);
    } else {
        file_put_contents($log_file, "  - Notes trouvées: " . count($notes) . "\n", FILE_APPEND);
        
        // Afficher les premières notes pour débogage
        $sample_notes = array_slice($notes, 0, 2);
        foreach ($sample_notes as $index => $note) {
            file_put_contents($log_file, "    - Note " . ($index + 1) . ": Matière: {$note['nom_matiere']}, " .
                             "Classe: {$note['note_classe']}, Devoir: {$note['note_devoir']}, " .
                             "Composition: {$note['note_composition']}\n", FILE_APPEND);
        }
    }
    
    // Nom du fichier pour ce bulletin - stocker dans le dossier permanent
    $filename = $classe_dir . '/' . sanitizeFileName($eleve['nom'] . '_' . $eleve['prenom']) . '.pdf';
    
    // Générer le PDF
    $pdf_result = genererBulletin($eleve, $notes, $classe, $trimestre, $filename, $log_file);
    
    // Vérifier si le fichier a été créé
    if ($pdf_result && file_exists($filename) && filesize($filename) > 0) {
        file_put_contents($log_file, "PDF créé: $filename (" . filesize($filename) . " octets)\n", FILE_APPEND);
        $pdf_generes++;
    } else {
        file_put_contents($log_file, "ERREUR: PDF non créé ou vide: $filename\n", FILE_APPEND);
    }
}

// Résumé de la génération
file_put_contents($log_file, "\n--- RÉSUMÉ DE LA GÉNÉRATION ---\n", FILE_APPEND);
file_put_contents($log_file, "Nombre total d'élèves: " . count($eleves) . "\n", FILE_APPEND);
file_put_contents($log_file, "Nombre de PDF générés: " . $pdf_generes . "\n", FILE_APPEND);

if (!empty($eleves_sans_notes)) {
    file_put_contents($log_file, "Élèves sans notes: " . implode(", ", $eleves_sans_notes) . "\n", FILE_APPEND);
}

// Vérifier les fichiers PDF avant de créer le ZIP
$pdf_files = glob($classe_dir . '/*.pdf');
file_put_contents($log_file, "Nombre de fichiers PDF générés: " . count($pdf_files) . "\n", FILE_APPEND);
file_put_contents($log_file, "Fichiers trouvés dans le dossier: " . implode(", ", array_map('basename', glob($classe_dir . '/*'))) . "\n", FILE_APPEND);

// Vérifier s'il y a des PDF à ajouter au ZIP
if (count($pdf_files) == 0) {
    file_put_contents($log_file, "Aucun PDF généré. Impossible de créer le ZIP.\n", FILE_APPEND);
    $_SESSION['message'] = "Aucun bulletin n'a pu être généré pour cette classe et ce trimestre. Vérifiez que des notes ont été saisies.";
    $_SESSION['message_type'] = "warning";
    header('Location: telecharger_bulletins.php');
    exit;
}

// Créer le fichier ZIP
$zipname = sanitizeFileName($classe['nom_classe']) . '_Trimestre' . $trimestre . '.zip';
$zipPath = $temp_dir . '/' . $zipname;

// Vérifier si ZipArchive est disponible
file_put_contents($log_file, "ZipArchive existe: " . (class_exists('ZipArchive') ? 'Oui' : 'Non') . "\n", FILE_APPEND);

// Si ZipArchive n'est pas disponible, utiliser une alternative avec shell_exec si possible
if (!class_exists('ZipArchive')) {
    // Alternative pour créer un ZIP (nécessite que zip soit installé sur le serveur)
    file_put_contents($log_file, "Utilisation de l'alternative shell_exec pour créer le ZIP\n", FILE_APPEND);
    $cmd = "cd " . escapeshellarg($classe_dir) . " && zip -j " . escapeshellarg($zipPath) . " *.pdf";
    $output = shell_exec($cmd);
    file_put_contents($log_file, "Commande exécutée: $cmd\nSortie: $output\n", FILE_APPEND);
    
    if (!file_exists($zipPath) || filesize($zipPath) <= 0) {
        file_put_contents($log_file, "ERREUR: ZIP non créé avec shell_exec\n", FILE_APPEND);
        $_SESSION['message'] = "Erreur lors de la création du fichier ZIP. L'extension ZipArchive n'est pas disponible.";
        $_SESSION['message_type'] = "danger";
        header('Location: telecharger_bulletins.php');
        exit;
    }
} else {
    // Utiliser ZipArchive
    $zip = new ZipArchive();
    $result = $zip->open($zipPath, ZipArchive::CREATE);
    
    if ($result !== TRUE) {
        file_put_contents($log_file, "Erreur lors de la création du fichier ZIP: code $result\n", FILE_APPEND);
        $_SESSION['message'] = "Erreur lors de la création du fichier ZIP (code $result)";
        $_SESSION['message_type'] = "danger";
        header('Location: telecharger_bulletins.php');
        exit;
    }
    
    // Ajouter tous les PDF au ZIP
    $fichiers_ajoutes = 0;
    foreach ($pdf_files as $file) {
        if ($zip->addFile($file, basename($file))) {
            file_put_contents($log_file, "Ajout au ZIP: " . basename($file) . "\n", FILE_APPEND);
            $fichiers_ajoutes++;
        } else {
            file_put_contents($log_file, "ERREUR: Impossible d'ajouter " . basename($file) . " au ZIP\n", FILE_APPEND);
            // Vérifier les permissions et l'existence du fichier
            file_put_contents($log_file, "  - Fichier existe: " . (file_exists($file) ? 'Oui' : 'Non') . "\n", FILE_APPEND);
            file_put_contents($log_file, "  - Fichier lisible: " . (is_readable($file) ? 'Oui' : 'Non') . "\n", FILE_APPEND);
            file_put_contents($log_file, "  - Taille du fichier: " . (file_exists($file) ? filesize($file) : 'N/A') . " octets\n", FILE_APPEND);
        }
    }
    
    // Vérifier si des fichiers ont été ajoutés au ZIP
    if ($fichiers_ajoutes == 0) {
        file_put_contents($log_file, "ERREUR: Aucun fichier PDF n'a pu être ajouté au ZIP\n", FILE_APPEND);
    $zip->close();
        @unlink($zipPath); // Supprimer le ZIP vide
        
        $_SESSION['message'] = "Erreur: Aucun bulletin n'a pu être ajouté au fichier ZIP. Les bulletins ont été générés et sont disponibles dans le dossier 'bulletins/{$classe['nom_classe']}_Trimestre{$trimestre}'.";
        $_SESSION['message_type'] = "warning";
        header('Location: telecharger_bulletins.php');
        exit;
    }
    
    // Ajouter le fichier log au ZIP
    $zip->addFile($log_file, 'debug.log');
    
    // Fermer le ZIP
    if (!$zip->close()) {
        file_put_contents($log_file, "ERREUR: Impossible de finaliser le ZIP\n", FILE_APPEND);
        $_SESSION['message'] = "Erreur lors de la finalisation du fichier ZIP";
        $_SESSION['message_type'] = "danger";
        header('Location: telecharger_bulletins.php');
        exit;
    }
}

// Vérifier que le ZIP a été créé correctement
if (!file_exists($zipPath) || filesize($zipPath) <= 0) {
    file_put_contents($log_file, "ERREUR: ZIP non créé ou vide\n", FILE_APPEND);
    $_SESSION['message'] = "Erreur lors de la création du fichier ZIP. Les bulletins ont été générés et sont disponibles dans le dossier 'bulletins/{$classe['nom_classe']}_Trimestre{$trimestre}'.";
    $_SESSION['message_type'] = "warning";
    header('Location: telecharger_bulletins.php');
    exit;
}

file_put_contents($log_file, "ZIP créé avec succès: $zipPath (" . filesize($zipPath) . " octets)\n", FILE_APPEND);
    
    // Envoyer le fichier ZIP
    header('Content-Type: application/zip');
    header('Content-Disposition: attachment; filename="' . $zipname . '"');
    header('Content-Length: ' . filesize($zipPath));

// Débogage: stocker des informations sur le fichier ZIP
file_put_contents($log_file, "Avant readfile - Fichier existe: " . (file_exists($zipPath) ? 'Oui' : 'Non') . "\n", FILE_APPEND);
file_put_contents($log_file, "Avant readfile - Taille: " . (file_exists($zipPath) ? filesize($zipPath) : 'N/A') . " octets\n", FILE_APPEND);

// Lire et envoyer le fichier
$bytes_sent = @readfile($zipPath);
if ($bytes_sent === false) {
    // Si readfile échoue, essayer une autre méthode
    $file_content = file_get_contents($zipPath);
    if ($file_content !== false) {
        echo $file_content;
        $bytes_sent = strlen($file_content);
    }
}

// Nettoyer les fichiers temporaires après l'envoi complet du fichier
register_shutdown_function(function() use ($temp_dir, $log_file, $zipPath, $bytes_sent) {
    // Journaliser la fin du script avant de supprimer les fichiers
    file_put_contents($log_file, "Fin du script - " . date('Y-m-d H:i:s') . "\n", FILE_APPEND);
    file_put_contents($log_file, "Octets envoyés: " . ($bytes_sent !== false ? $bytes_sent : 'ERREUR') . "\n", FILE_APPEND);
    
    // Ne nettoyer que le dossier temporaire, pas les PDF stockés à la racine
    // Attendre un peu pour s'assurer que le téléchargement est terminé
    sleep(1);
    
    // Supprimer tous les fichiers temporaires
    if (file_exists($temp_dir)) {
        $temp_files = glob($temp_dir . '/*');
        foreach ($temp_files as $file) {
            @unlink($file);
        }
        @rmdir($temp_dir);
    }
});
exit;

function sanitizeFileName($filename) {
    // Remplacer les caractères spéciaux et les espaces
    $filename = preg_replace('/[^a-zA-Z0-9_\-]/', '_', $filename);
    return $filename;
}

function genererBulletin($eleve, $notes, $classe, $trimestre, $filename, $log_file) {
    try {
        // Vérifier que la classe FPDF existe
        if (!class_exists('FPDF')) {
            file_put_contents($log_file, "ERREUR: La classe FPDF n'existe pas\n", FILE_APPEND);
            return false;
        }
        
    $pdf = new FPDF();
    $pdf->AddPage();
    
    // En-tête du bulletin
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(0, 10, 'BULLETIN DE NOTES', 0, 1, 'C');
    $pdf->Cell(0, 10, $trimestre . 'e TRIMESTRE', 0, 1, 'C');
    
    // Informations de l'élève
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(0, 10, 'Nom et Prénoms: ' . $eleve['nom'] . ' ' . $eleve['prenom'], 0, 1);
    $pdf->Cell(0, 10, 'Classe: ' . $classe['nom_classe'], 0, 1);
    
    // En-tête du tableau
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Ln(10);
    $pdf->Cell(60, 10, 'Matière', 1);
    $pdf->Cell(30, 10, 'Note Classe', 1);
    $pdf->Cell(30, 10, 'Note Devoir', 1);
    $pdf->Cell(35, 10, 'Composition', 1);
    $pdf->Cell(35, 10, 'Moyenne', 1);
    $pdf->Ln();
    
    // Contenu du tableau
    $pdf->SetFont('Arial', '', 10);
    $totalMoyennes = 0;
    $nombreMatieres = 0;
    
        // Vérifier si des notes existent
        if (empty($notes)) {
            $pdf->Cell(0, 10, 'Aucune note disponible pour ce trimestre', 1, 1, 'C');
            file_put_contents($log_file, "Aucune note pour l'élève {$eleve['nom']} {$eleve['prenom']}\n", FILE_APPEND);
        } else {
    foreach ($notes as $note) {
        $moyenne = ($note['note_classe'] + $note['note_devoir'] + $note['note_composition']) / 3;
        $totalMoyennes += $moyenne;
        $nombreMatieres++;
        
        $pdf->Cell(60, 10, $note['nom_matiere'], 1);
        $pdf->Cell(30, 10, number_format($note['note_classe'], 2), 1);
        $pdf->Cell(30, 10, number_format($note['note_devoir'], 2), 1);
        $pdf->Cell(35, 10, number_format($note['note_composition'], 2), 1);
        $pdf->Cell(35, 10, number_format($moyenne, 2), 1);
        $pdf->Ln();
    }
    
    // Moyenne générale
    if ($nombreMatieres > 0) {
        $moyenneGenerale = $totalMoyennes / $nombreMatieres;
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(155, 10, 'Moyenne Générale:', 1);
        $pdf->Cell(35, 10, number_format($moyenneGenerale, 2), 1);
            }
        }
        
        // Vérifier que le répertoire existe
        $dir = dirname($filename);
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
        
        // Générer le PDF avec gestion d'erreurs
        $pdf_result = false;
        try {
            $pdf_result = $pdf->Output($filename, 'F');
            file_put_contents($log_file, "PDF généré pour {$eleve['nom']} {$eleve['prenom']}\n", FILE_APPEND);
            return true;
        } catch (Exception $e) {
            file_put_contents($log_file, "Exception lors de la génération du PDF: " . $e->getMessage() . "\n", FILE_APPEND);
            return false;
        }
    } catch (Exception $e) {
        file_put_contents($log_file, "Erreur lors de la génération du PDF pour {$eleve['nom']} {$eleve['prenom']}: " . $e->getMessage() . "\n", FILE_APPEND);
        return false;
    }
}



