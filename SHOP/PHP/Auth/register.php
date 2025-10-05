<?php
session_start();
include "../config/db.php";

$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($db, $_POST['username']);
    $email = mysqli_real_escape_string($db, $_POST['email']);
    $password = mysqli_real_escape_string($db, $_POST['password']);
    $confirm_password = mysqli_real_escape_string($db, $_POST['confirm_password']);
    $phone = mysqli_real_escape_string($db, $_POST['phone']);
    $type = mysqli_real_escape_string($db, $_POST['type']);
    
    // Validation
    if (empty($username)) { $errors[] = "Nom d'utilisateur requis"; }
    if (empty($email)) { $errors[] = "Email requis"; }
    if (empty($password)) { $errors[] = "Mot de passe requis"; }
    if ($password != $confirm_password) { $errors[] = "Les mots de passe ne correspondent pas"; }
    
    // Vérifier si l'email existe déjà
    $check_email = "SELECT * FROM users WHERE email='$email' LIMIT 1";
    $result = mysqli_query($db, $check_email);
    if (mysqli_num_rows($result) > 0) {
        $errors[] = "Cet email est déjà utilisé";
    }
    
    // Traitement de l'image
    $image = "";
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $filename = $_FILES['image']['name'];
        $filetype = pathinfo($filename, PATHINFO_EXTENSION);
        
        if (in_array(strtolower($filetype), $allowed)) {
            $new_name = time() . '.' . $filetype;
            $destination = '../../uploads/profiles/' . $new_name;
            
            if (move_uploaded_file($_FILES['image']['tmp_name'], $destination)) {
                $image = $new_name;
            } else {
                $errors[] = "Erreur lors du téléchargement de l'image";
            }
        } else {
            $errors[] = "Format d'image non autorisé";
        }
    }
    
    // Inscription si pas d'erreurs
    if (empty($errors)) {
        $password = password_hash($password, PASSWORD_DEFAULT);
        $query = "INSERT INTO users (username, email, password, phone, type, image) 
                  VALUES ('$username', '$email', '$password', '$phone', '$type', '$image')";
        
        if (mysqli_query($db, $query)) {
            $_SESSION['success'] = "Inscription réussie! Vous pouvez maintenant vous connecter.";
            header('location: ../../Auth/login.html');
            echo 'well';
            exit();
        } else {
            // $errors[] = "Erreur lors de l'inscription: " . mysqli_error($db);
            echo "Erreur lors de l'inscription: " . mysqli_error($db);
        }
    } else {
        for ($i = 0; $i < count($errors); $i++) { 
            echo $errors[$i];
        }
        header('location: ../../Auth/register.html');
    }
    
}
?>
