<?php
$db = mysqli_connect("localhost", "root", "", "commerce");

if (!$db) {
    die("Erreur de connexion: " . mysqli_connect_error());
}
?>
