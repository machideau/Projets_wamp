<?php
include 'db.php'; // Inclut le fichier de connexion à la base de données
session_start(); // Démarre une session

if ($_SERVER['REQUEST_METHOD'] == 'POST') { // Vérifie si la requête est une méthode POST
    $email = $_POST['email']; // Récupère l'email depuis le formulaire
    $password = $_POST['password']; // Récupère le mot de passe depuis le formulaire

    // Prépare la requête
    $stmt = $conn->prepare("SELECT * FROM utilisateur WHERE Email = ?"); // Prépare la requête SQL
    if (!$stmt) {
        die("Erreur de préparation : " . $conn->error); // Vérifie si la préparation a échoué
    }

    $stmt->bind_param("s", $email); // Lie le paramètre (s pour string)
    $stmt->execute(); // Exécute la requête
    $result = $stmt->get_result(); // Récupère le résultat

    // Récupère l'utilisateur
    $user = $result->fetch_assoc(); // Récupère les résultats sous forme de tableau associatif

    // Vérifie les informations de l'utilisateur
    if ($user && password_verify($password, $user['Password'])) { // Vérifie si l'utilisateur existe et si le mot de passe est correct
        $_SESSION['user_id'] = $user['Id']; // Stocke l'ID de l'utilisateur en session
        $_SESSION['role'] = $user['Role']; // Stocke le rôle de l'utilisateur en session
        header('Location: index.php'); // Redirige vers la page d'accueil
        exit(); // Termine le script pour s'assurer qu'aucune autre sortie n'est envoyée
    } else {
        $error = "Email ou mot de passe incorrect."; // Gère l'erreur si les informations ne sont pas valides
    }

    $stmt->close(); // Ferme la requête préparée
}
?>



<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Connexion</title>
</head>
<body class="container mt-5">

<body class="bg-light">
    <div class="container ">
        <div class="row mt-5">
            <div class="col-lg-4 bg-white m-auto rounded-top">
                <h2 class="text-center"> Connexion</h2>
                <p class="text-center text-muted lead"> Se connecter</p>

                <form method="POST">
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger"><?= $error ?></div>
                    <?php endif; ?>
                    <div class="input-group  mb-3">
                        <span class="input-group-text">
                            <i class="fa fa-envelope"></i> 
                        </span>
                        <input type="text" class="form-control" name="email" id="email" placeholder="Email" required>
                    </div>
                
                    <div class="input-group  mb-3">
                        <span class="input-group-text">
                            <i class="fa fa-lock"></i> 
                        </span>
                        <input type="password" name="password" id="password" class="form-control" placeholder="Mot de passe " required>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Se connecter</button>
                       
                        <p class="text-center">
                              vous n'avez pas de compte ?<a href="register.php"> Inscription </a>
                        </p>
                    </div>
                </form>

            </div>
        </div>
    </div>

</body>
</html>
