<?php
// include database connection's file
include 'db.php';
session_start();

// when validate button is click
if (isset($_POST['submit'])) {
    // do when validate button is click
    // get the user's inputs 
    $email = mysqli_real_escape_string($db, $_POST['email']);
    $password = mysqli_real_escape_string($db, md5($_POST['password']));

    // verify if the input email is alrady in the database
    $select = mysqli_query($db, "SELECT * FROM `user_form` WHERE email = '$email' AND password = '$password'") or die('query failed');

    if (mysqli_num_rows($select) > 0) {
        $row = mysqli_fetch_assoc($select);
        $_SESSION['user_id'] = $row['id'];
        header('location: home.php');
    }else {
        $message[] = 'incorrect email or password !!!';
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="form-container">
        <form method="post">
            <h3>Login</h3>
            <input type="email" class="box" name="email" placeholder="enter email" >
            <input type="password" class="box" name="password" placeholder="password" >
            <input type="submit" name="submit" value="login now" class="btn">
            <p>don't have an account? <a href="register.php">sigin now</a></p> 
            <?php
                if (isset($message)) {
                    foreach($message as $message){
                        echo '<br><br><div class="alert" style="color:red">'.$message.'</div>';
                    }
                }
            ?>
        </form>
    </div>
</body>
</html>