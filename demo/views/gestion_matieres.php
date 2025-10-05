<?php
require_once __DIR__ . '/../config/config.php';
require_once CONFIG_PATH . '/database.php';
require_once MODELS_PATH . '/Utilisateur.php';
require_once __DIR__ . '/../includes/auth.php';

checkRole(['admin', 'directeur']);

$db = Database::getInstance();
$utilisateur = new Utilisateur($db);

// Récupérer toutes les classes
$classes = $utilisateur->getClasses();

// Récupérer la classe sélectionnée
$selectedClassId = isset($_GET['classe_id']) ? $_GET['classe_id'] : null;

// Récupérer les matières de la classe sélectionnée
$matieres = $selectedClassId ? $utilisateur->getMatieresByClasse($selectedClassId) : [];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Matières</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .btn-action { margin: 0 2px; }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>

    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Gestion des Matières</h2>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addMatiereModal">
                <i class="fas fa-plus"></i> Ajouter une matière
            </button>
        </div>

        <!-- Sélecteur de classe -->
        <div class="mb-4">
            <form method="GET" class="form-inline">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <label for="classe_select" class="mr-2">Sélectionner une classe:</label>
                    </div>
                    <div class="col-auto">
                        <select class="form-control" id="classe_select" name="classe_id" onchange="this.form.submit()">
                            <option value="">Choisir la classe</option>
                            <?php foreach ($classes as $classe): ?>
                                <option value="<?= $classe['id'] ?>" <?= $selectedClassId == $classe['id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($classe['nom_classe']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </form>
        </div>

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

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom de la matière</th>
                    <th>Classe</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($matieres as $matiere): ?>
                <tr>
                    <td><?= htmlspecialchars($matiere['id']) ?></td>
                    <td><?= htmlspecialchars($matiere['nom_matiere']) ?></td>
                    <td>
                        <?php
                        foreach ($classes as $classe) {
                            if ($classe['id'] == $selectedClassId) {
                                echo htmlspecialchars($classe['nom_classe']);
                                break;
                            }
                        }
                        ?>
                    </td>
                    <td>
                        <button class="btn btn-sm btn-warning btn-action" 
                                onclick="editMatiere(<?= $matiere['id'] ?>, '<?= htmlspecialchars($matiere['nom_matiere']) ?>', <?= $selectedClassId ?>)"
                                title="Modifier">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-sm btn-danger btn-action" 
                                onclick="deleteMatiere(<?= $matiere['id'] ?>)"
                                title="Supprimer">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Modal Ajout -->
    <div class="modal fade" id="addMatiereModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Ajouter une matière</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="traitement_matiere.php" method="POST">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="nom_matiere" class="form-label">Nom de la matière</label>
                            <input type="text" class="form-control" id="nom_matiere" name="nom_matiere" required>
                        </div>
                        <div class="mb-3">
                            <label for="classe_id" class="form-label">Classe</label>
                            <select class="form-control" id="classe_id" name="classe_id" required>
                                <option value="">Sélectionnez une classe</option>
                                <?php foreach ($classes as $classe): ?>
                                    <option value="<?= $classe['id'] ?>"><?= htmlspecialchars($classe['nom_classe']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <input type="hidden" name="action" value="add">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary">Ajouter</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Modification -->
    <div class="modal fade" id="editMatiereModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Modifier une matière</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="traitement_matiere.php" method="POST">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit_nom_matiere" class="form-label">Nom de la matière</label>
                            <input type="text" class="form-control" id="edit_nom_matiere" name="nom_matiere" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_classe_id" class="form-label">Classe</label>
                            <select class="form-control" id="edit_classe_id" name="classe_id" required>
                                <option value="">Sélectionnez une classe</option>
                                <?php foreach ($classes as $classe): ?>
                                    <option value="<?= $classe['id'] ?>"><?= htmlspecialchars($classe['nom_classe']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <input type="hidden" name="action" value="edit">
                        <input type="hidden" name="matiere_id" id="edit_matiere_id">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary">Modifier</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function editMatiere(id, nom, classeId) {
            document.getElementById('edit_matiere_id').value = id;
            document.getElementById('edit_nom_matiere').value = nom;
            document.getElementById('edit_classe_id').value = classeId;
            new bootstrap.Modal(document.getElementById('editMatiereModal')).show();
        }

        function deleteMatiere(id) {
            if (confirm('Êtes-vous sûr de vouloir supprimer cette matière ?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = 'traitement_matiere.php';
                
                const actionInput = document.createElement('input');
                actionInput.type = 'hidden';
                actionInput.name = 'action';
                actionInput.value = 'delete';
                
                const idInput = document.createElement('input');
                idInput.type = 'hidden';
                idInput.name = 'matiere_id';
                idInput.value = id;
                
                form.appendChild(actionInput);
                form.appendChild(idInput);
                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>
</body>
</html>

