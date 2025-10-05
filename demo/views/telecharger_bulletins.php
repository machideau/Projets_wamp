<?php
require_once __DIR__ . '/../config/config.php';
require_once CONFIG_PATH . '/database.php';
require_once MODELS_PATH . '/Utilisateur.php';
require_once __DIR__ . '/../includes/auth.php';

checkRole(['admin', 'directeur']);

$db = Database::getInstance();
$utilisateur = new Utilisateur($db);

$classes = $utilisateur->getClasses();
$trimestre_selectionne = isset($_GET['trimestre']) ? $_GET['trimestre'] : null;
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Téléchargement des Bulletins</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include 'header.php'; ?>

    <div class="container mt-4">
        <h2>Téléchargement des Bulletins</h2>

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

        <!-- Afficher les dossiers de bulletins existants s'il y en a -->
        <?php
        $bulletins_dir = ROOT_PATH . '/bulletins';
        if (file_exists($bulletins_dir)) {
            $classe_dirs = glob($bulletins_dir . '/*', GLOB_ONLYDIR);
            if (!empty($classe_dirs)) {
                echo '<div class="card mb-4">
                        <div class="card-header">
                            <h4>Bulletins générés précédemment</h4>
                        </div>
                        <div class="card-body">
                            <ul class="list-group">';
                
                foreach ($classe_dirs as $dir) {
                    $dir_name = basename($dir);
                    $pdf_count = count(glob($dir . '/*.pdf'));
                    echo '<li class="list-group-item d-flex justify-content-between align-items-center">
                            ' . $dir_name . '
                            <span class="badge bg-primary rounded-pill">' . $pdf_count . ' bulletin(s)</span>
                          </li>';
                }
                
                echo '</ul>
                      <p class="mt-3 text-muted">Ces bulletins sont stockés sur le serveur et accessibles aux administrateurs.</p>
                    </div>
                </div>';
            }
        }
        ?>

        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" class="mb-4">
                    <div class="mb-3">
                        <label for="trimestre" class="form-label">Sélectionner le trimestre</label>
                        <select class="form-control" id="trimestre" name="trimestre" required onchange="this.form.submit()">
                            <option value="">Choisir un trimestre</option>
                            <option value="1" <?= $trimestre_selectionne == '1' ? 'selected' : '' ?>>Premier Trimestre</option>
                            <option value="2" <?= $trimestre_selectionne == '2' ? 'selected' : '' ?>>Deuxième Trimestre</option>
                            <option value="3" <?= $trimestre_selectionne == '3' ? 'selected' : '' ?>>Troisième Trimestre</option>
                        </select>
                    </div>
                </form>

                <?php if ($trimestre_selectionne): ?>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Classe</th>
                                    <th>Nombre d'élèves</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($classes as $classe): 
                                    $nbEleves = $utilisateur->getNombreElevesParClasse($classe['id']);
                                ?>
                                <tr>
                                    <td><?= htmlspecialchars($classe['nom_classe']) ?></td>
                                    <td><?= $nbEleves ?></td>
                                    <td>
                                        <a href="generer_bulletins.php?classe_id=<?= $classe['id'] ?>&trimestre=<?= $trimestre_selectionne ?>" 
                                           class="btn btn-primary btn-sm">
                                            Télécharger les bulletins
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>