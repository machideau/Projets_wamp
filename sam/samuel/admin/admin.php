<?php
include 'config/db.php';

if (isset($_POST['submit'])) {

    // Update name and email using prepared statements
    $name = mysqli_real_escape_string($db, $_POST['name']);
    $description = mysqli_real_escape_string($db, $_POST['description']);
    $link = mysqli_real_escape_string($db, $_POST['link']);

    $image = $_FILES['image']['name'];
    $image_size = $_FILES['image']['size'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    // image storing 
    $image_folder = '../assets/imgs/projects/'.$image;

    $insert = mysqli_query($db, "INSERT INTO `projects`(name, Description, link, image) VALUES ('$name', '$description', '$link', '$image')") or die('query failed');

    if ($insert) {
        move_uploaded_file($image_tmp_name, $image_folder);
        $message[] = 'registered successfully !!!';
        // header('location: login.php');
    } else {
        $message[] = 'registeration failed !!!';
    }
    
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Profile</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="update-profile">
        <form action="" method="post" enctype="multipart/form-data">

            <div class="flex">
                <div class="inputBox">
                    <span>Nom du projet </span>
                    <input type="text" name="name" class="box">

                    <span>Description </span>
                    <input type="text" name="description" class="box">

                    <span>Lien pour acceder au projet </span>
                    <input type="url" name="link" class="box">

                    <span>Image du projet </span>
                    <input type="file" name="image" accept="image/jpg, image/png, image/jpeg" class="box">

                </div>
                <input type="submit" name="submit" value="Ajouter" class="btn">
                <a href="../index.html" class="delete-btn">Annuler</a>

                <?php
                    if (isset($message)) {
                        foreach($message as $msg) {
                            echo '<br><br><div class="alert" style="color:red">'.$msg.'</div>';
                        }
                    }
                ?>
            </div>

        </form>
    </div>
</body>
</html>
  