<?php
require_once __DIR__ . '/../config/config.php';
require_once CONFIG_PATH . '/database.php';
require_once MODELS_PATH . '/Utilisateur.php';
require_once __DIR__ . '/../includes/auth.php';

checkRole(['admin', 'directeur']);

$db = Database::getInstance();
$utilisateur = new Utilisateur($db);
$classes = $utilisateur->getClasses();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Import CSV - Élèves</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include 'header.php'; ?>

    <div class="container mt-4">
        <h2>Import CSV des Élèves</h2>
        
        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-<?= $_SESSION['message_type'] ?> alert-dismissible fade show">
                <?= $_SESSION['message'] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php 
            unset($_SESSION['message']);
            unset($_SESSION['message_type']);
            ?>
        <?php endif; ?>

        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Instructions</h5>
                <p>Le fichier CSV doit contenir les colonnes suivantes :</p>
                <ul>
                    <li>nom</li>
                    <li>prenom</li>
                    <li>email</li>
                    <li>date_naissance (formats acceptés : JJ/MM/AAAA, AAAA-MM-JJ, JJ-MM-AAAA)</li>
                    <li>classe_id</li>
                </ul>
                <p>Exemples de contenu :</p>
                <pre>
                    nom,prenom,email,date_naissance,classe_id
                    Dupont,Jean,jean.dupont@email.com,15/03/2005,1
                    Martin,Marie,marie.martin@email.com,22/06/2005,1
                    Durant,Pierre,pierre.durant@email.com,25/08/2005,1
                </pre>
            </div>
        </div>

        <form action="traitement_import_eleves.php" method="POST" enctype="multipart/form-data" class="mt-4">
            <div class="mb-3">
                <label for="csvFile" class="form-label">Fichier CSV</label>
                <input type="file" class="form-control" id="csvFile" name="csvFile" accept=".csv" required>
            </div>
            
            <div class="mb-3">
                <label for="classe_id" class="form-label">Classe par défaut (optionnel)</label>
                <select class="form-control" id="classe_id" name="classe_id">
                    <option value="">Sélectionner une classe</option>
                    <?php foreach ($classes as $classe): ?>
                        <option value="<?= $classe['id'] ?>"><?= htmlspecialchars($classe['nom_classe']) ?></option>
                    <?php endforeach; ?>
                </select>
                <small class="form-text text-muted">Si spécifié, cette classe sera utilisée pour les lignes sans classe_id</small>
            </div>

            <button type="submit" class="btn btn-primary">Importer</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
