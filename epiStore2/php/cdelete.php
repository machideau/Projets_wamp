<?php
    session_start();
    $produit_sup = $_SESSION['produit_sup'];

    if (isset($_POST['valider'])) {
        $delete = mysqli_query($db, "DELETE FROM produits WHERE `produits`.`id_produits` = '$produit_sup'") or die('query failed');
        if (isset($delete)) {
            $message[] = 'produits supprime';
            unset($produit_sup);
            session_destroy();
            header('location: supprimer.php');
        }
    }
?>