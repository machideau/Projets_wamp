<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../models/User.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $email = isset($_POST['email']) ? filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) : '';
        
        if (!$email) {
            throw new Exception('Email invalide');
        }

        $user = new User();
        $result = $user->initiatePasswordReset($email);
        
        if ($result) {
            echo json_encode([
                'success' => true,
                'message' => 'Instructions envoyées par email'
            ]);
        } else {
            throw new Exception('Email non trouvé');
        }
        
    } catch (Exception $e) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'error' => $e->getMessage()
        ]);
    }
} 