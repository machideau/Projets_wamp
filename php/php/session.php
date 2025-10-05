<?php
session_start();
include "db.php";

function getUserInfo() {
    global $db;
    if(isset($_SESSION['user_email'])) {
        $email = $_SESSION['user_email'];
        $query = mysqli_query($db, "SELECT * FROM users WHERE email = '$email'") or die ("Erreur lors de la selection de l'utilisateur");
        return mysqli_fetch_assoc($query);
    }
    return null;
}
?>