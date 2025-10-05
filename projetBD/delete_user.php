<?php
// Connexion à la base de données
include "db.php";

// Vérifier si l'ID de l'utilisateur est passé en paramètre
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Récupérer l'ID de l'image associée à l'utilisateur
    $sql = "SELECT Id_Image FROM Utilisateur WHERE Id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $imageId = $user['Id_Image'];

        // Supprimer l'utilisateur de la table Utilisateur
        $deleteUserSql = "DELETE FROM Utilisateur WHERE Id = ?";
        $deleteStmt = $conn->prepare($deleteUserSql);
        $deleteStmt->bind_param("i", $id);
        $deleteStmt->execute();
        $deleteStmt->close();

        // Récupérer le chemin de l'image
        $imagePathSql = "SELECT Chemin FROM Image WHERE Id = ?";
        $imageStmt = $conn->prepare($imagePathSql);
        $imageStmt->bind_param("i", $imageId);
        $imageStmt->execute();
        $imageResult = $imageStmt->get_result();

        if ($imageResult->num_rows > 0) {
            $image = $imageResult->fetch_assoc();
            $imagePath = $image['Chemin'];

            // Supprimer le fichier image du serveur
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }

            // Supprimer l'enregistrement de l'image de la table Image
            $deleteImageSql = "DELETE FROM Image WHERE Id = ?";
            $deleteImageStmt = $conn->prepare($deleteImageSql);
            $deleteImageStmt->bind_param("i", $imageId);
            $deleteImageStmt->execute();
            $deleteImageStmt->close();
        }

        echo "Utilisateur et son image associés ont été supprimés avec succès.";
        header("Location: index.php");
    } else {
        echo "Utilisateur non trouvé.";
    }

    $stmt->close();
} else {
    echo "ID de l'utilisateur non spécifié.";
}

$conn->close();
?>
