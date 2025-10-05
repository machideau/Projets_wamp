<?php 
require_once __DIR__ . '/../config/config.php';
require_once CONFIG_PATH . '/database.php';
require_once CONTROLLERS_PATH . '/InscriptionController.php';
require_once MODELS_PATH . '/Utilisateur.php';
require_once __DIR__ . '/../includes/auth.php';

$db = Database::getInstance();
$controller = new InscriptionController($db);
$utilisateur = new Utilisateur($db);
$classes = $utilisateur->getClasses();

include __DIR__ . '/header.php'; 
?>

<div class="container mt-5">
    <h2 class="mb-4">Créez votre compte enseignant</h2>
    
    <?php Flash::display(); ?>
    
    <form method="POST" action="<?= BASE_URL ?>/index.php?action=inscription" class="needs-validation" novalidate>
        <div class="mb-3">
            <label for="nom" class="form-label">Nom:</label>
            <input type="text" class="form-control" id="nom" name="nom" required>
        </div>
        
        <div class="mb-3">
            <label for="prenom" class="form-label">Prénom:</label>
            <input type="text" class="form-control" id="prenom" name="prenom" required>
        </div>
        
        <div class="mb-3">
            <label for="email" class="form-label">Email:</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        
        <div class="mb-3">
            <label for="password" class="form-label">Mot de passe:</label>
            <input type="password" class="form-control" id="password" name="mot_de_passe" required>
        </div>
        
        <div class="mb-3">
            <label for="telephone" class="form-label">Numéro de téléphone:</label>
            <input type="tel" class="form-control" id="telephone" name="telephone">
        </div>

        <div class="mb-3">
            <label for="classes" class="form-label">Classes:</label>
            <select class="form-select" id="classes" name="classe_id" required>
                <option value="">Sélectionnez une classe</option>
                <?php foreach ($classes as $classe): ?>
                    <option value="<?= htmlspecialchars($classe['id']) ?>">
                        <?= htmlspecialchars($classe['nom_classe']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div id="matieres-container" class="mb-3">
            <!-- Les matières seront chargées ici -->
        </div>

        <div id="classes-selected" class="mb-3">
            <h5>Classes et matières sélectionnées:</h5>
            <div class="list-group" id="classes-matieres-list">
                <!-- Les classes et matières sélectionnées seront affichées ici -->
            </div>
        </div>

        <div class="mt-4">
            <a href="connexion.php" class="btn btn-link">Déjà inscrit ? Se connecter</a>
            <button type="submit" class="btn btn-primary">S'inscrire</button>
        </div>
    </form>
</div>

<script>
// Variables pour stocker les sélections
let selectedClassesAndSubjects = {};

document.getElementById('classes').addEventListener('change', function() {
    const classeId = this.value;
    if (!classeId) {
        document.getElementById('matieres-container').innerHTML = '';
        return;
    }
    
    loadMatieres(classeId);
});

function loadMatieres(classeId) {
    const container = document.getElementById('matieres-container');
    container.innerHTML = '<div class="text-center"><div class="spinner-border" role="status"></div></div>';

    fetch(`get_matieres.php?classe_id=${classeId}`)
        .then(response => response.json())
        .then(matieres => {
            const className = document.querySelector(`#classes option[value="${classeId}"]`).textContent;
            
            let html = `
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="mb-0">Matières pour ${className}</h5>
                    </div>
                    <div class="card-body">
            `;

            if (matieres.length === 0) {
                html += '<p class="text-muted">Aucune matière disponible pour cette classe</p>';
            } else {
                html += `
                    <div class="row">
                        ${matieres.map(matiere => `
                            <div class="col-md-4 mb-2">
                                <div class="form-check">
                                    <input class="form-check-input matiere-checkbox" type="checkbox" 
                                        data-matiere-id="${matiere.id}"
                                        data-matiere-nom="${matiere.nom_matiere}"
                                        id="matiere-${classeId}-${matiere.id}"
                                        ${matiere.deja_prise ? 'disabled' : ''}>
                                    <label class="form-check-label" for="matiere-${classeId}-${matiere.id}">
                                        ${matiere.nom_matiere}
                                        ${matiere.deja_prise ? `<small class="text-danger">(enseignée par ${matiere.enseignant})</small>` : ''}
                                    </label>
                                </div>
                            </div>
                        `).join('')}
                    </div>
                `;
            }

            html += `
                    </div>
                    <div class="card-footer">
                        <button type="button" class="btn btn-primary" id="add-to-selection">
                            Ajouter à ma sélection
                        </button>
                    </div>
                </div>
            `;

            container.innerHTML = html;
            
            // Ajouter l'événement au bouton
            document.getElementById('add-to-selection').addEventListener('click', function() {
                addClassAndSubjectsToSelection(classeId, className);
            });
        })
        .catch(error => {
            console.error('Erreur:', error);
            container.innerHTML = `
                <div class="alert alert-danger">
                    Une erreur est survenue lors du chargement des matières.
                </div>
            `;
        });
}

function addClassAndSubjectsToSelection(classeId, className) {
    // Récupérer les matières sélectionnées
    const selectedMatieres = [];
    document.querySelectorAll('.matiere-checkbox:checked').forEach(checkbox => {
        selectedMatieres.push({
            id: checkbox.dataset.matiereId,
            nom: checkbox.dataset.matiereNom
        });
    });
    
    if (selectedMatieres.length === 0) {
        alert('Veuillez sélectionner au moins une matière');
        return;
    }
    
    // Ajouter à notre objet de stockage
    selectedClassesAndSubjects[classeId] = {
        className: className,
        matieres: selectedMatieres
    };
    
    // Mettre à jour l'affichage
    updateSelectedClassesDisplay();
    
    // Réinitialiser le sélecteur de classe
    document.getElementById('classes').value = '';
    document.getElementById('matieres-container').innerHTML = '';
}

function updateSelectedClassesDisplay() {
    const container = document.getElementById('classes-matieres-list');
    let html = '';
    
    Object.keys(selectedClassesAndSubjects).forEach(classeId => {
        const classe = selectedClassesAndSubjects[classeId];
        
        html += `
            <div class="list-group-item">
                <div class="d-flex justify-content-between align-items-center">
                    <h6>${classe.className}</h6>
                    <button type="button" class="btn btn-sm btn-danger remove-class" data-classe-id="${classeId}">
                        Supprimer
                    </button>
                </div>
                <div>
                    <input type="hidden" name="classe_id[]" value="${classeId}">
                    <ul class="list-unstyled ms-3">
                        ${classe.matieres.map(matiere => `
                            <li>
                                ${matiere.nom}
                                <input type="hidden" name="matieres[${classeId}][]" value="${matiere.id}">
                            </li>
                        `).join('')}
                    </ul>
                </div>
            </div>
        `;
    });
    
    container.innerHTML = html;
    
    // Ajouter les événements pour les boutons de suppression
    document.querySelectorAll('.remove-class').forEach(button => {
        button.addEventListener('click', function() {
            const classeId = this.dataset.classeId;
            delete selectedClassesAndSubjects[classeId];
            updateSelectedClassesDisplay();
        });
    });
}

// Validation du formulaire
document.querySelector('form').addEventListener('submit', function(e) {
    if (Object.keys(selectedClassesAndSubjects).length === 0) {
        e.preventDefault();
        alert('Veuillez sélectionner au moins une classe avec des matières');
    }
});
</script>

<?php include 'footer.php'; ?>
