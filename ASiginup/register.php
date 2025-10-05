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
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

        // Vérifiez si le nom d'utilisateur et l'email sont déjà pris
        $conn = new mysqli('localhost', 'root', '', 'user_management');
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo "Nom d'utilisateur ou adresse e-mail déjà utilisés.";
        } else {
            $stmt = $conn->prepare("INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $username, $email, $password);
            if ($stmt->execute()) {
                echo "Inscription réussie. Votre compte est en attente d'approbation.";
            } else {
                echo "Erreur lors de l\'inscription : " . $stmt->error;
            }
        }

        $stmt->close();
        $conn->close();
    }
    ?>
    <form action="register.php" method="post">
        <label for="username">Nom d'utilisateur:</label>
        <input type="text" id="username" name="username" required><br><br>

        <label for="email">Adresse e-mail:</label>
        <input type="email" id="email" name="email" required><br><br>

        <label for="password">Mot de passe:</label>
        <input type="password" id="password" name="password" required><br><br>

        <button type="submit">S'inscrire</button>
    </form>
</body>
</html>
