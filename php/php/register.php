<?php
include "db.php";
if (isset($_POST['submit'])) {
    $name = $_POST['nom'];
    $email = $_POST['email'];
    $pswd = $_POST['password'];

    // verifier si l'email n'est pas deja inscrit
    if (!empty($email)) {
        $select = mysqli_query($db, "SELECT * FROM `users` WHERE email = '$email'") or die('query failed in');
        // $select1 = mysqli_fetch_assoc($select);
        // print_r( $select);
        // print_r($select1);

        if (mysqli_num_rows($select) !== 0) {
            echo 'Email deja inscrit';
            header('location: ../register.html');
        } else {
            // Gestion de l'image de profil
            $profile_image = 'default-avatar.png'; // Image par défaut
            
            if(isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === 0) {
                $allowed = ['jpg', 'jpeg', 'png', 'gif'];
                $filename = $_FILES['profile_image']['name'];
                $file_extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
                
                if(in_array($file_extension, $allowed)) {
                    // Créer un nom de fichier unique
                    $new_filename = uniqid('profile_') . '.' . $file_extension;
                    $upload_path = '../uploads/profiles/' . $new_filename;
                    // $tmp = $_FILES['profile_image']['tmp_name'];

                    // echo $tmp,$upload_path;
                    
                    // Déplacer le fichier uploadé
                    if(move_uploaded_file($_FILES['profile_image']['tmp_name'], $upload_path)) {
                        $profile_image = $new_filename;
                    }
                }
            }

            if (!empty($email) && !empty($pswd)) {
                $insert = mysqli_query($db, "INSERT INTO `users`(name, email, password, profile_image) 
                                        VALUES ('$name', '$email', '$pswd', '$profile_image')") or die('query failed');

                if ($insert) {
                    header('location: ../login.html');
                } else {
                    echo 'incorrect email or password !!!';
                    header('location: ../login.html');
                }
            } else {
                header('location: ../login.html');
            }
        }
    }    
}
?>
