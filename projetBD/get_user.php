<?php
// Connexion à la base de données
include "db.php";

// Vérifier si l'ID de l'utilisateur est passé en paramètre
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Requête pour récupérer les informations de l'utilisateur
    $sql = "SELECT u.Id, u.Nom, u.Prenom, u.Email, u.Role, i.Chemin AS Chemin
            FROM Utilisateur u
            LEFT JOIN Image i ON u.Id_Image = i.Id
            WHERE u.Id = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Vérifier si l'utilisateur existe
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        echo json_encode($user);
    } else {
        echo json_encode(["error" => "Utilisateur non trouvé."]);
    }

    $stmt->close();
} else {
    echo json_encode(["error" => "ID non spécifié."]);
}

$conn->close();
?>
