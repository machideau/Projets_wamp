<?php
require_once 'config/database.php';
require_once 'controllers/InscriptionController.php';
require_once 'controllers/connexionController.php';

$controller = new connexionController($db);
$controller->handleRequest();


// if (isset($_POST['action']) && $_POST['action'] === 'inscription') {
//     $controller = new InscriptionController($db);
//     $controller->handleRequest();
// } else if (isset($_POST['action']) && $_POST['action'] === 'login') {
//     $controller = new connexionController($db);
//     $controller->handleRequest();
// } else {
//     $controller = new connexionController($db);
//     $controller->handleRequest();
// }

?>