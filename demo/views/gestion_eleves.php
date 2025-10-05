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

// Récupérer les élèves de la classe sélectionnée
$eleves = $selectedClassId ? $utilisateur->getElevesByClasse($selectedClassId) : [];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Élèves</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <?php include 'header.php'; ?>

    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Gestion des Élèves</h2>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addEleveModal">
                <i class="fas fa-plus"></i> Ajouter un élève
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
                            <option value="">Toutes les classes</option>
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
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Email</th>
                    <th>Date de naissance</th>
                    <th>Classe</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($eleves as $eleve): ?>
                <tr>
                    <td><?= htmlspecialchars($eleve['id']) ?></td>
                    <td><?= htmlspecialchars($eleve['nom']) ?></td>
                    <td><?= htmlspecialchars($eleve['prenom']) ?></td>
                    <td><?= htmlspecialchars($eleve['email']) ?></td>
                    <td><?= htmlspecialchars($eleve['date_naissance']) ?></td>
                    <td><?= htmlspecialchars($eleve['nom_classe']) ?></td>
                    <td>
                        <button class="btn btn-sm btn-warning btn-action" 
                                onclick="editEleve(<?= $eleve['id'] ?>, '<?= htmlspecialchars($eleve['nom']) ?>', '<?= htmlspecialchars($eleve['prenom']) ?>', '<?= htmlspecialchars($eleve['email']) ?>', '<?= htmlspecialchars($eleve['date_naissance']) ?>', <?= $eleve['classe_id'] ?>)"
                                title="Modifier">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-sm btn-danger btn-action" 
                                onclick="deleteEleve(<?= $eleve['id'] ?>)"
                                title="Supprimer">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Modal Ajout Élève -->
    <div class="modal fade" id="addEleveModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Ajouter un élève</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="traitement_eleve.php" method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="action" value="add">
                        <div class="mb-3">
                            <label for="nom" class="form-label">Nom</label>
                            <input type="text" class="form-control" id="nom" name="nom" required>
                        </div>
                        <div class="mb-3">
                            <label for="prenom" class="form-label">Prénom</label>
                            <input type="text" class="form-control" id="prenom" name="prenom" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="date_naissance" class="form-label">Date de naissance</label>
                            <input type="date" class="form-control" id="date_naissance" name="date_naissance" required>
                        </div>
                        <div class="mb-3">
                            <label for="classe_id" class="form-label">Classe</label>
                            <select class="form-control" id="classe_id" name="classe_id" required>
                                <option value="">Choisir une classe</option>
                                <?php foreach ($classes as $classe): ?>
                                    <option value="<?= $classe['id'] ?>"><?= htmlspecialchars($classe['nom_classe']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                        <button type="submit" class="btn btn-primary">Ajouter</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function editEleve(id, nom, prenom, email, dateNaissance, classeId) {
            // À implémenter
        }

        function deleteEleve(id) {
            if (confirm('Êtes-vous sûr de vouloir supprimer cet élève ?')) {
                window.location.href = `traitement_eleve.php?action=delete&id=${id}`;
            }
        }
    </script>
</body>
</html>