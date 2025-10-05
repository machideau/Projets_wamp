<?php
require_once 'includes/db.php';
require_once 'includes/header.php';

if (!isset($_GET['id'])) {
    echo "<p>Article introuvable.</p>";
    require_once 'includes/footer.php';
    exit;
}

$id = intval($_GET['id']);

// Récupérer l'article
$stmt = $conn->prepare("SELECT a.titre, a.contenu, a.date_publication, u.nom_utilisateur 
                        FROM articles a
                        LEFT JOIN utilisateurs u ON a.auteur_id = u.id
                        WHERE a.id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<p>Article non trouvé.</p>";
    require_once 'includes/footer.php';
    exit;
}

$article = $result->fetch_assoc();
?>

<article>
    <h2><?= htmlspecialchars($article['titre']) ?></h2>
    <p>Publié le <?= $article['date_publication'] ?> par <?= htmlspecialchars($article['nom_utilisateur']) ?></p>
    <p><?= nl2br(htmlspecialchars($article['contenu'])) ?></p>
</article>

<hr>

<h3>Commentaires</h3>
<?php
// Récupérer les commentaires
$com = $conn->prepare("SELECT contenu, auteur, date_commentaire FROM commentaires WHERE article_id = ? ORDER BY date_commentaire DESC");
$com->bind_param("i", $id);
$com->execute();
$resCom = $com->get_result();

if ($resCom->num_rows > 0):
    while ($commentaire = $resCom->fetch_assoc()):
?>
        <div class="commentaire">
            <p><strong><?= htmlspecialchars($commentaire['auteur']) ?></strong> le <?= $commentaire['date_commentaire'] ?></p>
            <p><?= nl2br(htmlspecialchars($commentaire['contenu'])) ?></p>
        </div>
<?php
    endwhile;
else:
    echo "<p>Aucun commentaire pour cet article.</p>";
endif;
?>

<hr>

<h3>Laisser un commentaire</h3>
<form action="ajouter_commentaire.php" method="post">
    <input type="hidden" name="article_id" value="<?= $id ?>">
    <input type="text" name="auteur" placeholder="Votre nom" required><br>
    <textarea name="contenu" placeholder="Votre commentaire" required></textarea><br>
    <button type="submit">Envoyer</button>
</form>

<?php require_once 'includes/footer.php'; ?>
