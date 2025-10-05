<?php
// connexion a la base de donnees 
$con = mysqli_connect("localhost","root","","panier");
// verifier la connexion
if(!$con) die('Erreur : '.mysqli_connect_error());
?>