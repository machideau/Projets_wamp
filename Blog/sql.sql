-- Création de la base de données
CREATE DATABASE IF NOT EXISTS blog_php DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE blog_php;

-- Table des utilisateurs
CREATE TABLE utilisateurs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom_utilisateur VARCHAR(50) NOT NULL UNIQUE,
    mot_de_passe VARCHAR(255) NOT NULL,
    email VARCHAR(100),
    role ENUM('admin', 'user') DEFAULT 'user',
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table des articles
CREATE TABLE articles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titre VARCHAR(255) NOT NULL,
    contenu TEXT NOT NULL,
    image VARCHAR(255),
    date_publication DATETIME DEFAULT CURRENT_TIMESTAMP,
    auteur_id INT,
    FOREIGN KEY (auteur_id) REFERENCES utilisateurs(id) ON DELETE SET NULL
);

-- Table des commentaires
CREATE TABLE commentaires (
    id INT AUTO_INCREMENT PRIMARY KEY,
    contenu TEXT NOT NULL,
    pseudo VARCHAR(100),
    date_commentaire DATETIME DEFAULT CURRENT_TIMESTAMP,
    article_id INT NOT NULL,
    FOREIGN KEY (article_id) REFERENCES articles(id) ON DELETE CASCADE
);

-- Table des configurations du blog
CREATE TABLE configurations (
    id INT PRIMARY KEY,
    titre_blog VARCHAR(255),
    description_blog TEXT
);
