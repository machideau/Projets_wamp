<?php
session_destroy();
session_start();

if ($_POST['next2']) {
    $email = $_POST['mail'];
    $pswd = $_POST['pswd'];

    if (!empty($email) && !empty($pswd)) {
        $_SESSION['mail'] = $email;
        $_SESSION['pswd'] = $pswd;

        header("Location: ../page3.html");
    } else {
        header("Location : ../page2.html");
    }
    
}
?>