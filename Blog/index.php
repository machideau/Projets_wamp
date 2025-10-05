<?php
require_once 'includes/db.php';
require_once 'includes/header.php';

// Vérifier si c'est la première visite
if (!isset($_COOKIE['first_visit'])) {
    // Définir le cookie pour 2 minutes
    setcookie('first_visit', 'true', time() + 120, '/');
    
    // Rediriger vers register.php
    header('Location: register.php');
    exit();
}

// Récupérer les articles
$sql = "SELECT * FROM articles ORDER BY date_creation DESC";
$result = $conn->query($sql);
?>

<h2>Derniers Articles</h2>

<?php if ($result->num_rows > 0): ?>
    <?php while($article = $result->fetch_assoc()): ?>
        <article>
            <h3><a href="article.php?id=<?= $article['id'] ?>"><?= htmlspecialchars($article['titre']) ?></a></h3>
            <p class="date">Publié le <?= date('d/m/Y', strtotime($article['date_creation'])) ?></p>
            <p><?= htmlspecialchars(substr($article['contenu'], 0, 200)) ?>...</p>
            <a href="article.php?id=<?= $article['id'] ?>">Lire la suite</a>
        </article>
    <?php endwhile; ?>
<?php else: ?>
    <p>Aucun article disponible.</p>
<?php endif; ?>

<?php require_once 'includes/footer.php'; ?>