<?php
require_once 'includes/db.php';
require_once 'includes/header.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom_utilisateur = trim($_POST['nom_utilisateur']);
    $mot_de_passe = $_POST['mot_de_passe'];

    if (!empty($nom_utilisateur) && !empty($mot_de_passe)) {
        $stmt = $conn->prepare("SELECT id, nom_utilisateur, mot_de_passe, role FROM utilisateurs WHERE nom_utilisateur = ?");
        $stmt->bind_param("s", $nom_utilisateur);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows === 1) {
            $utilisateur = $result->fetch_assoc();

            if (password_verify($mot_de_passe, $utilisateur['mot_de_passe'])) {
                $_SESSION['utilisateur'] = [
                    'id' => $utilisateur['id'],
                    'nom_utilisateur' => $utilisateur['nom_utilisateur'],
                    'role' => $utilisateur['role']
                ];
                header('Location: index.php');
                exit;
            } else {
                $message = "Mot de passe incorrect.";
            }
        } else {
            $message = "Nom d'utilisateur non trouvé.";
        }
    } else {
        $message = "Veuillez remplir tous les champs.";
    }
}
?>

<h2>Connexion</h2>

<?php if (!empty($message)) : ?>
    <p><?= $message ?></p>
<?php endif; ?>

<form method="post" action="login.php">
    <input type="text" name="nom_utilisateur" placeholder="Nom d'utilisateur" required><br>
    <input type="password" name="mot_de_passe" placeholder="Mot de passe" required><br>
    <button type="submit">Se connecter</button>
</form>

<p>Pas encore inscrit ? <a href="register.php">Créer un compte</a></p>

<?php require_once 'includes/footer.php'; ?>
