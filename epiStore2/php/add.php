<?php
    include '../php/db.php';
    // session_start();
    // $id = $_SESSION['idA'];

    if (isset($_POST['ajouter'])) {
        $nom = mysqli_real_escape_string($db, $_POST['nom']);
        $prix = mysqli_real_escape_string($db, $_POST['prix']);
        $description = mysqli_real_escape_string($db, $_POST['description']);
        $image = $_FILES['image']['name'];

        $image_size = $_FILES['image']['size'];
        $image_tmp_name = $_FILES['image']['tmp_name'];
        $image_folder = 'produit-images/' . $image;

        // Check if the product with the same name and image already exists in the database
        $select = mysqli_query($db, "SELECT * FROM `produits` WHERE nom = '$nom' AND image = '$image'") or die('Query failed');

        if (mysqli_num_rows($select) > 0) {
            $message[] = '<div class="alert" style="color:red">' .$nom. ' existe deja !!!</div>';
        } else {
            // Verify the image size
            if ($image_size > 2000000 && $image_size < 1555200) {
                $message[] = '<div class="alert" style="color:red">Image trop grande!!!</div>';
            } else {
                // Insert the product data into the database
                $stmt = $db->prepare("INSERT INTO `produits` (nom, prix, description, image) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("siss", $nom, $prix, $description, $image);
                $stmt->execute();
                
                // Check if the insertion was successful and move the uploaded image to the designated folder
                if ($stmt->affected_rows > 0) {
                    if (move_uploaded_file($image_tmp_name, $image_folder)) {
                        $message[] = '<div class="alert" style="color:green">Enregistrement du produit reussie !!!</div>';
                    } else {
                        $message[] = '<div class="alert" style="color:red">Echec d\'envoie de l\'image!</div>';
                    }
                } else {
                    $message[] = '<div class="alert" style="color:red">Echec de l\'enregistrement du produit !!!</div>';
                }
                $stmt->close();
            }
        }
    }

    // Display messages for debugging
    if (isset($message)) {
        foreach ($message as $msg) {
            echo $msg ;
        }
    }
?>
