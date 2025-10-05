<?php
require_once 'C:/wamp64/www\Ecole/demo/config/database.php';
require_once 'C:/wamp64/www/Ecole/demo/models/Utilisateur.php';


// $db = new PDO('mysql:host=localhost;dbname=gestion_scolaire;charset=utf8', 'root', '');
$utilisateur = new Utilisateur($db);

$classe_id = $_GET['classe_id'] ?? null;
if ($classe_id) {
    $matieres = $utilisateur->getMatieresByClasse($classe_id);
    echo json_encode($matieres);
} else {
    echo json_encode([]);
}

?>