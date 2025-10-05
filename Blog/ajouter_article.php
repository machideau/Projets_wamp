<?php
require_once 'includes/db.php';
// require_once 'includes/auth.php';
require_once 'includes/header.php';

rediriger_si_non_admin();

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = trim($_POST['titre']);
    $contenu = trim($_POST['contenu']);
    $auteur_id = $_SESSION['utilisateur']['id'];

    if (!empty($titre) && !empty($contenu)) {
        $stmt = $conn->prepare("INSERT INTO articles (titre, contenu, auteur_id) VALUES (?, ?, ?)");
        $stmt->bind_param("ssi", $titre, $contenu, $auteur_id);
        if ($stmt->execute()) {
            $message = "Article publié avec succès.";
        } else {
            $message = "Erreur lors de la publication.";
        }
    } else {
        $message = "Tous les champs sont obligatoires.";
    }
}
?>

<h2>Ajouter un article</h2>
<?php if (!empty($message)) echo "<p>$message</p>"; ?>

<form method="post" action="">
    <input type="text" name="titre" placeholder="Titre de l'article" required><br>
    <textarea name="contenu" placeholder="Contenu" rows="10" required></textarea><br>
    <button type="submit">Publier</button>
</form>

<?php require_once 'includes/footer.php'; ?>
