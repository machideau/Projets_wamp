<?php
// includes/db.php

$host = 'localhost';
$dbname = 'portfolio';
$username = 'root'; // Utilisateur MySQL par défaut
$password = ''; // Mot de passe MySQL (vide par défaut sur XAMPP/WAMP)

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
?>