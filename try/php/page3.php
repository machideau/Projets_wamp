<?php
session_destroy();
session_start();

if ($_POST['valider']) {
    $phone = $_POST['phone'];
    $skname = $_POST['skname'];

    if (!empty($phone) && !empty($skname)) {
        $_SESSION['phone'] = $phone;
        $_SESSION['school'] = $skname;

        header("Location: ../print.php");
    } else {
        header("Location : ../page3.html");
    }
    
}
?>