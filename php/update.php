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
    <title>Update</title>
    <link rel="stylesheet" href="lscss.css">
</head>
<body>
    <div class="form-container">
        <form method="post" action="php/update.php" enctype="multipart/form-data">
            <h3></h3>
            <img src="uploads/profiles/<?php echo $user['profile_image']; ?>" alt="pprofile" class="show-image">
            <input type="text" class="box" name="nom" placeholder="Nom" value="<?php echo $user['name']; ?>">
            <input type="email" class="box" name="email" placeholder="Email" value="<?php echo $user['email']; ?>">
            <div class="password-input">
                <input type="password" class="box" name="old_password" placeholder="Ancien Mot de passe" value="<?php echo $user['password']; ?>">
                <i class="toggle-password">voir</i>
            </div>
            <div class="password-input">
                <input type="password" class="box" name="new_password" placeholder="Nouveau Mot de passe" value="<?php echo $user['password']; ?>">
                <i class="toggle-password">voir</i>
            </div>  
            <div class="image-upload">
                <input type="file" name="profile_image" accept="image/*" class="box">
            </div>
            <input type="submit" name="modify" value="Modifier le compte" class="update-btn">
        </form>
    </div>
    <script src="script.js"></script>
</body>
</html>
