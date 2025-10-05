<?php
session_start();
include '../php/db.php';

if (isset($_POST['valider'])) {
  $nom = mysqli_real_escape_string($db, $_POST['nom']);
  $select = mysqli_query($db, "SELECT * FROM `produits` WHERE nom = '$nom'") or die('query failed');

  if (mysqli_num_rows($select) > 0) {
    $row = mysqli_fetch_assoc($select);
    $_SESSION['produit_sup'] = $row['id_produits'];
    $produit_sup = $_SESSION['produit_sup'];
    // echo $produit_sup;
    header('location: csupprimer.php');
  }else {
    $message[] = 'Produits non trouvé';
  }
}