<?php
require_once 'includes/db.php';
require_once 'includes/header.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom_utilisateur = trim($_POST['nom_utilisateur']);
    $mot_de_passe = $_POST['mot_de_passe'];
    $email = trim($_POST['email']);

    // Validation simple
    if (!empty($nom_utilisateur) && !empty($mot_de_passe)) {
        // Vérifier si le nom d'utilisateur existe déjà
        $stmt = $conn->prepare("SELECT id FROM utilisateurs WHERE nom_utilisateur = ?");
        $stmt->bind_param("s", $nom_utilisateur);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $message = "Nom d'utilisateur déjà pris.";
        } else {
            // Créer le compte
            $mot_de_passe_hash = password_hash($mot_de_passe, PASSWORD_DEFAULT);
            $role = 'user';

            $stmt = $conn->prepare("INSERT INTO utilisateurs (nom_utilisateur, mot_de_passe, email, role) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $nom_utilisateur, $mot_de_passe_hash, $email, $role);

            if ($stmt->execute()) {
                $message = "Compte créé avec succès. <a href='login.php'>Connectez-vous ici</a>.";
            } else {
                $message = "Erreur lors de l'inscription.";
            }
        }
    } else {
        $message = "Veuillez remplir tous les champs obligatoires.";
    }
}
?>

<h2>Créer un compte</h2>

<?php if (!empty($message)) : ?>
    <p><?= $message ?></p>
<?php endif; ?>

<form method="post" action="register.php">
    <input type="text" name="nom_utilisateur" placeholder="Nom d'utilisateur" required><br>
    <input type="email" name="email" placeholder="Adresse email (facultatif)"><br>
    <input type="password" name="mot_de_passe" placeholder="Mot de passe" required><br>
    <button type="submit">S'inscrire</button>
</form>

<?php require_once 'includes/footer.php'; ?>
