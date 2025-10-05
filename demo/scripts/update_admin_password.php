<?php
require_once __DIR__ . '/../config/config.php';
require_once CONFIG_PATH . '/database.php';

$db = Database::getInstance();

$password = '';
$hashed_password = password_hash($password, PASSWORD_BCRYPT);

$query = "UPDATE utilisateurs 
          SET mot_de_passe = ? 
          WHERE email = 'admin@admin.com'";

$stmt = $db->prepare($query);
$result = $stmt->execute([$hashed_password]);

if ($result) {
    echo "Mot de passe admin mis à jour avec succès\n";
    echo "Email: admin@admin.com\n";
    echo "Mot de passe: admin123\n";
    echo "Hash généré: " . $hashed_password . "\n";
} else {
    echo "Erreur lors de la mise à jour du mot de passe\n";
}
?>