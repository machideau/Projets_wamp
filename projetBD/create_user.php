<?php
// Connexion à la base de données
include "db.php";

// Vérifier si le formulaire est soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    // Gestion de l'image
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image_name = $_FILES['image']['name'];
        $image_tmp = $_FILES['image']['tmp_name'];
        $image_path = 'uploads/' . basename($image_name);
        
        if (move_uploaded_file($image_tmp, $image_path)) {
            // Insérer l'image dans la table Image et obtenir l'ID de l'image
            $stmt = $conn->prepare("INSERT INTO Image (Chemin) VALUES (?)");
            $stmt->bind_param("s", $image_path);
            $stmt->execute();
            $image_id = $stmt->insert_id;
            $stmt->close();

            // Insérer l'utilisateur dans la table Utilisateur
            $stmt = $conn->prepare("INSERT INTO Utilisateur (Nom, Prenom, Email, Role, Id_Image, Password) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssssi", $nom, $prenom, $email, $role, $image_id, $password);
            $w = $stmt->execute();
            $stmt->close();

            if ($w) {
                echo $nom, $prenom, $email;
            }
            // echo "Utilisateur ajouté avec succès.";
            // header("Location: index.php");
        } else {
            echo "Erreur lors du téléchargement de l'image.";
        }
    } else {
        echo "Veuillez fournir une image valide.";
    }
}

$conn->close();
?>
