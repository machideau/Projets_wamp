<?php
  if (isset($_POST['submit-message'])) {
    $nom = mysqli_real_escape_string($db, $_POST['nom']);
    $email = mysqli_real_escape_string($db, ($_POST['email']));
    $num_tel = mysqli_real_escape_string($db, ($_POST['num_tel']));
    $message = mysqli_real_escape_string($db, $_POST['message']);

    // echo $nom,$email,$num_tel,$message;
  
    // verify if the input email is alrady in the database
    $select = mysqli_query($db, "SELECT * FROM `message` WHERE email = '$email' AND nom_user = '$nom' AND num_tel = '$num_tel' AND message = '$message'") or die('query failed in message select');
    // $select2 = mysqli_query($db, "SELECT * FROM `message` WHERE id_message = 1") or die('query failed');
    // $fetch = mysqli_fetch_assoc($select2);
    if (mysqli_num_rows($select) > 0) {
      header('location: admin/among.php');
    }else {
      $insert = mysqli_query($db, "INSERT INTO `message`(`nom_user`, `email`, `num_tel`, `message`) VALUES ('$nom','$email','$num_tel','$message')") or die('query failed in message insert');
      if ($insert) {
        $alert[] = '<div style="color:green">message send</div>';
      }
    }
  }
?>