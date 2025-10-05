<?php
session_start();
require_once 'includes/db.php';
// require_once 'includes/fonctions.php';
require_once 'includes/auth.php';

// Vérifie que l'utilisateur est admin
rediriger_si_non_admin();

// Vérifie qu’un ID d’article est passé
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: admin.php');
    exit;
}

$article_id = intval($_GET['id']);
$message = '';

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = trim($_POST['titre']);
    $contenu = trim($_POST['contenu']);

    if (!empty($titre) && !empty($contenu)) {
        $stmt = $conn->prepare("UPDATE articles SET titre = ?, contenu = ? WHERE id = ?");
        $stmt->bind_param("ssi", $titre, $contenu, $article_id);
        if ($stmt->execute()) {
            $message = "Article mis à jour avec succès.";
        } else {
            $message = "Erreur lors de la mise à jour.";
        }
    } else {
        $message = "Tous les champs sont obligatoires.";
    }
}

// Récupérer l'article existant
$stmt = $conn->prepare("SELECT titre, contenu FROM articles WHERE id = ?");
$stmt->bind_param("i", $article_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Article introuvable.";
    exit;
}

$article = $result->fetch_assoc();
?>

<?php require_once 'includes/header.php'; ?>

<div class="container">
    <h2>Modifier l'article</h2>

    <?php if (!empty($message)) : ?>
        <p><?= $message ?></p>
    <?php endif; ?>

    <form method="post">
        <div>
            <label for="titre">Titre :</label><br>
            <input type="text" id="titre" name="titre" value="<?= $article['titre'] ?>" required>
        </div>
        <div>
            <label for="contenu">Contenu :</label><br>
            <textarea id="contenu" name="contenu" rows="10" required><?= $article['contenu'] ?></textarea>
        </div>
        <button type="submit">Mettre à jour</button>
    </form>
</div>

<?php require_once 'includes/footer.php'; ?>
