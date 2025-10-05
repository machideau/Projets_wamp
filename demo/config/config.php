<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Détection automatique du chemin de base
$base_path = dirname(__DIR__);
$base_url = '';

// Détection si en production ou en local
if ($_SERVER['SERVER_NAME'] === 'localhost' || $_SERVER['SERVER_NAME'] === '127.0.0.1') {
    $base_url = '/demo'; // Pour le développement local
} else {
    $base_url = ''; // Pour la production, ajustez selon votre configuration
}

define('ROOT_PATH', $base_path);
define('MODELS_PATH', ROOT_PATH . '/models');
define('CONTROLLERS_PATH', ROOT_PATH . '/controllers');
define('VIEWS_PATH', ROOT_PATH . '/views');
define('CONFIG_PATH', ROOT_PATH . '/config');
define('VENDOR_PATH', ROOT_PATH . '/vendor');
define('BASE_URL', $base_url);

require_once ROOT_PATH . '/includes/utils.php';
Logger::init();
?>
