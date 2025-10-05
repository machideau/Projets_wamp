<?php
// include database connection's file
include 'db.php';

// when validate button is click
if (isset($_POST['submit'])) {
    // do when validate button is click
    // get the user's inputs
    $name = mysqli_real_escape_string($db, $_POST['name']);
    $email = mysqli_real_escape_string($db, $_POST['email']);
    $password = mysqli_real_escape_string($db, md5($_POST['password']));
    $cpassword = mysqli_real_escape_string($db, md5($_POST['cpassword']));
    $image = $_FILES['image']['name'];
    $image_size = $_FILES['image']['size'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    // image storing 
    $image_folder = 'uploaded_img/'.$image; 

    // verify if the input email is alrady in the database
    $select = mysqli_query($db, "SELECT * FROM `user_form` WHERE email = '$email' AND password = '$password'") or die('query failed');

    if (mysqli_num_rows($select) > 0) {
        //if email isn't in the database
        $message[] = $email.' is alrady registed !!!';
    }else {
        // if email is in the database
        
        // verify if the two passords matche
        if ($password != $cpassword) {
            // if not
            $message[] = 'confirm password not matched !!!';
        }elseif ($image_size > 2000000){
            // if the image size is more than 2000000
            $message[] = 'image size is too large !!!';
        }else {
            // if the two are good 

            // insert input datas in the database
            $insert = mysqli_query($db, "INSERT INTO `user_form`(name, email, password, image) VALUES ('$name', '$email', '$password', '$image')") or die('query failed');

            // if datas are insert, give the name to the image and store it in a folder
            if ($insert) {
                move_uploaded_file($image_tmp_name, $image_folder);
                $message[] = 'registered successfully !!!';
                header('location: login.php');
            }else {
                $message[] = 'registeration failed !!!';
                header('location: register.php');
            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>register</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="form-container">
        <form method="post">
            <h3>Register</h3>
            <input type="text" class="box" name="name" placeholder="enter username" required>
            <input type="email" class="box" name="email" placeholder="enter email" required>
            <input type="password" class="box" name="password" placeholder="password" required>
            <input type="password" class="box" name="cpassword" placeholder="confirm password" required>
            <input type="file" name="image" class="box" accept="image/jpg, image/png/ image/jpeg">
            <input type="submit" name="submit" value="register now" class="btn">
            <p>already have an account? <a href="login.php">login now</a></p> 
            <?php
                if (isset($message)) {
                    foreach($message as $message){
                        echo '<div class="message" style="color:red">'.$message.'</div>';
                    }
                }
            ?>
        </form>
    </div>
</body>
</html>