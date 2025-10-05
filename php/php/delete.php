<?php
    session_start();
    include "db.php";
    if(isset($_SESSION['user_email'])) {
        $email = $_SESSION['user_email'];
    } else {
        echo "no session";
    }
    if (isset($_POST['delete'])) {
        $delete = mysqli_query($db, "DELETE FROM `users` WHERE `users`.`email` = '$email'") or die('query failed');
        if ($delete) {
            unset($email);
            session_destroy();
            header('location: ../index.php');
        } else {
            echo "not delete";
        }
    } else {
        echo "not set";
    }
?>