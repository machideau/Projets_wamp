<?php
include 'db.php';
session_start();
$user_id = $_SESSION['user_id'];

if (isset($_POST['submit'])) {
    // Get the current password hash from the database
    $result = mysqli_query($db, "SELECT password FROM `user_form` WHERE id = '$user_id'");
    $user = mysqli_fetch_assoc($result);
    $current_password_hash = $user['password'];

    // Update name and email using prepared statements
    $name_update = mysqli_real_escape_string($db, $_POST['name_update']);
    $email_update = mysqli_real_escape_string($db, $_POST['email_update']);
    $stmt = $db->prepare("UPDATE `user_form` SET name = ?, email = ? WHERE id = ?");
    $stmt->bind_param("ssi", $name_update, $email_update, $user_id);
    $stmt->execute();
    $stmt->close();

    // Update password if fields are not empty
    $old_password = $_POST['old_password'];
    $password_update = $_POST['password_update'];
    $new_password = $_POST['new_password'];
    $cnew_password = $_POST['cnew_password'];

    if (!empty($password_update) || !empty($new_password) || !empty($cnew_password)) {
        if (!password_verify($password_update, $current_password_hash)) {
            $message[] = 'Old password does not match!';
        } elseif ($new_password != $cnew_password) {
            $message[] = 'New passwords do not match!';
        } else {
            $new_password_hash = password_hash($new_password, PASSWORD_BCRYPT);
            $stmt = $db->prepare("UPDATE `user_form` SET password = ? WHERE id = ?");
            $stmt->bind_param("si", $new_password_hash, $user_id);
            $stmt->execute();
            $stmt->close();
            $message[] = 'Password updated successfully!';
        }
    }

    // Update profile image if a new one is uploaded
    $image_update = $_FILES['image_update']['name'];
    $image_size_update = $_FILES['image_update']['size'];
    $image_tmp_name_update = $_FILES['image_update']['tmp_name'];
    $image_folder_update = 'uploaded_img/' . $image_update;

    if (!empty($image_update)) {
        if ($image_size_update > 2000000) {
            $message[] = 'Image is too large!';
        } else {
            $stmt = $db->prepare("UPDATE `user_form` SET image = ? WHERE id = ?");
            $stmt->bind_param("si", $image_update, $user_id);
            $stmt->execute();
            $stmt->close();
            move_uploaded_file($image_tmp_name_update, $image_folder_update);
            $message[] = 'Image updated successfully!';
        }
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
        <?php
            $select = mysqli_query($db, "SELECT * FROM `user_form` WHERE id = '$user_id'") or die('Query failed');
            if (mysqli_num_rows($select) > 0) {
                $fetch = mysqli_fetch_assoc($select);
            }
        ?>

        <form action="" method="post" enctype="multipart/form-data">
            <?php
                if ($fetch['image'] == '') {
                    echo '<img src="images/user.png" alt="avatar">';
                } else {
                    echo '<img src="uploaded_img/'.$fetch['image'].'" alt="profile">';
                }
            ?>
            <div class="flex">
                <div class="inputBox">
                    <span>Username: </span>
                    <input type="text" name="name_update" value="<?php echo htmlspecialchars($fetch['name']); ?>" class="box">
                    <span>Email: </span>
                    <input type="email" name="email_update" value="<?php echo htmlspecialchars($fetch['email']); ?>" class="box">
                    <span>Update your profile picture: </span>
                    <input type="file" name="image_update" accept="image/jpg, image/png, image/jpeg" class="box">
                </div>
                <div class="inputBox">
                    <input type="hidden" name="old_password" value="<?php echo htmlspecialchars($fetch['password']); ?>">
                    <span>Old password: </span>
                    <input type="password" name="password_update" placeholder="Enter your previous password" class="box">
                    <span>New password: </span>
                    <input type="password" name="new_password" placeholder="Enter your new password" class="box">
                    <span>Confirm new password: </span>
                    <input type="password" name="cnew_password" placeholder="Confirm your new password" class="box">
                </div>
                <input type="submit" name="submit" value="Update now" class="btn">
                <a href="home.php" class="delete-btn">Go back</a>

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
  