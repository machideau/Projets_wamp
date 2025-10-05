<?php
session_start();
include "../config/db.php";

$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($db, $_POST['email']);
    $password = mysqli_real_escape_string($db, $_POST['password']);
    
    // Validation
    if (empty($email)) { $errors[] = "Email requis"; }
    if (empty($password)) { $errors[] = "Mot de passe requis"; }
    
    if (empty($errors)) {
        $query = "SELECT * FROM users WHERE email='$email' AND status='active' LIMIT 1";
        $result = mysqli_query($db, $query);
        
        if (mysqli_num_rows($result) == 1) {
            $user = mysqli_fetch_assoc($result);
            
            if (password_verify($password, $user['password'])) {
                // Connexion réussie
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['type'] = $user['type'];
                $_SESSION['role'] = $user['role'];
                
                // Redirection selon le type d'utilisateur
                if ($user['role'] == 'admin') {
                    header('location: ../../admin/dashboard.html');
                } else {
                    if ($user['type'] == 'seller') {
                        header('location: ../../seller_index.html');
                    } else {
                        header('location: ../../index.html');
                    }
                }
                exit();
            } else {
                $errors[] = "Email ou mot de passe incorrect";
            }
        } else {
            $errors[] = "Email ou mot de passe incorrect";
        }
    }
}
?>
