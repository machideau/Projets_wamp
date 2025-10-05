<?php
    if (isset($_POST['submit-email'])) {
        $email = mysqli_real_escape_string($db, md5($_POST['email']));
    
        // verify if the input email is alrady in the database
        $select = mysqli_query($db, "SELECT * FROM `message` WHERE email = '$email'") or die('query failed in get email');
        if (mysqli_num_rows($select) > 0) {
        header('location: admin/among.php');
        }else {
        $select = mysqli_query($db, "INSERT INTO `user_email`(`email`) VALUES ('$email')") or die('query failed in get email');
        $alert = '<div style="color:green">message send</div>';
        }
    }
?>