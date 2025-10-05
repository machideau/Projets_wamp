<?php
session_start();
include "db.php";
if (isset($_POST['submit'])) {
    $email = $_POST['email'];
    $pswd = $_POST['password'];
    // $a = mysqli_real_escape_string($db, $_POST['a']);

    if (!empty($email) && !empty($pswd)) {
        $select = mysqli_query($db, "SELECT * FROM `users` WHERE email = '$email' AND password = '$pswd'") or die('query failed in');

        if (mysqli_num_rows($select) > 0) {
            $_SESSION['user_email'] = $email;
            header('location: ../index.php');
        } else {
            echo 'incorrect email or password !!!';
            header('location: ../login.html');
        }
    } else {
        header('location: ../login.html');
    }
}
?>
