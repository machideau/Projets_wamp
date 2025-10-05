<?php
class Database {
    private static $instance = null;
    private $pdo;

    private function __construct() {
        try {
            // Configuration pour la production
            if ($_SERVER['SERVER_NAME'] !== 'localhost' && $_SERVER['SERVER_NAME'] !== '127.0.0.1') {
                $host = 'votre_host_en_ligne';
                $dbname = 'votre_db_en_ligne';
                $user = 'votre_user_en_ligne';
                $pass = 'votre_password_en_ligne';
            } else {
                // Configuration locale
                $host = 'localhost';
                $dbname = 'gestion_scolaire';
                $user = 'root';
                $pass = '';
            }

            $this->pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Erreur de connexion : " . $e->getMessage());
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance->pdo;
    }
}
?>
