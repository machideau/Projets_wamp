<?php
$password = 'votre_mot_de_passe';
echo password_hash($password, PASSWORD_BCRYPT);
?>