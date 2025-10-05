<?php
session_destroy();
session_start();

if ($_POST['next1']) {
    $name = $_POST['name'];
    $first_name = $_POST['fname'];

    if (!empty($name) && !empty($first_name)) {
        $_SESSION['name'] = $name;
        $_SESSION['fname'] = $first_name;

        header("Location: ../page2.html");
    } else {
        header("Location : ../index.html");
    }
    
}
?>