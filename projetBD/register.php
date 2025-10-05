<?php
include 'db.php'; // Inclut le fichier de connexion à la base de données

if ($_SERVER['REQUEST_METHOD'] == 'POST') { // Vérifie si la requête est une méthode POST
    $nom = $_POST['nom']; // Récupère le nom
    $prenom = $_POST['prenom']; // Récupère le prénom
    $email = $_POST['email']; // Récupère l'email
    $role = $_POST['role']; // Récupère le rôle
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hache le mot de passe

    // Gestion de l'image uploadée
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image_name = $_FILES['image']['name']; // Récupère le nom du fichier image
        $image_tmp = $_FILES['image']['tmp_name']; // Récupère le chemin temporaire de l'image
        $image_folder = 'uploads/'; // Dossier de destination
        $image_path = $image_folder . time() . '_' . $image_name; // Crée un chemin unique pour l'image

        // Déplacer le fichier dans le dossier "uploads"
        if (move_uploaded_file($image_tmp, $image_path)) {
            // Insérer l'image dans la table Image
            $stmt = $conn->prepare("INSERT INTO Image (Chemin) VALUES (?)"); // Prépare la requête d'insertion
            $stmt->bind_param("s", $image_path); // Lie le paramètre
            if (!$stmt->execute()) {
                die("Erreur d'insertion de l'image : " . $stmt->error); // Vérifie si l'insertion a échoué
            }
            $image_id = $conn->insert_id; // Récupère l'ID de l'image insérée

            // Insérer les informations de l'utilisateur dans la table Utilisateur
            $stmt = $conn->prepare("INSERT INTO Utilisateur (Nom, Prenom, Email, Role, Password, Id_Image) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssssi", $nom, $prenom, $email, $role, $password, $image_id); // Lie les paramètres
            if (!$stmt->execute()) {
                die("Erreur d'insertion de l'utilisateur : " . $stmt->error); // Vérifie si l'insertion a échoué
            }

            header('Location: login.php'); // Redirige vers la page de connexion
            exit(); // Termine le script pour éviter d'envoyer d'autres sorties
        } else {
            $error = "Erreur lors du téléchargement de l'image."; // Gère l'erreur de téléchargement
        }
    } else {
        $error = "Veuillez sélectionner une image."; // Gère l'erreur d'absence d'image
    }
}
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Enregistrement</title>
</head>
<body class="bg-light">
    <div class="container ">
        <div class="row mt-5">
            <div class="col-lg-4 bg-white m-auto rounded-top">
                <h2 class="text-center"> Inscription</h2>
                <p class="text-center text-muted lead"> Simple et Rapide </p>

                <form method="POST" enctype="multipart/form-data">
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger"><?= $error ?></div>
                    <?php endif; ?>
                    <div class="input-group  mb-3">
                        <span class="input-group-text">
                            <i class="fa fa-user">
                            </i> 
                        </span>
                        <input type="text" class="form-control" name="nom" id="nom" required placeholder="Nom">
                    </div>
                    <div class="input-group  mb-3">
                        <span class="input-group-text">
                            <i class="fa fa-user">
                            </i> 
                        </span>
                        <input type="text" class="form-control" name="prenom" id="prenom" required placeholder="Prenom">
                    </div>
                    <div class="input-group  mb-3">
                        <span class="input-group-text">
                            <i class="fa fa-envelope">
                            </i> 
                        </span>
                        <input type="email" class="form-control" name="email" id="email" required placeholder="Email">
                    </div>
                    <div class="input-group  mb-3">
                        <span class="input-group-text">
                            <i class="fa fa-lock">
                            </i> 
                        </span>
                        <input type="password" class="form-control" name="password" id="password" required placeholder="Mot de passe">
                    </div>
                    <div class="input-group mb-3">
                        <select name="role" class="form-select" id="role" required>
                            <option>Admin / Utilisateur</option>
                            <option value="Admin">Admin</option>
                            <option value="Utilisateur">Utilisateur</option>
                        </select>
                    </div>
                    <div class="input-group mb-3">
                        <input type="file" name="image" id="image" class="form-control" accept="image/*" required>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">S’inscrire</button>
                        <p class="text-center text-muted mt-3">
                            En cliquant sur S’inscrire, vous acceptez nos <a href="#">  Conditions générales</a>, notre <a href=""> Politique de confidentialité </a> et notre <a href="#">  Politique d’utilisation</a> des cookies. 
                        </p>
                        <p class="text-center">
                             Avez vous déjà un compte ?<a href="login.php"> Connexion </a>
                        </p>
                    </div>
                </form>

            </div>
        </div>
    </div> 
</body>
</html>
