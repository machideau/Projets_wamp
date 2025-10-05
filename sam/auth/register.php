<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../models/User.php';

// Activer l'affichage des erreurs pour le débogage
ini_set('display_errors', 1);
error_reporting(E_ALL);

try {
    // Vérification des champs requis
    if (empty($_POST['email']) || empty($_POST['password']) || empty($_POST['confirm_password'])) {
        throw new Exception('Veuillez remplir tous les champs obligatoires');
    }

    // Validation de l'email
    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        throw new Exception('Format d\'email invalide');
    }

    // Validation du mot de passe
    if (strlen($_POST['password']) < 8) {
        throw new Exception('Le mot de passe doit contenir au moins 8 caractères');
    }

    if ($_POST['password'] !== $_POST['confirm_password']) {
        throw new Exception('Les mots de passe ne correspondent pas');
    }

    // Traitement de la photo
    $profilePhotoPath = 'images/default-avatar.webp';
    
    if (isset($_FILES['profile_photo']) && $_FILES['profile_photo']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['profile_photo'];
        $allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
        
        if (!in_array($file['type'], $allowedTypes)) {
            throw new Exception('Format de fichier non supporté. Utilisez JPG, PNG ou WEBP');
        }
        
        if ($file['size'] > 5 * 1024 * 1024) {
            throw new Exception('La photo ne doit pas dépasser 5MB');
        }
        
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $newFileName = uniqid() . '.' . $extension;
        $uploadDir = '../images/users/';
        
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        
        if (move_uploaded_file($file['tmp_name'], $uploadDir . $newFileName)) {
            $profilePhotoPath = 'images/users/' . $newFileName;
        } else {
            throw new Exception('Erreur lors de l\'upload de la photo');
        }
    }

    require_once '../includes/config.php';
    
    // Vérifier si l'email existe déjà
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $_POST['email']);
    $stmt->execute();
    if ($stmt->get_result()->num_rows > 0) {
        throw new Exception('Cet email est déjà utilisé');
    }

    // Insertion de l'utilisateur
    $hashedPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO users (email, password, profile_photo) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $_POST['email'], $hashedPassword, $profilePhotoPath);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        throw new Exception('Erreur lors de l\'inscription');
    }

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
