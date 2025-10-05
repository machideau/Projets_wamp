<?php
// session_start();
require_once 'includes/db.php';
require_once 'includes/auth.php';
require_once 'includes/fonctions.php';
require_once 'includes/header.php';

// Vérifie que l'utilisateur est admin
rediriger_si_non_admin();

// Requête pour récupérer tous les articles avec les auteurs
$sql = "SELECT a.id, a.titre, a.date_publication, u.nom_utilisateur
        FROM articles a
        LEFT JOIN utilisateurs u ON a.auteur_id = u.id
        ORDER BY a.date_publication DESC";

$result = $conn->query($sql);
?>

<div class="container">
    <h2>Tableau de bord - Administration</h2>

    <p><a href="ajouter_article.php">➕ Ajouter un article</a></p>

    <?php if ($result && $result->num_rows > 0): ?>
        <table border="1" cellpadding="8" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>Titre</th>
                    <th>Auteur</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= e($row['titre']) ?></td>
                        <td><?= e($row['nom_utilisateur']) ?></td>
                        <td><?= e($row['date_publication']) ?></td>
                        <td>
                            <a href="modifier_article.php?id=<?= $row['id'] ?>">✏️ Modifier</a> |
                            <a href="supprimer_article.php?id=<?= $row['id'] ?>" onclick="return confirm('Supprimer cet article ?');">🗑️ Supprimer</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <?php else: ?>
        <p>Aucun article trouvé.</p>
    <?php endif; ?>
</div>

<?php require_once 'includes/footer.php'; ?>
