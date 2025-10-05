<?php
session_start();
include "db.php";

if (isset($_POST['modify'])) {

    $name = $_POST['nom'];
    $email = $_POST['email'];
    $odl_pswd = $_POST['old_password'];
    $new_pswd = $_POST['new_password'];

    if ($odl_pswd === $new_pswd && !empty($odl_pswd) && !empty($new_pswd)) {

        if(isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === 0) {
            $allowed = ['jpg', 'jpeg', 'png', 'gif'];
            $filename = $_FILES['profile_image']['name'];
            $file_extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
            
            if(in_array($file_extension, $allowed)) {
                // Créer un nom de fichier unique
                $new_filename = uniqid('profile_') . '.' . $file_extension;
                $upload_path = '../uploads/profiles/' . $new_filename;
                
                // Déplacer le fichier uploadé
                if(move_uploaded_file($_FILES['profile_image']['tmp_name'], $upload_path)) {
                    $profile_image = $new_filename;
                }
            }
            
            if ($profile_image) {
                $update = mysqli_query($db, "UPDATE `users` SET name = '$name', email = '$email', password = '$new_pswd', profile_image = '$profile_image' WHERE email = '$email'") or die('Query failed');
            }
        } else {
            $update = mysqli_query($db, "UPDATE `users` SET name = '$name', email = '$email', password = '$new_pswd' WHERE email = '$email'") or die('Query failed');
        }

        if ($update) {
            header('location: ../index.php');
        } else {
            echo 'update unsuccesfull !!!';
            // header('location: ../login.html');
        }
    }

}
?>