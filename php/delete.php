<?php
include "php/session.php";
$user = getUserInfo();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="uploads/icon/logo.png" type="image/x-icon">
    <title>Delete</title>
    <link rel="stylesheet" href="lscss.css">
</head>
<body>
    <div class="form-container">
        <form method="post" action="php/delete.php" enctype="multipart/form-data">
            <h3></h3>
            <img src="uploads/profiles/<?php echo $user['profile_image']; ?>" alt="profile" class="show-image">
            <input type="text" class="box" name="nom" value="<?php echo $user['name']; ?>" readonly>
            <br><br>
            <input type="email" class="box" name="email" value="<?php echo $user['email']; ?>" readonly>
            <input type="submit" name="delete" value="Supprimer" class="delete-btn">
        </form>
    </div>
</body>
</html>
