<?php
require_once __DIR__ . '/../../config/config.php';
require_once CONFIG_PATH . '/database.php';
require_once MODELS_PATH . '/Utilisateur.php';
require_once __DIR__ . '/../../includes/auth.php';

// Vérifier que l'utilisateur est un enseignant
checkRole(['enseignant']);

$db = Database::getInstance();
$utilisateur = new Utilisateur($db);

// Récupérer les informations de l'enseignant
$enseignant = $utilisateur->getUtilisateurById($_SESSION['user']['id']);
$matieres = $utilisateur->getMatieresEnseignant($_SESSION['user']['id']);
$classes = $utilisateur->getClassesEnseignant($_SESSION['user']['id']);

include __DIR__ . '/../header.php';
?>
<?php if (isAdmin() || isDirecteur()): ?>
    <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/views/telecharger_bulletins.php">Bulletins</a></li>
<?php endif; ?>


<div class="container mt-4">
    <h2>Tableau de bord Enseignant</h2>
    
    <?php Flash::display(); ?>

    <div class="row mt-4">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Informations personnelles</h5>
                    <p class="card-text">
                        <strong>Nom:</strong> <?= htmlspecialchars($enseignant['nom']) ?><br>
                        <strong>Prénom:</strong> <?= htmlspecialchars($enseignant['prenom']) ?><br>
                        <strong>Email:</strong> <?= htmlspecialchars($enseignant['email']) ?><br>
                        <strong>Téléphone:</strong> <?= htmlspecialchars($enseignant['telephone'] ?? 'Non renseigné') ?>
                    </p>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5 class="card-title">Mes classes</h5>
                            <select class="form-select" id="classeSelect" onchange="chargerMatieres()">
                                <option value="">Sélectionnez une classe</option>
                                <?php foreach ($classes as $classe): ?>
                                    <option value="<?= $classe['id'] ?>">
                                        <?= htmlspecialchars($classe['nom_classe']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <h5 class="card-title">Mes matières</h5>
                            <ul class="list-group" id="matieresList">
                                <li class="list-group-item text-muted">Sélectionnez une classe pour voir les matières</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Actions rapides</h5>
                    <div class="list-group">
                        <a href="<?= BASE_URL ?>/views/import_notes.php" class="list-group-item list-group-item-action">
                            Importer des notes
                        </a>
                        <a href="<?= BASE_URL ?>/views/consulter_moyennes.php" class="list-group-item list-group-item-action">
                            Consulter les moyennes
                        </a>
                        <a href="<?= BASE_URL ?>/views/gestion_absences.php" class="list-group-item list-group-item-action">
                            Gérer les absences
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Dernières activités</h5>
                    <p class="card-text text-muted">
                        Aucune activité récente à afficher.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function chargerMatieres() {
    const classeId = document.getElementById('classeSelect').value;
    const matieresList = document.getElementById('matieresList');

    if (!classeId) {
        matieresList.innerHTML = '<li class="list-group-item text-muted">Sélectionnez une classe pour voir les matières</li>';
        return;
    }

    // Appel AJAX pour récupérer les matières de l'enseignant pour cette classe
    fetch(`<?= BASE_URL ?>/views/get_matieres_enseignant.php?classe_id=${classeId}`)
        .then(response => response.json())
        .then(matieres => {
            if (matieres.length === 0) {
                matieresList.innerHTML = '<li class="list-group-item text-muted">Aucune matière pour cette classe</li>';
                return;
            }

            matieresList.innerHTML = matieres
                .map(matiere => `<li class="list-group-item">${matiere.nom_matiere}</li>`)
                .join('');
        })
        .catch(error => {
            console.error('Erreur:', error);
            matieresList.innerHTML = '<li class="list-group-item text-danger">Erreur lors du chargement des matières</li>';
        });
}
</script>

<?php include __DIR__ . '/../footer.php'; ?>

