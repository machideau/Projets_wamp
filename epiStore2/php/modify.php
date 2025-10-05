<?php
session_start();
include '../php/db.php';

if (isset($_POST['valider'])) {
  $nom_update = mysqli_real_escape_string($db, $_POST['nom_update']);
  $select = mysqli_query($db, "SELECT * FROM `produits` WHERE nom = '$nom_update'") or die('query failed');

  if (mysqli_num_rows($select) > 0) {
    $row = mysqli_fetch_assoc($select);
    $_SESSION['produit_update'] = $row['id_produits'];
    $produit_update = $_SESSION['produit_update'];
    // echo $produit_update;
    header('location: cmodifier.php');
  }else {
    $message[] = '<br><div style="color:red">Produits non trouvé</div>';
  }
}