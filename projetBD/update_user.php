<?php
session_start();
include "db.php";

header('Content-Type: application/json');

if (isset($_POST['id'])) {
    $id = $_POST['id'];
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $response = ["error" => false, "message" => ""];

    // Gestion de l'image
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image_name = $_FILES['image']['name'];
        $image_tmp = $_FILES['image']['tmp_name'];
        $image_path = 'uploads/' . basename($image_name);
        
        // Déplacer l'image et vérifier le succès
        if (move_uploaded_file($image_tmp, $image_path)) {
            $stmt = $conn->prepare("INSERT INTO Image (Chemin) VALUES (?)");
            $stmt->bind_param("s", $image_path);
            $stmt->execute();
            $image_id = $stmt->insert_id;
            $stmt->close();

            // Mettre à jour avec l'image
            $stmt = $conn->prepare("UPDATE utilisateur SET Nom = ?, Prenom = ?, Email = ?, Role = ?, Id_Image = ? WHERE Id = ?");
            $stmt->bind_param("ssssii", $nom, $prenom, $email, $role, $id, $id);
        } else {
            $response["error"] = true;
            $response["message"] = "Erreur lors du téléchargement de l'image.";
            echo json_encode($response);
            exit;
        }
    } else {
        // Mettre à jour sans changer l'image
        $stmt = $conn->prepare("UPDATE Utilisateur SET Nom = ?, Prenom = ?, Email = ?, Role = ? WHERE Id = ?");
        $stmt->bind_param("ssssi", $nom, $prenom, $email, $role, $id);
    }

    // Exécution de la mise à jour et vérification de l'état
    if ($stmt->execute()) {
        $response["message"] = "Utilisateur mis à jour avec succès.";
        header("Location: login.php");
    } else {
        $response["error"] = true;
        $response["message"] = "Erreur lors de la mise à jour de l'utilisateur.";
    }

    $stmt->close();
    echo json_encode($response);
} else {
    echo json_encode(["error" => true, "message" => "Aucune donnée reçue."]);
}

$conn->close();
