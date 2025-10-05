<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Système de S\'inscription</title>
</head>
<body>
    <?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Vérifiez les informations d'identification
        $conn = new mysqli('localhost', 'root', '', 'user_management');
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password_hash'])) {
                // Vérifiez si le compte est accepté par l'amministrateur
                if ($user['status'] === 'approved') {
                    echo "Bienvenue, " . $username;
                    // Redirigez vers la page d'accueil enregistrée ici
                } else {
                    echo "Votre compte n\'a pas encore été approuvé.";
                }
            } else {
                echo "Nom d'utilisateur ou mot de passe incorrecte.";
            }
        } else {
            echo "Nom d'utilisateur ou mot de passe incorrecte.";
        }

        $stmt->close();
        $conn->close();
    }
    ?>
    <form action="login.php" method="post">
        <label for="username">Nom d'utilisateur:</label>
        <input type="text" id="username" name="username" required><br><br>

        <label for="password">Mot de passe:</label>
        <input type="password" id="password" name="password" required><br><br>

        <button type="submit">Se connecter</button>
    </form>
</body>
</html>
