-- Créer la base de données
CREATE DATABASE IF NOT EXISTS gestion_scolaire;
USE gestion_scolaire;

-- Table des rôles
CREATE TABLE IF NOT EXISTS roles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(50) NOT NULL UNIQUE
);

-- Table des utilisateurs (enseignants)
CREATE TABLE IF NOT EXISTS utilisateurs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    mot_de_passe VARCHAR(255) NOT NULL,
    telephone VARCHAR(20),
    role_id INT NOT NULL,
    date_inscription TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    statut VARCHAR(20) DEFAULT 'pending' CHECK (statut IN ('pending', 'approved', 'rejected')),
    FOREIGN KEY (role_id) REFERENCES roles(id)
);

-- Table des classes
CREATE TABLE IF NOT EXISTS classes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom_classe VARCHAR(50) NOT NULL UNIQUE
);

-- Table des matières
CREATE TABLE IF NOT EXISTS matieres (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom_matiere VARCHAR(100) NOT NULL UNIQUE,
    classe_id INT,
    FOREIGN KEY (classe_id) REFERENCES classes(id) ON DELETE SET NULL
);

-- Table pour associer les enseignants aux classes
CREATE TABLE IF NOT EXISTS enseignant_classes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    utilisateur_id INT NOT NULL,
    classe_id INT NOT NULL,
    FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id) ON DELETE CASCADE,
    FOREIGN KEY (classe_id) REFERENCES classes(id) ON DELETE CASCADE
);

-- Table pour associer les enseignants aux matières
CREATE TABLE IF NOT EXISTS enseignant_matieres (
    id INT AUTO_INCREMENT PRIMARY KEY,
    utilisateur_id INT NOT NULL,
    matiere_id INT NOT NULL,
    FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id) ON DELETE CASCADE,
    FOREIGN KEY (matiere_id) REFERENCES matieres(id) ON DELETE CASCADE
);

-- Table pour associer les enseignants aux matières et classes
CREATE TABLE IF NOT EXISTS enseignant_matieres_classes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    utilisateur_id INT NOT NULL,
    matiere_id INT NOT NULL,
    classe_id INT NOT NULL,
    FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id) ON DELETE CASCADE,
    FOREIGN KEY (matiere_id) REFERENCES matieres(id) ON DELETE CASCADE,
    FOREIGN KEY (classe_id) REFERENCES classes(id) ON DELETE CASCADE,
    UNIQUE KEY unique_enseignant_matiere_classe (utilisateur_id, matiere_id, classe_id)
);

-- Table des élèves
CREATE TABLE IF NOT EXISTS eleves (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    date_naissance DATE NOT NULL,
    classe_id INT,
    FOREIGN KEY (classe_id) REFERENCES classes(id) ON DELETE SET NULL
);

-- Table des notes
CREATE TABLE IF NOT EXISTS notes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    eleve_id INT NOT NULL,
    matiere_id INT NOT NULL,
    note_classe DECIMAL(4,2) NOT NULL,
    note_devoir DECIMAL(4,2) NOT NULL,
    note_composition DECIMAL(4,2) NOT NULL,
    trimestre INT NOT NULL,
    date_ajout TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (eleve_id) REFERENCES eleves(id) ON DELETE CASCADE,
    FOREIGN KEY (matiere_id) REFERENCES matieres(id) ON DELETE CASCADE,
    CONSTRAINT check_trimestre CHECK (trimestre IN (1, 2, 3)),
    CONSTRAINT check_notes CHECK (
        note_classe BETWEEN 0 AND 20 AND
        note_devoir BETWEEN 0 AND 20 AND
        note_composition BETWEEN 0 AND 20
    ),
    UNIQUE KEY unique_note (eleve_id, matiere_id, trimestre)
);

-- Insérer les rôles de base
INSERT INTO roles (nom) VALUES 
('admin'),
('enseignant'),
('directeur');

--- Insérer manuellement un compte administrateur
INSERT INTO utilisateurs (
    nom, 
    prenom, 
    email, 
    mot_de_passe, 
    telephone, 
    role_id,
    statut
) VALUES (
    'Admin',
    'System',
    'admin@admin.com',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', -- mot de passe: 'admin123'
    '0123456789',
    (SELECT id FROM roles WHERE nom = 'admin'),
    'approved'
);

-- Insérer manuellement un compte directeur
INSERT INTO utilisateurs (
    nom, 
    prenom, 
    email, 
    mot_de_passe, 
    telephone, 
    role_id,
    statut
) VALUES (
    'Directeur',
    'Principal',
    'directeur@admin.com',
    '$2y$10$mwQpUwX0YQX.svzS/pN0qeqEAHxDxwFrF9w9HxwXX3zqhRyPZUYpC', -- mot de passe: 'directeur123'
    '0987654321',
    (SELECT id FROM roles WHERE nom = 'directeur'),
    'approved'
);

SELECT 
    n.eleve_id,
    n.matiere_id,
    n.trimestre,
    CASE 
        WHEN e.id IS NULL THEN 'ID élève invalide'
        WHEN m.id IS NULL THEN 'ID matière invalide'
    END as probleme
FROM notes n
LEFT JOIN eleves e ON n.eleve_id = e.id
LEFT JOIN matieres m ON n.matiere_id = m.id
WHERE e.id IS NULL OR m.id IS NULL;
