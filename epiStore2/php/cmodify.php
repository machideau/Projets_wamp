<?php
// session_start();
$produit_update = $_SESSION['produit_update'];

if (isset($_POST['valider'])) {
    // Assuming $db is already defined and connected to the database
    $nom_update = mysqli_real_escape_string($db, $_POST['nom_update']);
    $prix_update = mysqli_real_escape_string($db, $_POST['prix_update']);
    $description_update = mysqli_real_escape_string($db, $_POST['description_update']);

    $rer_up = mysqli_query($db, "UPDATE `produits` SET nom = '$nom_update', prix = '$prix_update', description = '$description_update' WHERE id_produits = '$produit_update'") or die('Query failed');

    // Update profile image if a new one is uploaded
    $image_update = $_FILES['image_update']['name'];
    $image_size_update = $_FILES['image_update']['size'];
    $image_tmp_name_update = $_FILES['image_update']['tmp_name'];
    $image_folder_update = 'produit-images/' . $image_update;

    if (!empty($image_update)) {
        if ($image_size_update > 2000000 && $image_size_update < 1555200) {
            $message[] = 'Image trop grande!';
        } else {
            $stmt = $db->prepare("UPDATE `produits` SET image = ? WHERE id_produits = ?");
            $stmt->bind_param("si", $image_update, $produit_update);
            $stmt->execute();
            if ($stmt->affected_rows > 0) {
                if (move_uploaded_file($image_tmp_name_update, $image_folder_update)) {
                    // $message[] = 'Registered successfully!!!';
                    header('location: modifier.php');
                } else {
                    $message[] = '<br><div style="color:red">Echec de modification du produit !</div>';
                }
            } else {
                $message[] = '<br><div style="color:red">Echec de modification du produit !</div>';
            }
            $stmt->close();
            
        }
    }

    if (isset($rer_up) || (isset($stmt) || $stmt->affected_rows > 0)) {
        $message[] = '<br><div style="color:green">Modification du produit reussie !</div>';
        // unset($_SESSION['produit_update']);
        // session_destroy();
        // header('Location: index.php');
    } else {
        $message[] = '<br><div style="color:red">Echec de modification du produit !</div>';
    }

    // Print messages for debugging
    // echo '<pre>';
    // print_r($message);
    // echo '</pre>';
}
?>
