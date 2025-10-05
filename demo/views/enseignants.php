<?php
require_once __DIR__ . '/../config/config.php';
require_once CONFIG_PATH . '/database.php';
require_once MODELS_PATH . '/Utilisateur.php';
require_once __DIR__ . '/../includes/auth.php';

// Debug de la session
error_log("Session dans enseignants.php : " . print_r($_SESSION, true));

// Vérifier que l'utilisateur est connecté et a le bon rôle
checkRole(['admin', 'directeur']);

// Initialiser la connexion à la base de données
$db = Database::getInstance();
$utilisateur = new Utilisateur($db);

// Récupérer tous les enseignants
$enseignants = $utilisateur->getAllEnseignants();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Enseignants</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .navbar {
            background-color: #007bff;
        }
        .navbar-brand, .nav-link {
            color: white !important;
        }
        .status-pending {
            color: orange;
        }
        .status-approved {
            color: green;
        }
        .status-rejected {
            color: red;
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>

    <div class="mt-5">
        <h2 class="mb-4">Liste des Enseignants en Attente de Validation</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Email</th>
                    <th>Matières</th>
                    <th>Classes</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($enseignants as $enseignant): ?>
                <tr>
                    <td><?= htmlspecialchars($enseignant['nom']) ?></td>
                    <td><?= htmlspecialchars($enseignant['prenom']) ?></td>
                    <td><?= htmlspecialchars($enseignant['email']) ?></td>
                    <td>
                        <?php 
                        $matieres = $utilisateur->getMatieresEnseignant($enseignant['id']);
                        echo htmlspecialchars(implode(', ', array_column($matieres, 'nom_matiere')));
                        ?>
                    </td>
                    <td>
                        <?php 
                        $classes = $utilisateur->getClassesEnseignant($enseignant['id']);
                        echo htmlspecialchars(implode(', ', array_column($classes, 'nom_classe')));
                        ?>
                    </td>
                    <td class="status-<?= $enseignant['statut'] ?>">
                        <?= htmlspecialchars($enseignant['statut']) ?>
                    </td>
                    <td>
                        <form method="POST" action="update_status.php" class="d-inline">
                            <input type="hidden" name="enseignant_id" value="<?= $enseignant['id'] ?>">
                            <button type="submit" name="action" value="approve" class="btn btn-success btn-sm">
                                Approuver
                            </button>
                            <button type="submit" name="action" value="reject" class="btn btn-danger btn-sm">
                                Rejeter
                            </button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>




