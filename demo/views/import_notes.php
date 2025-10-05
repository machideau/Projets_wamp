<?php
require_once __DIR__ . '/../config/config.php';
require_once CONFIG_PATH . '/database.php';
require_once MODELS_PATH . '/Utilisateur.php';
require_once __DIR__ . '/../includes/auth.php';

if (!isset($_SESSION['user']) || !isset($_SESSION['user']['id'])) {
    $_SESSION['error'] = "Vous devez être connecté pour accéder à cette page.";
    header('Location: ' . BASE_URL . '/connexion.php');
    exit;
}

checkRole(['enseignant']);

$db = Database::getInstance();
$utilisateur = new Utilisateur($db);

$user_id = $_SESSION['user']['id'];
$matieres = $utilisateur->getMatieresEnseignant($user_id);
$classes = $utilisateur->getClassesEnseignant($user_id);

// Récupérer la classe et matière sélectionnées
$selectedClassId = isset($_GET['classe_id']) ? $_GET['classe_id'] : null;
$selectedMatiereId = isset($_GET['matiere_id']) ? $_GET['matiere_id'] : null;

// Récupérer les élèves uniquement si l'enseignant a le droit d'accéder à cette classe et matière
$eleves = [];
if ($selectedClassId && $selectedMatiereId && $utilisateur->peutNoterClasseMatiere($user_id, $selectedClassId, $selectedMatiereId)) {
    $eleves = $utilisateur->getElevesByClasse($selectedClassId);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Import des Notes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include 'header.php'; ?>

    <div class="container mt-4">
        <h2>Import CSV des Notes</h2>
        
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

        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">Instructions</h5>
                <p>Le fichier CSV doit contenir les colonnes suivantes :</p>
                <ul>
                    <li>email_eleve</li>
                    <li>note_classe (sur 20)</li>
                    <li>note_devoir (sur 20)</li>
                    <li>note_composition (sur 20)</li>
                    <li>trimestre (1, 2 ou 3)</li>
                </ul>
                <p>Exemple de contenu :</p>
                <pre>email_eleve,note_classe,note_devoir,note_composition,trimestre
jean.dupont@email.com,15,16,14,1
marie.martin@email.com,17,18,16,1</pre>
            </div>
        </div>

        <form action="traitement_import_notes.php" method="POST" enctype="multipart/form-data">
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="classe_id" class="form-label">Classe</label>
                    <select class="form-control" id="classe_id" name="classe_id" required>
                        <option value="">Sélectionner une classe</option>
                        <?php foreach ($classes as $classe): ?>
                            <option value="<?= $classe['id'] ?>" <?= ($selectedClassId == $classe['id'] ? 'selected' : '') ?>>
                                <?= htmlspecialchars($classe['nom_classe']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="matiere_id" class="form-label">Matière</label>
                    <select class="form-control" id="matiere_id" name="matiere_id" required>
                        <option value="">Sélectionner une matière</option>
                        <?php foreach ($matieres as $matiere): ?>
                            <option value="<?= $matiere['id'] ?>" <?= ($selectedMatiereId == $matiere['id'] ? 'selected' : '') ?>>
                                <?= htmlspecialchars($matiere['nom_matiere']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="mb-3">
                <label for="csvFile" class="form-label">Fichier CSV</label>
                <input type="file" class="form-control" id="csvFile" name="csvFile" accept=".csv" required>
            </div>

            <button type="submit" class="btn btn-primary">Importer les notes</button>
        </form>

        <?php if (!empty($eleves)): ?>
        <div class="mt-4">
            <h3>Liste des élèves de la classe</h3>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Email</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($eleves as $eleve): ?>
                    <tr>
                        <td><?= htmlspecialchars($eleve['nom']) ?></td>
                        <td><?= htmlspecialchars($eleve['prenom']) ?></td>
                        <td><?= htmlspecialchars($eleve['email']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

