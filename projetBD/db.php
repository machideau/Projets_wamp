<?php
// $host = 'localhost';
// $dbname = 'ProjetDB';
// $user = 'root';
// $pass = '';

// try {
//     $conn = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
//     $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
// } catch (PDOException $e) {
//     echo 'Erreur : ' . $e->getMessage();
// }
// Connexion à la base de données
$conn = new mysqli("localhost", "root", "", "projetDB");

if ($conn->connect_error) {
    die("Erreur de connexion: " . $conn->connect_error);
}
?>
