-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Oct 05, 2025 at 10:20 AM
-- Server version: 5.7.23
-- PHP Version: 7.2.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `asigin`
--
CREATE DATABASE IF NOT EXISTS `asigin` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `asigin`;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`) VALUES
(1, 'machideau', 'machideau@gmail.com', '123');
--
-- Database: `blog_php`
--
CREATE DATABASE IF NOT EXISTS `blog_php` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `blog_php`;

-- --------------------------------------------------------

--
-- Table structure for table `articles`
--

DROP TABLE IF EXISTS `articles`;
CREATE TABLE IF NOT EXISTS `articles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titre` varchar(255) NOT NULL,
  `contenu` text NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `date_publication` datetime DEFAULT CURRENT_TIMESTAMP,
  `auteur_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `auteur_id` (`auteur_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `articles`
--

INSERT INTO `articles` (`id`, `titre`, `contenu`, `image`, `date_publication`, `auteur_id`) VALUES
(1, 'Demo', 'ceci est un demo', NULL, '2025-06-16 21:25:13', 1),
(2, 'Demo2', 'c\'est du lourd', NULL, '2025-06-16 21:47:41', 1);

-- --------------------------------------------------------

--
-- Table structure for table `commentaires`
--

DROP TABLE IF EXISTS `commentaires`;
CREATE TABLE IF NOT EXISTS `commentaires` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `article_id` int(11) NOT NULL,
  `auteur` varchar(255) NOT NULL,
  `contenu` text NOT NULL,
  `date_commentaire` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `article_id` (`article_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `commentaires`
--

INSERT INTO `commentaires` (`id`, `article_id`, `auteur`, `contenu`, `date_commentaire`) VALUES
(1, 1, 'user', 'sympa', '2025-06-16 21:45:27'),
(2, 1, 'moi', 'encore un succes', '2025-06-16 21:46:41');

-- --------------------------------------------------------

--
-- Table structure for table `configurations`
--

DROP TABLE IF EXISTS `configurations`;
CREATE TABLE IF NOT EXISTS `configurations` (
  `id` int(11) NOT NULL,
  `titre_blog` varchar(255) DEFAULT NULL,
  `description_blog` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `utilisateurs`
--

DROP TABLE IF EXISTS `utilisateurs`;
CREATE TABLE IF NOT EXISTS `utilisateurs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom_utilisateur` varchar(50) NOT NULL,
  `mot_de_passe` varchar(255) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `role` enum('admin','user') DEFAULT 'user',
  `date_creation` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nom_utilisateur` (`nom_utilisateur`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `utilisateurs`
--

INSERT INTO `utilisateurs` (`id`, `nom_utilisateur`, `mot_de_passe`, `email`, `role`, `date_creation`) VALUES
(1, 'admin', '$2y$10$/5QXOvs76Of4W48ExVRDFeRQHKfHKBodq4dw0fzRsp1zMRmUzvJHi', 'admin@admin.com', 'admin', '2025-06-16 20:51:57'),
(2, 'user', '$2y$10$frMSvje39/BFMI3lgqXFV.zUXMYMgsNSxSS5octVVcWm0/BC0uCnK', 'user@users.com', 'user', '2025-06-16 21:36:51');
--
-- Database: `bulletins_system`
--
CREATE DATABASE IF NOT EXISTS `bulletins_system` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `bulletins_system`;

-- --------------------------------------------------------

--
-- Table structure for table `annees_scolaires`
--

DROP TABLE IF EXISTS `annees_scolaires`;
CREATE TABLE IF NOT EXISTS `annees_scolaires` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `libelle` varchar(50) NOT NULL,
  `date_debut` date NOT NULL,
  `date_fin` date NOT NULL,
  `active` tinyint(1) DEFAULT '1',
  `etablissement_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `etablissement_id` (`etablissement_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `annees_scolaires`
--

INSERT INTO `annees_scolaires` (`id`, `libelle`, `date_debut`, `date_fin`, `active`, `etablissement_id`, `created_at`) VALUES
(1, '2025-2026', '2025-09-01', '2026-06-30', 1, 1, '2025-09-18 06:48:15');

-- --------------------------------------------------------

--
-- Table structure for table `bulletins`
--

DROP TABLE IF EXISTS `bulletins`;
CREATE TABLE IF NOT EXISTS `bulletins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `eleve_id` int(11) NOT NULL,
  `periode_id` int(11) NOT NULL,
  `moyenne_generale` decimal(4,2) DEFAULT NULL,
  `rang_classe` int(11) DEFAULT NULL,
  `effectif_classe` int(11) DEFAULT NULL,
  `appreciation_generale` text,
  `decision_conseil` text,
  `fichier_pdf` varchar(500) DEFAULT NULL,
  `statut` enum('brouillon','valide','envoye') DEFAULT 'brouillon',
  `genere_par` int(11) NOT NULL,
  `genere_le` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `valide_par` int(11) DEFAULT NULL,
  `valide_le` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_eleve_periode` (`eleve_id`,`periode_id`),
  KEY `genere_par` (`genere_par`),
  KEY `valide_par` (`valide_par`),
  KEY `idx_bulletins_periode` (`periode_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `classes`
--

DROP TABLE IF EXISTS `classes`;
CREATE TABLE IF NOT EXISTS `classes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) NOT NULL,
  `niveau` varchar(50) DEFAULT NULL,
  `section` varchar(50) DEFAULT NULL,
  `effectif_max` int(11) DEFAULT '35',
  `etablissement_id` int(11) NOT NULL,
  `annee_scolaire_id` int(11) NOT NULL,
  `professeur_principal_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `etablissement_id` (`etablissement_id`),
  KEY `annee_scolaire_id` (`annee_scolaire_id`),
  KEY `professeur_principal_id` (`professeur_principal_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `classes`
--

INSERT INTO `classes` (`id`, `nom`, `niveau`, `section`, `effectif_max`, `etablissement_id`, `annee_scolaire_id`, `professeur_principal_id`, `created_at`, `updated_at`) VALUES
(1, '6ème A', '6ème', 'A', 35, 1, 1, 2, '2025-09-18 06:48:15', '2025-09-18 06:48:15'),
(3, '5ème B ', '5ème', 'B', 50, 1, 1, NULL, '2025-09-22 04:38:35', '2025-09-22 04:38:35');

-- --------------------------------------------------------

--
-- Table structure for table `classe_matiere_professeur`
--

DROP TABLE IF EXISTS `classe_matiere_professeur`;
CREATE TABLE IF NOT EXISTS `classe_matiere_professeur` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `classe_id` int(11) NOT NULL,
  `matiere_id` int(11) NOT NULL,
  `professeur_id` int(11) NOT NULL,
  `coefficient_classe` decimal(3,1) DEFAULT '1.0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_classe_matiere_prof` (`classe_id`,`matiere_id`,`professeur_id`),
  KEY `matiere_id` (`matiere_id`),
  KEY `professeur_id` (`professeur_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `classe_matiere_professeur`
--

INSERT INTO `classe_matiere_professeur` (`id`, `classe_id`, `matiere_id`, `professeur_id`, `coefficient_classe`, `created_at`) VALUES
(1, 1, 1, 2, '1.0', '2025-09-18 07:02:38'),
(2, 1, 2, 2, '1.0', '2025-09-18 07:07:17'),
(3, 1, 3, 2, '1.0', '2025-09-18 07:19:30'),
(4, 1, 4, 2, '1.0', '2025-09-18 07:22:36');

-- --------------------------------------------------------

--
-- Table structure for table `eleves`
--

DROP TABLE IF EXISTS `eleves`;
CREATE TABLE IF NOT EXISTS `eleves` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `classe_id` int(11) NOT NULL,
  `numero_ordre` int(11) DEFAULT NULL,
  `date_inscription` date NOT NULL,
  `statut` enum('inscrit','transfere','abandonne','diplome') DEFAULT 'inscrit',
  `nom_pere` varchar(255) DEFAULT NULL,
  `nom_mere` varchar(255) DEFAULT NULL,
  `telephone_tuteur` varchar(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`user_id`),
  KEY `idx_eleves_classe` (`classe_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `eleves`
--

INSERT INTO `eleves` (`id`, `user_id`, `classe_id`, `numero_ordre`, `date_inscription`, `statut`, `nom_pere`, `nom_mere`, `telephone_tuteur`, `created_at`, `updated_at`) VALUES
(1, 3, 1, 1, '2025-09-18', 'inscrit', NULL, NULL, NULL, '2025-09-18 06:48:15', '2025-09-18 06:48:15'),
(2, 4, 1, 2, '2025-09-18', 'inscrit', NULL, NULL, NULL, '2025-09-18 06:48:16', '2025-09-18 06:48:16'),
(3, 5, 1, NULL, '2025-09-18', 'inscrit', NULL, NULL, NULL, '2025-09-18 07:49:47', '2025-09-18 07:49:47');

-- --------------------------------------------------------

--
-- Table structure for table `etablissements`
--

DROP TABLE IF EXISTS `etablissements`;
CREATE TABLE IF NOT EXISTS `etablissements` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) NOT NULL,
  `adresse` text,
  `telephone` varchar(20) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `directeur` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `etablissements`
--

INSERT INTO `etablissements` (`id`, `nom`, `adresse`, `telephone`, `email`, `logo`, `directeur`, `created_at`, `updated_at`) VALUES
(1, 'Etablissement Démo', 'Abidjan', '0102030405', 'contact@demo.com', NULL, 'Directeur Démo', '2025-09-18 06:48:15', '2025-09-18 06:48:15');

-- --------------------------------------------------------

--
-- Table structure for table `evaluations`
--

DROP TABLE IF EXISTS `evaluations`;
CREATE TABLE IF NOT EXISTS `evaluations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titre` varchar(255) NOT NULL,
  `date_evaluation` date NOT NULL,
  `note_sur` decimal(4,2) DEFAULT '20.00',
  `classe_matiere_professeur_id` int(11) NOT NULL,
  `periode_id` int(11) NOT NULL,
  `type_evaluation_id` int(11) NOT NULL,
  `description` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `classe_matiere_professeur_id` (`classe_matiere_professeur_id`),
  KEY `type_evaluation_id` (`type_evaluation_id`),
  KEY `idx_evaluations_periode` (`periode_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `imports_fichiers`
--

DROP TABLE IF EXISTS `imports_fichiers`;
CREATE TABLE IF NOT EXISTS `imports_fichiers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom_fichier` varchar(255) NOT NULL,
  `type_fichier` enum('excel','csv','pdf','image') NOT NULL,
  `chemin_original` varchar(500) NOT NULL,
  `chemin_csv` varchar(500) DEFAULT NULL,
  `statut` enum('en_cours','converti','importe','erreur') DEFAULT 'en_cours',
  `nombre_lignes` int(11) DEFAULT '0',
  `nombre_erreurs` int(11) DEFAULT '0',
  `details_erreurs` text,
  `importe_par` int(11) NOT NULL,
  `evaluation_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `importe_par` (`importe_par`),
  KEY `evaluation_id` (`evaluation_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `matieres`
--

DROP TABLE IF EXISTS `matieres`;
CREATE TABLE IF NOT EXISTS `matieres` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) NOT NULL,
  `code` varchar(10) DEFAULT NULL,
  `coefficient` decimal(3,1) DEFAULT '1.0',
  `couleur` varchar(7) DEFAULT '#000000',
  `description` text,
  `etablissement_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`),
  KEY `etablissement_id` (`etablissement_id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `matieres`
--

INSERT INTO `matieres` (`id`, `nom`, `code`, `coefficient`, `couleur`, `description`, `etablissement_id`, `created_at`, `updated_at`) VALUES
(1, 'Maths', 'MATH', '1.0', '#000000', '', 1, '2025-09-18 07:02:38', '2025-09-18 07:02:38'),
(2, 'Anglais', 'ANGL', '1.0', '#000000', '', 1, '2025-09-18 07:07:17', '2025-09-18 07:07:17'),
(3, 'Francais', 'FRAN', '3.0', '#000000', '', 1, '2025-09-18 07:19:30', '2025-09-18 07:19:30'),
(4, 'Histoire', 'HIST', '2.0', '#000000', '', 1, '2025-09-18 07:22:36', '2025-09-18 07:22:36'),
(5, 'SVT', 'SVT', '1.0', '#000000', '', 1, '2025-09-22 04:39:38', '2025-09-22 04:39:38');

-- --------------------------------------------------------

--
-- Table structure for table `moyennes_matieres`
--

DROP TABLE IF EXISTS `moyennes_matieres`;
CREATE TABLE IF NOT EXISTS `moyennes_matieres` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `eleve_id` int(11) NOT NULL,
  `matiere_id` int(11) NOT NULL,
  `periode_id` int(11) NOT NULL,
  `moyenne` decimal(4,2) DEFAULT NULL,
  `coefficient` decimal(3,1) DEFAULT NULL,
  `rang_matiere` int(11) DEFAULT NULL,
  `effectif_matiere` int(11) DEFAULT NULL,
  `appreciation` text,
  `calculated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_eleve_matiere_periode` (`eleve_id`,`matiere_id`,`periode_id`),
  KEY `matiere_id` (`matiere_id`),
  KEY `periode_id` (`periode_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `notes`
--

DROP TABLE IF EXISTS `notes`;
CREATE TABLE IF NOT EXISTS `notes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `note` decimal(4,2) DEFAULT NULL,
  `statut` enum('present','absent','dispense') DEFAULT 'present',
  `commentaire` text,
  `eleve_id` int(11) NOT NULL,
  `evaluation_id` int(11) NOT NULL,
  `saisie_par` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_eleve_evaluation` (`eleve_id`,`evaluation_id`),
  KEY `evaluation_id` (`evaluation_id`),
  KEY `saisie_par` (`saisie_par`),
  KEY `idx_notes_eleve_periode` (`eleve_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `periodes`
--

DROP TABLE IF EXISTS `periodes`;
CREATE TABLE IF NOT EXISTS `periodes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) NOT NULL,
  `numero_ordre` int(11) NOT NULL,
  `date_debut` date NOT NULL,
  `date_fin` date NOT NULL,
  `date_limite_saisie` date DEFAULT NULL,
  `active` tinyint(1) DEFAULT '1',
  `annee_scolaire_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `annee_scolaire_id` (`annee_scolaire_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `types_evaluations`
--

DROP TABLE IF EXISTS `types_evaluations`;
CREATE TABLE IF NOT EXISTS `types_evaluations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) NOT NULL,
  `coefficient` decimal(3,1) DEFAULT '1.0',
  `couleur` varchar(7) DEFAULT '#000000',
  `etablissement_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `etablissement_id` (`etablissement_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `types_evaluations`
--

INSERT INTO `types_evaluations` (`id`, `nom`, `coefficient`, `couleur`, `etablissement_id`, `created_at`) VALUES
(1, 'Devoir', '1.0', '#3498db', 1, '2025-09-17 22:41:21'),
(2, 'Composition', '2.0', '#e74c3c', 1, '2025-09-17 22:41:21'),
(3, 'Travaux Pratiques', '1.0', '#2ecc71', 1, '2025-09-17 22:41:21'),
(4, 'Interrogation', '0.5', '#f39c12', 1, '2025-09-17 22:41:21');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) NOT NULL,
  `prenoms` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `telephone` varchar(20) DEFAULT NULL,
  `date_naissance` date DEFAULT NULL,
  `lieu_naissance` varchar(255) DEFAULT NULL,
  `sexe` enum('M','F') NOT NULL,
  `adresse` text,
  `photo` varchar(255) DEFAULT NULL,
  `type_user` enum('admin','professeur','eleve','parent') NOT NULL,
  `matricule` varchar(50) DEFAULT NULL,
  `mot_de_passe` varchar(255) NOT NULL,
  `actif` tinyint(1) DEFAULT '1',
  `etablissement_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `matricule` (`matricule`),
  KEY `etablissement_id` (`etablissement_id`),
  KEY `idx_users_type` (`type_user`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `nom`, `prenoms`, `email`, `telephone`, `date_naissance`, `lieu_naissance`, `sexe`, `adresse`, `photo`, `type_user`, `matricule`, `mot_de_passe`, `actif`, `etablissement_id`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'Système', 'admin@etablissement.com', NULL, NULL, NULL, 'M', NULL, NULL, 'admin', 'ADM2025001', '$2y$10$lbgeTAua/19H03emBtEaQeuFarnLtuXEM28odEQTDQjAFaqXyjtBu', 1, 1, '2025-09-18 06:48:15', '2025-09-18 06:48:15'),
(2, 'Dupont', 'Paul', 'prof.maths@etab.com', NULL, NULL, NULL, 'M', NULL, NULL, 'professeur', 'PRF2025001', '$2y$10$BIu4CGFhJ4ntMJP0uQVNcOYc2ou6v4lZ3u90QIicw2EwJ4ve6wSle', 1, 1, '2025-09-18 06:48:15', '2025-09-18 06:48:15'),
(3, 'Kone', 'Awa', 'awa.kone@etab.com', NULL, NULL, NULL, 'F', NULL, NULL, 'eleve', 'EL2025001', '$2y$10$H4QqQO/nIix1DykQy3wg/edTxb/voH6U0TfXYS.0T18EpMo76eRPi', 1, 1, '2025-09-18 06:48:15', '2025-09-18 06:48:15'),
(4, 'Traore', 'Ibrahim', 'ibrahim.traore@etab.com', NULL, NULL, NULL, 'M', NULL, NULL, 'eleve', 'EL2025002', '$2y$10$yT6odfkc330VFHXQ7qVzmO4K2tTLedDj0Kb0y8biRtJUDaIKOcWLS', 1, 1, '2025-09-18 06:48:16', '2025-09-18 06:48:16'),
(5, 'SAM', 'Lefaure', 'machideau@gmail.com', NULL, NULL, NULL, 'M', NULL, NULL, 'eleve', 'EL202510003', '$2y$10$lDruWfFnDn9QUF45sS1s8ezltd/bOUwPxicqSOUuWmwS7dbOCGHaC', 1, 1, '2025-09-18 07:49:47', '2025-09-18 07:49:47');
--
-- Database: `chatapp_db`
--
CREATE DATABASE IF NOT EXISTS `chatapp_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `chatapp_db`;

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

DROP TABLE IF EXISTS `messages`;
CREATE TABLE IF NOT EXISTS `messages` (
  `msg_id` int(11) NOT NULL AUTO_INCREMENT,
  `incoming_msg_id` int(255) NOT NULL,
  `outgoing_msg_id` int(255) NOT NULL,
  `msg` varchar(1000) NOT NULL,
  PRIMARY KEY (`msg_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`msg_id`, `incoming_msg_id`, `outgoing_msg_id`, `msg`) VALUES
(1, 1, 2, 'salut'),
(2, 2, 1, 'ehlo'),
(3, 2, 1, 'hghv'),
(4, 1, 3, 'salut'),
(5, 3, 2, 'hghv'),
(6, 3, 2, 'bcgfdv'),
(7, 2, 3, 'hnbvhv'),
(8, 2, 3, 'jhujhb'),
(9, 3, 2, 'bvcgc'),
(10, 3, 2, 'ggfg');

-- --------------------------------------------------------

--
-- Table structure for table `user_form`
--

DROP TABLE IF EXISTS `user_form`;
CREATE TABLE IF NOT EXISTS `user_form` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `unique_id` int(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `img` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user_form`
--

INSERT INTO `user_form` (`user_id`, `unique_id`, `name`, `email`, `password`, `img`, `status`) VALUES
(1, 1628684524, 'machideau', 'machideau@gmail.com', 'f970e2767d0cfe75876ea857f92e319b', 'logo12_14_205245.png', 'Active Now'),
(2, 1539485164, 'admin', 'admin@admin.com', 'c20ad4d76fe97759aa27a0c99bff6710', 'jarvis.png', 'Active Now'),
(3, 1701552972, 'Admin', 'samuellefaure@gmail.com', '81dc9bdb52d04dc20036dbd8313ed055', 'default-avatar.png', 'Active Now');
--
-- Database: `commerce`
--
CREATE DATABASE IF NOT EXISTS `commerce` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `commerce`;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
CREATE TABLE IF NOT EXISTS `orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `status` enum('pending','paid','shipped','completed','cancelled') DEFAULT 'pending',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

DROP TABLE IF EXISTS `order_items`;
CREATE TABLE IF NOT EXISTS `order_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT '1',
  `price` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  KEY `product_id` (`product_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

DROP TABLE IF EXISTS `payments`;
CREATE TABLE IF NOT EXISTS `payments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `method` enum('credit_card','paypal','mobile_money','cash') NOT NULL,
  `status` enum('pending','completed','failed') DEFAULT 'pending',
  `payment_date` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
CREATE TABLE IF NOT EXISTS `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `category` varchar(100) DEFAULT NULL,
  `description` text,
  `price` decimal(10,2) NOT NULL,
  `stock` int(11) DEFAULT '0',
  `image` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `type` enum('buyer','seller') DEFAULT 'buyer',
  `role` enum('admin','user') DEFAULT 'user',
  `status` enum('active','inactive','deleted') DEFAULT 'active',
  `image` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `phone`, `type`, `role`, `status`, `image`, `created_at`, `updated_at`) VALUES
(1, 'KKVZ', 'machideau@gmail.com', '$2y$10$I4xFZ91dS./36PxjtFqbKOzCP/ERyw/lTjnI5.b5aX4KLAagOaRb.', '+22893112060', 'seller', 'user', 'active', '1746133430.png', '2025-05-01 21:03:50', '2025-05-01 21:03:50'),
(2, 'Admin', 'admin@admin.com', '$2y$10$3NXd2mCmTObueghO0UfR..SrNVSIVaa/RXwy9Uwy6Vnr3n0Zs6sX.', '+22893112060', NULL, 'admin', 'active', '1746541153.png', '2025-05-06 14:19:13', '2025-05-06 14:22:25'),
(3, 'Test', 'test@test.com', '$2y$10$F6yqmxha9UyXtcyXtJF7F.AnlQ0vIQDLqky6f8Hvk4hMQIDJswvXq', '', 'buyer', 'user', 'active', '', '2025-05-17 12:50:00', '2025-05-17 12:50:00');
--
-- Database: `ecole`
--
CREATE DATABASE IF NOT EXISTS `ecole` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `ecole`;

-- --------------------------------------------------------

--
-- Table structure for table `classe`
--

DROP TABLE IF EXISTS `classe`;
CREATE TABLE IF NOT EXISTS `classe` (
  `classe_id` int(11) NOT NULL,
  `nom_classe` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`classe_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `classe`
--

INSERT INTO `classe` (`classe_id`, `nom_classe`) VALUES
(1, 'C A'),
(2, 'C B'),
(3, 'C A'),
(4, 'C B'),
(5, 'C C');

-- --------------------------------------------------------

--
-- Table structure for table `eleve`
--

DROP TABLE IF EXISTS `eleve`;
CREATE TABLE IF NOT EXISTS `eleve` (
  `eleve_id` int(11) NOT NULL,
  `nom_eleve` varchar(255) DEFAULT NULL,
  `classe_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`eleve_id`),
  KEY `classe_id` (`classe_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `eleve`
--

INSERT INTO `eleve` (`eleve_id`, `nom_eleve`, `classe_id`) VALUES
(1, 'aba', 1),
(2, 'baba', 2),
(3, 'lami', 1),
(4, 'dupont', 4),
(5, 'liane', 5);

-- --------------------------------------------------------

--
-- Table structure for table `enseignant`
--

DROP TABLE IF EXISTS `enseignant`;
CREATE TABLE IF NOT EXISTS `enseignant` (
  `enseignant_id` int(11) NOT NULL,
  `nom_enseignant` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`enseignant_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `enseignant`
--

INSERT INTO `enseignant` (`enseignant_id`, `nom_enseignant`) VALUES
(1, 'marc'),
(2, 'sam'),
(3, 'leo'),
(4, 'ter'),
(5, 'def');

-- --------------------------------------------------------

--
-- Table structure for table `intervenantclassematiere`
--

DROP TABLE IF EXISTS `intervenantclassematiere`;
CREATE TABLE IF NOT EXISTS `intervenantclassematiere` (
  `id` int(11) NOT NULL,
  `classe_id` int(11) DEFAULT NULL,
  `matiere_id` int(11) DEFAULT NULL,
  `enseignant_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `classe_id` (`classe_id`),
  KEY `matiere_id` (`matiere_id`),
  KEY `enseignant_id` (`enseignant_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `intervenantclassematiere`
--

INSERT INTO `intervenantclassematiere` (`id`, `classe_id`, `matiere_id`, `enseignant_id`) VALUES
(1, 1, 1, 1),
(2, 2, 2, 2),
(3, 3, 3, 3),
(4, 4, 4, 4),
(5, 5, 5, 5);

-- --------------------------------------------------------

--
-- Table structure for table `matiere`
--

DROP TABLE IF EXISTS `matiere`;
CREATE TABLE IF NOT EXISTS `matiere` (
  `matiere_id` int(11) NOT NULL,
  `nom_matiere` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`matiere_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `matiere`
--

INSERT INTO `matiere` (`matiere_id`, `nom_matiere`) VALUES
(1, 'Math'),
(2, 'EPS'),
(3, 'FR'),
(4, 'ANG'),
(5, 'HG');

-- --------------------------------------------------------

--
-- Table structure for table `note`
--

DROP TABLE IF EXISTS `note`;
CREATE TABLE IF NOT EXISTS `note` (
  `note_id` int(11) NOT NULL,
  `eleve_id` int(11) DEFAULT NULL,
  `matiere_id` int(11) DEFAULT NULL,
  `enseignant_id` int(11) DEFAULT NULL,
  `note_value` float DEFAULT NULL,
  PRIMARY KEY (`note_id`),
  KEY `eleve_id` (`eleve_id`),
  KEY `matiere_id` (`matiere_id`),
  KEY `enseignant_id` (`enseignant_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `note`
--

INSERT INTO `note` (`note_id`, `eleve_id`, `matiere_id`, `enseignant_id`, `note_value`) VALUES
(1, 1, 1, 1, 12.5),
(2, 2, 2, 2, 15),
(3, 3, 3, 3, 20),
(4, 4, 4, 4, 10),
(5, 5, 5, 5, 5);
--
-- Database: `ellegantia_db`
--
CREATE DATABASE IF NOT EXISTS `ellegantia_db` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `ellegantia_db`;

-- --------------------------------------------------------

--
-- Table structure for table `carts`
--

DROP TABLE IF EXISTS `carts`;
CREATE TABLE IF NOT EXISTS `carts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `session_id` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `session_id` (`session_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `cart_items`
--

DROP TABLE IF EXISTS `cart_items`;
CREATE TABLE IF NOT EXISTS `cart_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cart_id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `added_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cart_id` (`cart_id`,`product_id`),
  KEY `product_id` (`product_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
CREATE TABLE IF NOT EXISTS `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `description` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `description`, `image`, `status`, `created_at`, `updated_at`) VALUES
(2, 'demo', 'nm ,./', 'man.jpg', 'active', '2025-07-03 11:31:37', '2025-07-03 11:31:37'),
(3, 'demo2', 'demo demo', 'WhatsApp Image 2025-06-28 at 16.15.43_06524f4f.jpg', 'inactive', '2025-07-03 19:42:58', '2025-07-03 19:42:58');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
CREATE TABLE IF NOT EXISTS `notifications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `type` enum('order_update','promotion','account_alert','system_message') NOT NULL,
  `message` text NOT NULL,
  `link` varchar(255) DEFAULT NULL,
  `is_read` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
CREATE TABLE IF NOT EXISTS `orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `address` varchar(255) NOT NULL,
  `discount_amount` decimal(10,2) DEFAULT '0.00',
  `order_status` enum('pending','processing','shipped','delivered','cancelled') DEFAULT 'pending',
  `payment_status` enum('pending','paid','refunded','failed') DEFAULT 'pending',
  `tracking_number` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
CREATE TABLE IF NOT EXISTS `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text,
  `price` decimal(10,2) NOT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `brand` varchar(100) DEFAULT NULL,
  `status` enum('available','out_of_stock','discontinued') DEFAULT 'available',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `category_id` (`category_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1001 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `image_url`, `category_id`, `brand`, `status`, `created_at`, `updated_at`) VALUES
(1000, 'p1', 'p1 demo', '100.00', NULL, 2, NULL, 'available', '2025-07-03 20:14:18', '2025-07-03 20:14:18');

-- --------------------------------------------------------

--
-- Table structure for table `promotions`
--

DROP TABLE IF EXISTS `promotions`;
CREATE TABLE IF NOT EXISTS `promotions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(50) NOT NULL,
  `discount_type` enum('percentage','fixed_amount') NOT NULL,
  `discount_value` decimal(10,2) NOT NULL,
  `min_order_amount` decimal(10,2) DEFAULT '0.00',
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `usage_limit` int(11) DEFAULT NULL,
  `used_count` int(11) DEFAULT '0',
  `max_uses_per_user` int(11) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `is_admin` tinyint(1) DEFAULT '0',
  `profile_picture_url` varchar(255) DEFAULT NULL,
  `user_type` enum('customer','admin') DEFAULT 'customer',
  `registration_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `is_admin`, `profile_picture_url`, `user_type`, `registration_date`, `created_at`, `updated_at`) VALUES
(1, 'user1', 'user@users.com', '$2y$10$nirWPrATQ/8xP6BKQihEou.Yv2ULMAFYjWEeZQx0HDUXsV0jxeA4W', 0, 'default-avatar.png', 'customer', '2025-07-01 06:57:04', '2025-07-01 06:57:04', '2025-07-01 07:28:27'),
(2, 'admin', 'admin@admin.com', '$2y$10$kgpD/RAadYoDgW1U/vr3Y.ZgQ0RcoR0HrBnYWZ211pltLrl6CqDcW', 1, 'admin.png', 'admin', '2025-07-02 19:13:53', '2025-07-02 19:13:53', '2025-07-02 19:14:35');
--
-- Database: `epi_store`
--
CREATE DATABASE IF NOT EXISTS `epi_store` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `epi_store`;

-- --------------------------------------------------------

--
-- Table structure for table `among`
--

DROP TABLE IF EXISTS `among`;
CREATE TABLE IF NOT EXISTS `among` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `among`
--

INSERT INTO `among` (`id`, `nom`) VALUES
(1, '803cfc2e1ef214b94d91a0d94f610fbe'),
(2, 'Pablo');

-- --------------------------------------------------------

--
-- Table structure for table `message`
--

DROP TABLE IF EXISTS `message`;
CREATE TABLE IF NOT EXISTS `message` (
  `id_message` int(11) NOT NULL AUTO_INCREMENT,
  `nom_user` varchar(255) NOT NULL,
  `email` text NOT NULL,
  `num_tel` int(11) NOT NULL,
  `message` text NOT NULL,
  PRIMARY KEY (`id_message`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `message`
--

INSERT INTO `message` (`id_message`, `nom_user`, `email`, `num_tel`, `message`) VALUES
(14, 'admin', '64e1b8d34f425d19e1ee2ea7236d3028', 202, 'admin'),
(15, 'Test', 'test@test.com', 1234, 'sam'),
(16, 'Test', 'machideau@gmail.com', 1234, 'sam'),
(17, 'anah', 'anah@gmail.com', 123445, 'salut'),
(18, 'anah', 'anah@gmail.com', 12445, 'salut'),
(19, 'Test1', 'test1@test.com', 123, '123'),
(20, 'Test', 'machideau@gmail.com', 123, '123');

-- --------------------------------------------------------

--
-- Table structure for table `produits`
--

DROP TABLE IF EXISTS `produits`;
CREATE TABLE IF NOT EXISTS `produits` (
  `id_produits` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) NOT NULL,
  `prix` int(11) NOT NULL,
  `image` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  PRIMARY KEY (`id_produits`)
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `produits`
--

INSERT INTO `produits` (`id_produits`, `nom`, `prix`, `image`, `description`) VALUES
(1, 'montre classe', 2500, 'w1.jpg', 'montre de classe de couleur noir'),
(2, 'chaussures rouge-noires', 5000, 'c2.jpg', 'bonne chaussures rouge-noires'),
(3, 'chaussures blanches', 25, 'c1.jpg', 'bonne'),
(17, 'Pasca', 200, 'Screenshot_20250412-150530.png', 'test');

-- --------------------------------------------------------

--
-- Table structure for table `users_email`
--

DROP TABLE IF EXISTS `users_email`;
CREATE TABLE IF NOT EXISTS `users_email` (
  `id_mail` int(11) NOT NULL AUTO_INCREMENT,
  `email` text NOT NULL,
  PRIMARY KEY (`id_mail`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
--
-- Database: `gestion_scolaire`
--
CREATE DATABASE IF NOT EXISTS `gestion_scolaire` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `gestion_scolaire`;

-- --------------------------------------------------------

--
-- Table structure for table `classes`
--

DROP TABLE IF EXISTS `classes`;
CREATE TABLE IF NOT EXISTS `classes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom_classe` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nom_classe` (`nom_classe`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `classes`
--

INSERT INTO `classes` (`id`, `nom_classe`) VALUES
(1, 'Classe A'),
(2, 'Classe B'),
(3, 'Classe C');

-- --------------------------------------------------------

--
-- Table structure for table `eleves`
--

DROP TABLE IF EXISTS `eleves`;
CREATE TABLE IF NOT EXISTS `eleves` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) NOT NULL,
  `prenom` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `date_naissance` date NOT NULL,
  `classe_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `classe_id` (`classe_id`)
) ENGINE=MyISAM AUTO_INCREMENT=56 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `eleves`
--

INSERT INTO `eleves` (`id`, `nom`, `prenom`, `email`, `date_naissance`, `classe_id`) VALUES
(1, 'Dupont', 'Alice', 'alice.dupont@email.com', '2002-05-14', 1),
(2, 'Martin', 'Julien', 'julien.martin@email.com', '2001-08-22', 2),
(3, 'Bernard', 'Sophie', 'sophie.bernard@email.com', '2003-03-10', 3),
(4, 'Dubois', 'Thomas', 'thomas.dubois@email.com', '2002-11-05', 1),
(5, 'Morel', 'Laura', 'laura.morel@email.com', '2000-12-30', 2),
(6, 'Simon', 'Hugo', 'hugo.simon@email.com', '2001-07-19', 3),
(7, 'Lefevre', 'Emma', 'emma.lefevre@email.com', '2003-02-25', 1),
(8, 'Fournier', 'Lucas', 'lucas.fournier@email.com', '2000-06-15', 2),
(9, 'Bonnet', 'Nicolas', 'nicolas.bonnet@email.com', '2001-05-27', 1),
(10, 'Lambert', 'Clara', 'clara.lambert@email.com', '2003-09-14', 2),
(11, 'Rousseau', 'Paul', 'paul.rousseau@email.com', '2000-04-09', 3),
(12, 'Muller', 'Antoine', 'antoine.muller@email.com', '2003-07-21', 2),
(13, 'Faure', 'Camille', 'camille.faure@email.com', '2001-03-05', 3),
(14, 'Dupuis', 'Mathieu', 'mathieu.dupuis@email.com', '2001-10-18', 3),
(15, 'Marchand', 'Alice', 'alice.marchand@email.com', '2003-06-09', 1),
(16, 'Pelletier', 'Maxime', 'maxime.pelletier@email.com', '2000-11-24', 2),
(17, 'Gauthier', 'Julie', 'julie.gauthier@email.com', '2003-08-11', 2),
(18, 'Masson', 'Valentin', 'valentin.masson@email.com', '2000-07-13', 3),
(19, 'Blanchard', 'Manon', 'manon.blanchard@email.com', '2001-12-05', 1),
(20, 'Perrin', 'Adrien', 'adrien.perrin@email.com', '2002-09-23', 2),
(21, 'Dufour', 'Eva', 'eva.dufour@email.com', '2003-04-30', 3),
(22, 'Caron', 'Quentin', 'quentin.caron@email.com', '2000-10-21', 1),
(23, 'Picard', 'Sarah', 'sarah.picard@email.com', '2001-06-16', 2),
(24, 'Regnier', 'Bastien', 'bastien.regnier@email.com', '2002-03-12', 3),
(25, 'Smith', 'John', 'john.smith@email.com', '2005-03-12', 1),
(26, 'Johnson', 'Emma', 'emma.johnson@email.com', '2004-07-25', 2),
(27, 'Williams', 'Liam', 'liam.williams@email.com', '2006-01-18', 3),
(28, 'Brown', 'Olivia', 'olivia.brown@email.com', '2005-09-30', 1),
(29, 'Jones', 'Noah', 'noah.jones@email.com', '2004-11-15', 2),
(30, 'Garcia', 'Ava', 'ava.garcia@email.com', '2006-05-21', 3),
(31, 'Miller', 'Sophia', 'sophia.miller@email.com', '2005-06-10', 1),
(32, 'Davis', 'Lucas', 'lucas.davis@email.com', '2004-02-28', 2),
(33, 'Rodriguez', 'Mia', 'mia.rodriguez@email.com', '2006-08-14', 3),
(34, 'Martinez', 'Ethan', 'ethan.martinez@email.com', '2005-12-03', 1),
(35, 'Hernandez', 'Isabella', 'isabella.hernandez@email.com', '2004-04-29', 2),
(36, 'Lopez', 'Mason', 'mason.lopez@email.com', '2006-07-08', 3),
(37, 'Gonzalez', 'Amelia', 'amelia.gonzalez@email.com', '2005-10-20', 1),
(38, 'Wilson', 'Logan', 'logan.wilson@email.com', '2004-01-05', 2),
(39, 'Anderson', 'Harper', 'harper.anderson@email.com', '2006-03-17', 3),
(40, 'Thomas', 'Elijah', 'elijah.thomas@email.com', '2005-05-09', 1),
(41, 'Taylor', 'Evelyn', 'evelyn.taylor@email.com', '2004-08-22', 2),
(42, 'Moore', 'Daniel', 'daniel.moore@email.com', '2006-09-11', 3),
(43, 'Jackson', 'Lily', 'lily.jackson@email.com', '2005-12-25', 1),
(44, 'Martin', 'Matthew', 'matthew.martin@email.com', '2004-06-06', 2),
(45, 'Lee', 'Scarlett', 'scarlett.lee@email.com', '2006-04-18', 3),
(46, 'Perez', 'Samuel', 'samuel.perez@email.com', '2005-07-14', 1),
(47, 'Thompson', 'Chloe', 'chloe.thompson@email.com', '2004-10-09', 2),
(48, 'White', 'Henry', 'henry.white@email.com', '2006-11-02', 3),
(49, 'Harris', 'Ella', 'ella.harris@email.com', '2005-03-27', 1),
(50, 'Clark', 'David', 'david.clark@email.com', '2004-09-12', 2),
(51, 'Lewis', 'Grace', 'grace.lewis@email.com', '2006-06-30', 3),
(52, 'Walker', 'Benjamin', 'benjamin.walker@email.com', '2005-01-22', 1),
(53, 'Hall', 'Victoria', 'victoria.hall@email.com', '2004-12-17', 2),
(54, 'Allen', 'Carter', 'carter.allen@email.com', '2006-02-05', 3),
(55, 'v', 'gvg', 'directeur@admin.com', '2000-07-08', 1);

-- --------------------------------------------------------

--
-- Table structure for table `enseignant_classes`
--

DROP TABLE IF EXISTS `enseignant_classes`;
CREATE TABLE IF NOT EXISTS `enseignant_classes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `utilisateur_id` int(11) NOT NULL,
  `classe_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `utilisateur_id` (`utilisateur_id`),
  KEY `classe_id` (`classe_id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `enseignant_classes`
--

INSERT INTO `enseignant_classes` (`id`, `utilisateur_id`, `classe_id`) VALUES
(1, 1, 1),
(2, 2, 3),
(3, 3, 1),
(4, 6, 3),
(5, 7, 1),
(6, 8, 3),
(7, 9, 1),
(8, 9, 2),
(9, 9, 3),
(10, 10, 2),
(11, 11, 1),
(12, 11, 2);

-- --------------------------------------------------------

--
-- Table structure for table `enseignant_matieres`
--

DROP TABLE IF EXISTS `enseignant_matieres`;
CREATE TABLE IF NOT EXISTS `enseignant_matieres` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `utilisateur_id` int(11) NOT NULL,
  `matiere_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `utilisateur_id` (`utilisateur_id`),
  KEY `matiere_id` (`matiere_id`)
) ENGINE=MyISAM AUTO_INCREMENT=23 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `enseignant_matieres`
--

INSERT INTO `enseignant_matieres` (`id`, `utilisateur_id`, `matiere_id`) VALUES
(1, 1, 1),
(2, 1, 2),
(3, 2, 8),
(4, 3, 3),
(5, 6, 8),
(6, 6, 9),
(7, 7, 1),
(8, 8, 7),
(9, 8, 8),
(10, 8, 9),
(11, 9, 1),
(12, 9, 2),
(13, 9, 3),
(14, 9, 4),
(15, 9, 5),
(16, 9, 6),
(17, 9, 7),
(18, 9, 8),
(19, 9, 9),
(20, 10, 5),
(21, 11, 1),
(22, 11, 5);

-- --------------------------------------------------------

--
-- Table structure for table `enseignant_matieres_classes`
--

DROP TABLE IF EXISTS `enseignant_matieres_classes`;
CREATE TABLE IF NOT EXISTS `enseignant_matieres_classes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `utilisateur_id` int(11) NOT NULL,
  `matiere_id` int(11) NOT NULL,
  `classe_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_enseignant_matiere_classe` (`utilisateur_id`,`matiere_id`,`classe_id`),
  KEY `matiere_id` (`matiere_id`),
  KEY `classe_id` (`classe_id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `enseignant_matieres_classes`
--

INSERT INTO `enseignant_matieres_classes` (`id`, `utilisateur_id`, `matiere_id`, `classe_id`) VALUES
(1, 9, 1, 1),
(2, 9, 2, 1),
(3, 9, 3, 1),
(4, 9, 4, 2),
(5, 9, 5, 2),
(6, 9, 6, 2),
(7, 9, 7, 3),
(8, 9, 8, 3),
(9, 9, 9, 3),
(10, 10, 5, 2),
(11, 11, 1, 1),
(12, 11, 5, 2);

-- --------------------------------------------------------

--
-- Table structure for table `matieres`
--

DROP TABLE IF EXISTS `matieres`;
CREATE TABLE IF NOT EXISTS `matieres` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom_matiere` varchar(100) NOT NULL,
  `classe_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nom_matiere` (`nom_matiere`),
  KEY `classe_id` (`classe_id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `matieres`
--

INSERT INTO `matieres` (`id`, `nom_matiere`, `classe_id`) VALUES
(1, 'Math1', 1),
(2, 'Science1', 1),
(3, 'Histoire1', 1),
(4, 'Math2', 2),
(5, 'Science2', 2),
(6, 'Histoire2', 2),
(7, 'Math3', 3),
(8, 'Science3', 3),
(9, 'Histoire3', 3);

-- --------------------------------------------------------

--
-- Table structure for table `notes`
--

DROP TABLE IF EXISTS `notes`;
CREATE TABLE IF NOT EXISTS `notes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `eleve_id` int(11) NOT NULL,
  `matiere_id` int(11) NOT NULL,
  `note_classe` decimal(4,2) NOT NULL,
  `note_devoir` decimal(4,2) NOT NULL,
  `note_composition` decimal(4,2) NOT NULL,
  `trimestre` int(11) NOT NULL,
  `date_ajout` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_note` (`eleve_id`,`matiere_id`,`trimestre`),
  KEY `matiere_id` (`matiere_id`)
) ENGINE=MyISAM AUTO_INCREMENT=94 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `notes`
--

INSERT INTO `notes` (`id`, `eleve_id`, `matiere_id`, `note_classe`, `note_devoir`, `note_composition`, `trimestre`, `date_ajout`) VALUES
(1, 22, 1, '13.00', '14.00', '12.00', 1, '2025-03-20 13:08:28'),
(2, 19, 1, '17.00', '18.00', '16.00', 3, '2025-03-20 13:08:28'),
(3, 15, 1, '17.00', '18.00', '16.00', 1, '2025-03-20 13:08:28'),
(4, 9, 1, '16.00', '17.00', '16.00', 1, '2025-03-20 13:08:28'),
(5, 7, 1, '15.00', '14.00', '16.00', 3, '2025-03-20 13:08:28'),
(6, 4, 1, '16.00', '17.00', '15.00', 2, '2025-03-20 13:08:28'),
(7, 25, 1, '14.00', '15.00', '13.00', 1, '2025-03-20 13:08:28'),
(8, 28, 1, '16.00', '17.00', '15.00', 1, '2025-03-20 13:08:28'),
(9, 31, 1, '15.00', '16.00', '14.00', 1, '2025-03-20 13:08:28'),
(10, 34, 1, '17.00', '18.00', '16.00', 1, '2025-03-20 13:08:28'),
(11, 37, 1, '13.00', '14.00', '12.00', 1, '2025-03-20 13:08:29'),
(12, 40, 1, '18.00', '19.00', '17.00', 1, '2025-03-20 13:08:29'),
(13, 43, 1, '14.00', '13.00', '15.00', 1, '2025-03-20 13:08:29'),
(14, 46, 1, '16.00', '15.00', '14.00', 1, '2025-03-20 13:08:29'),
(15, 49, 1, '17.00', '16.00', '15.00', 1, '2025-03-20 13:08:29'),
(16, 52, 1, '15.00', '14.00', '13.00', 1, '2025-03-20 13:08:29'),
(17, 25, 2, '14.00', '15.00', '13.00', 1, '2025-03-20 13:08:29'),
(18, 28, 2, '16.00', '17.00', '15.00', 1, '2025-03-20 13:08:29'),
(19, 31, 2, '15.00', '16.00', '14.00', 1, '2025-03-20 13:08:29'),
(20, 34, 2, '17.00', '18.00', '16.00', 1, '2025-03-20 13:08:29'),
(21, 37, 2, '13.00', '14.00', '12.00', 1, '2025-03-20 13:08:29'),
(22, 40, 2, '18.00', '19.00', '17.00', 1, '2025-03-20 13:08:29'),
(23, 43, 2, '14.00', '13.00', '15.00', 1, '2025-03-20 13:08:29'),
(24, 46, 2, '16.00', '15.00', '14.00', 1, '2025-03-20 13:08:29'),
(25, 49, 2, '17.00', '16.00', '15.00', 1, '2025-03-20 13:08:29'),
(26, 52, 2, '15.00', '14.00', '13.00', 1, '2025-03-20 13:08:29'),
(27, 25, 3, '14.00', '15.00', '13.00', 1, '2025-03-20 13:08:29'),
(28, 28, 3, '16.00', '17.00', '15.00', 1, '2025-03-20 13:08:29'),
(29, 31, 3, '15.00', '16.00', '14.00', 1, '2025-03-20 13:08:29'),
(30, 34, 3, '17.00', '18.00', '16.00', 1, '2025-03-20 13:08:29'),
(31, 37, 3, '13.00', '14.00', '12.00', 1, '2025-03-20 13:08:29'),
(32, 40, 3, '18.00', '19.00', '17.00', 1, '2025-03-20 13:08:29'),
(33, 43, 3, '14.00', '13.00', '15.00', 1, '2025-03-20 13:08:29'),
(34, 46, 3, '16.00', '15.00', '14.00', 1, '2025-03-20 13:08:29'),
(35, 49, 3, '17.00', '16.00', '15.00', 1, '2025-03-20 13:08:29'),
(36, 52, 3, '15.00', '14.00', '13.00', 1, '2025-03-20 13:08:29'),
(37, 26, 4, '15.00', '16.00', '14.00', 1, '2025-03-20 13:08:29'),
(38, 29, 4, '17.00', '18.00', '16.00', 1, '2025-03-20 13:08:29'),
(39, 32, 4, '14.00', '15.00', '13.00', 1, '2025-03-20 13:08:29'),
(40, 35, 4, '16.00', '17.00', '15.00', 1, '2025-03-20 13:08:29'),
(41, 38, 4, '18.00', '19.00', '17.00', 1, '2025-03-20 13:08:29'),
(42, 44, 4, '13.00', '14.00', '12.00', 1, '2025-03-20 13:08:29'),
(43, 47, 4, '15.00', '16.00', '14.00', 1, '2025-03-20 13:08:29'),
(44, 50, 4, '14.00', '13.00', '12.00', 1, '2025-03-20 13:08:29'),
(45, 53, 4, '17.00', '16.00', '15.00', 1, '2025-03-20 13:08:29'),
(46, 26, 5, '15.00', '16.00', '14.00', 1, '2025-03-20 13:08:29'),
(47, 29, 5, '17.00', '18.00', '16.00', 1, '2025-03-20 13:08:29'),
(48, 32, 5, '14.00', '15.00', '13.00', 1, '2025-03-20 13:08:29'),
(49, 35, 5, '16.00', '17.00', '15.00', 1, '2025-03-20 13:08:29'),
(50, 38, 5, '18.00', '19.00', '17.00', 1, '2025-03-20 13:08:29'),
(51, 44, 5, '13.00', '14.00', '12.00', 1, '2025-03-20 13:08:29'),
(52, 47, 5, '15.00', '16.00', '14.00', 1, '2025-03-20 13:08:29'),
(53, 50, 5, '14.00', '13.00', '12.00', 1, '2025-03-20 13:08:29'),
(54, 53, 5, '17.00', '16.00', '15.00', 1, '2025-03-20 13:08:29'),
(55, 26, 6, '15.00', '16.00', '14.00', 1, '2025-03-20 13:08:29'),
(56, 29, 6, '17.00', '18.00', '16.00', 1, '2025-03-20 13:08:29'),
(57, 32, 6, '14.00', '15.00', '13.00', 1, '2025-03-20 13:08:29'),
(58, 35, 6, '16.00', '17.00', '15.00', 1, '2025-03-20 13:08:29'),
(59, 38, 6, '18.00', '19.00', '17.00', 1, '2025-03-20 13:08:29'),
(60, 44, 6, '13.00', '14.00', '12.00', 1, '2025-03-20 13:08:29'),
(61, 47, 6, '15.00', '16.00', '14.00', 1, '2025-03-20 13:08:29'),
(62, 50, 6, '14.00', '13.00', '12.00', 1, '2025-03-20 13:08:29'),
(63, 53, 6, '17.00', '16.00', '15.00', 1, '2025-03-20 13:08:29'),
(64, 27, 7, '14.00', '15.00', '13.00', 1, '2025-03-20 13:08:29'),
(65, 30, 7, '16.00', '17.00', '15.00', 1, '2025-03-20 13:08:29'),
(66, 33, 7, '17.00', '18.00', '16.00', 1, '2025-03-20 13:08:29'),
(67, 36, 7, '15.00', '16.00', '14.00', 1, '2025-03-20 13:08:29'),
(68, 39, 7, '18.00', '19.00', '17.00', 1, '2025-03-20 13:08:29'),
(69, 42, 7, '13.00', '14.00', '12.00', 1, '2025-03-20 13:08:29'),
(70, 45, 7, '16.00', '17.00', '15.00', 1, '2025-03-20 13:08:29'),
(71, 48, 7, '14.00', '13.00', '12.00', 1, '2025-03-20 13:08:29'),
(72, 54, 7, '17.00', '16.00', '15.00', 1, '2025-03-20 13:08:29'),
(73, 51, 7, '15.00', '14.00', '13.00', 1, '2025-03-20 13:08:29'),
(74, 27, 8, '14.00', '15.00', '13.00', 1, '2025-03-20 13:08:29'),
(75, 30, 8, '16.00', '17.00', '15.00', 1, '2025-03-20 13:08:29'),
(76, 33, 8, '17.00', '18.00', '16.00', 1, '2025-03-20 13:08:29'),
(77, 36, 8, '15.00', '16.00', '14.00', 1, '2025-03-20 13:08:29'),
(78, 39, 8, '18.00', '19.00', '17.00', 1, '2025-03-20 13:08:29'),
(79, 42, 8, '13.00', '14.00', '12.00', 1, '2025-03-20 13:08:29'),
(80, 45, 8, '16.00', '17.00', '15.00', 1, '2025-03-20 13:08:29'),
(81, 48, 8, '14.00', '13.00', '12.00', 1, '2025-03-20 13:08:29'),
(82, 54, 8, '17.00', '16.00', '15.00', 1, '2025-03-20 13:08:29'),
(83, 51, 8, '15.00', '14.00', '13.00', 1, '2025-03-20 13:08:29'),
(84, 27, 9, '14.00', '15.00', '13.00', 1, '2025-03-20 13:08:29'),
(85, 30, 9, '16.00', '17.00', '15.00', 1, '2025-03-20 13:08:29'),
(86, 33, 9, '17.00', '18.00', '16.00', 1, '2025-03-20 13:08:29'),
(87, 36, 9, '15.00', '16.00', '14.00', 1, '2025-03-20 13:08:29'),
(88, 39, 9, '18.00', '19.00', '17.00', 1, '2025-03-20 13:08:29'),
(89, 42, 9, '13.00', '14.00', '12.00', 1, '2025-03-20 13:08:29'),
(90, 45, 9, '16.00', '17.00', '15.00', 1, '2025-03-20 13:08:29'),
(91, 48, 9, '14.00', '13.00', '12.00', 1, '2025-03-20 13:08:29'),
(92, 54, 9, '17.00', '16.00', '15.00', 1, '2025-03-20 13:08:29'),
(93, 51, 9, '15.00', '14.00', '13.00', 1, '2025-03-20 13:08:29');

-- --------------------------------------------------------

--
-- Table structure for table `notes_backup`
--

DROP TABLE IF EXISTS `notes_backup`;
CREATE TABLE IF NOT EXISTS `notes_backup` (
  `id` int(11) NOT NULL DEFAULT '0',
  `eleve_id` int(11) NOT NULL,
  `matiere_id` int(11) NOT NULL,
  `note_classe` decimal(4,2) NOT NULL,
  `note_devoir` decimal(4,2) NOT NULL,
  `note_composition` decimal(4,2) NOT NULL,
  `trimestre` int(11) NOT NULL,
  `date_ajout` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `notes_backup`
--

INSERT INTO `notes_backup` (`id`, `eleve_id`, `matiere_id`, `note_classe`, `note_devoir`, `note_composition`, `trimestre`, `date_ajout`) VALUES
(12, 22, 1, '13.00', '14.00', '12.00', 1, '2025-03-20 03:19:14'),
(11, 19, 1, '17.00', '18.00', '16.00', 3, '2025-03-20 03:19:14'),
(10, 15, 1, '17.00', '18.00', '16.00', 1, '2025-03-20 03:19:14'),
(9, 9, 1, '16.00', '17.00', '16.00', 1, '2025-03-20 03:19:14'),
(8, 7, 1, '15.00', '14.00', '16.00', 3, '2025-03-20 03:19:14'),
(7, 4, 1, '16.00', '17.00', '15.00', 2, '2025-03-20 03:19:14'),
(13, 25, 1, '14.00', '15.00', '13.00', 1, '2025-03-20 11:25:41'),
(14, 28, 1, '16.00', '17.00', '15.00', 1, '2025-03-20 11:25:41'),
(15, 31, 1, '15.00', '16.00', '14.00', 1, '2025-03-20 11:25:41'),
(16, 34, 1, '17.00', '18.00', '16.00', 1, '2025-03-20 11:25:41'),
(17, 37, 1, '13.00', '14.00', '12.00', 1, '2025-03-20 11:25:41'),
(18, 40, 1, '18.00', '19.00', '17.00', 1, '2025-03-20 11:25:41'),
(19, 43, 1, '14.00', '13.00', '15.00', 1, '2025-03-20 11:25:41'),
(20, 46, 1, '16.00', '15.00', '14.00', 1, '2025-03-20 11:25:41'),
(21, 49, 1, '17.00', '16.00', '15.00', 1, '2025-03-20 11:25:41'),
(22, 52, 1, '15.00', '14.00', '13.00', 1, '2025-03-20 11:25:41'),
(23, 25, 2, '14.00', '15.00', '13.00', 1, '2025-03-20 11:26:12'),
(24, 28, 2, '16.00', '17.00', '15.00', 1, '2025-03-20 11:26:12'),
(25, 31, 2, '15.00', '16.00', '14.00', 1, '2025-03-20 11:26:12'),
(26, 34, 2, '17.00', '18.00', '16.00', 1, '2025-03-20 11:26:12'),
(27, 37, 2, '13.00', '14.00', '12.00', 1, '2025-03-20 11:26:12'),
(28, 40, 2, '18.00', '19.00', '17.00', 1, '2025-03-20 11:26:12'),
(29, 43, 2, '14.00', '13.00', '15.00', 1, '2025-03-20 11:26:12'),
(30, 46, 2, '16.00', '15.00', '14.00', 1, '2025-03-20 11:26:12'),
(31, 49, 2, '17.00', '16.00', '15.00', 1, '2025-03-20 11:26:12'),
(32, 52, 2, '15.00', '14.00', '13.00', 1, '2025-03-20 11:26:12'),
(33, 25, 3, '14.00', '15.00', '13.00', 1, '2025-03-20 11:26:33'),
(34, 28, 3, '16.00', '17.00', '15.00', 1, '2025-03-20 11:26:33'),
(35, 31, 3, '15.00', '16.00', '14.00', 1, '2025-03-20 11:26:33'),
(36, 34, 3, '17.00', '18.00', '16.00', 1, '2025-03-20 11:26:33'),
(37, 37, 3, '13.00', '14.00', '12.00', 1, '2025-03-20 11:26:33'),
(38, 40, 3, '18.00', '19.00', '17.00', 1, '2025-03-20 11:26:33'),
(39, 43, 3, '14.00', '13.00', '15.00', 1, '2025-03-20 11:26:33'),
(40, 46, 3, '16.00', '15.00', '14.00', 1, '2025-03-20 11:26:33'),
(41, 49, 3, '17.00', '16.00', '15.00', 1, '2025-03-20 11:26:33'),
(42, 52, 3, '15.00', '14.00', '13.00', 1, '2025-03-20 11:26:33'),
(43, 26, 4, '15.00', '16.00', '14.00', 1, '2025-03-20 11:26:58'),
(44, 29, 4, '17.00', '18.00', '16.00', 1, '2025-03-20 11:26:58'),
(45, 32, 4, '14.00', '15.00', '13.00', 1, '2025-03-20 11:26:58'),
(46, 35, 4, '16.00', '17.00', '15.00', 1, '2025-03-20 11:26:58'),
(47, 38, 4, '18.00', '19.00', '17.00', 1, '2025-03-20 11:26:58'),
(48, 44, 4, '13.00', '14.00', '12.00', 1, '2025-03-20 11:26:58'),
(49, 47, 4, '15.00', '16.00', '14.00', 1, '2025-03-20 11:26:58'),
(50, 50, 4, '14.00', '13.00', '12.00', 1, '2025-03-20 11:26:58'),
(51, 53, 4, '17.00', '16.00', '15.00', 1, '2025-03-20 11:26:58'),
(52, 26, 5, '15.00', '16.00', '14.00', 1, '2025-03-20 11:27:24'),
(53, 29, 5, '17.00', '18.00', '16.00', 1, '2025-03-20 11:27:24'),
(54, 32, 5, '14.00', '15.00', '13.00', 1, '2025-03-20 11:27:24'),
(55, 35, 5, '16.00', '17.00', '15.00', 1, '2025-03-20 11:27:24'),
(56, 38, 5, '18.00', '19.00', '17.00', 1, '2025-03-20 11:27:24'),
(57, 44, 5, '13.00', '14.00', '12.00', 1, '2025-03-20 11:27:24'),
(58, 47, 5, '15.00', '16.00', '14.00', 1, '2025-03-20 11:27:24'),
(59, 50, 5, '14.00', '13.00', '12.00', 1, '2025-03-20 11:27:24'),
(60, 53, 5, '17.00', '16.00', '15.00', 1, '2025-03-20 11:27:24'),
(61, 26, 6, '15.00', '16.00', '14.00', 1, '2025-03-20 11:27:52'),
(62, 29, 6, '17.00', '18.00', '16.00', 1, '2025-03-20 11:27:52'),
(63, 32, 6, '14.00', '15.00', '13.00', 1, '2025-03-20 11:27:52'),
(64, 35, 6, '16.00', '17.00', '15.00', 1, '2025-03-20 11:27:52'),
(65, 38, 6, '18.00', '19.00', '17.00', 1, '2025-03-20 11:27:52'),
(66, 44, 6, '13.00', '14.00', '12.00', 1, '2025-03-20 11:27:52'),
(67, 47, 6, '15.00', '16.00', '14.00', 1, '2025-03-20 11:27:52'),
(68, 50, 6, '14.00', '13.00', '12.00', 1, '2025-03-20 11:27:52'),
(69, 53, 6, '17.00', '16.00', '15.00', 1, '2025-03-20 11:27:52'),
(70, 27, 7, '14.00', '15.00', '13.00', 1, '2025-03-20 11:28:15'),
(71, 30, 7, '16.00', '17.00', '15.00', 1, '2025-03-20 11:28:15'),
(72, 33, 7, '17.00', '18.00', '16.00', 1, '2025-03-20 11:28:15'),
(73, 36, 7, '15.00', '16.00', '14.00', 1, '2025-03-20 11:28:15'),
(74, 39, 7, '18.00', '19.00', '17.00', 1, '2025-03-20 11:28:15'),
(75, 42, 7, '13.00', '14.00', '12.00', 1, '2025-03-20 11:28:15'),
(76, 45, 7, '16.00', '17.00', '15.00', 1, '2025-03-20 11:28:15'),
(77, 48, 7, '14.00', '13.00', '12.00', 1, '2025-03-20 11:28:15'),
(78, 54, 7, '17.00', '16.00', '15.00', 1, '2025-03-20 11:28:15'),
(79, 51, 7, '15.00', '14.00', '13.00', 1, '2025-03-20 11:28:15'),
(80, 27, 8, '14.00', '15.00', '13.00', 1, '2025-03-20 11:28:41'),
(81, 30, 8, '16.00', '17.00', '15.00', 1, '2025-03-20 11:28:41'),
(82, 33, 8, '17.00', '18.00', '16.00', 1, '2025-03-20 11:28:41'),
(83, 36, 8, '15.00', '16.00', '14.00', 1, '2025-03-20 11:28:41'),
(84, 39, 8, '18.00', '19.00', '17.00', 1, '2025-03-20 11:28:41'),
(85, 42, 8, '13.00', '14.00', '12.00', 1, '2025-03-20 11:28:41'),
(86, 45, 8, '16.00', '17.00', '15.00', 1, '2025-03-20 11:28:41'),
(87, 48, 8, '14.00', '13.00', '12.00', 1, '2025-03-20 11:28:41'),
(88, 54, 8, '17.00', '16.00', '15.00', 1, '2025-03-20 11:28:41'),
(89, 51, 8, '15.00', '14.00', '13.00', 1, '2025-03-20 11:28:41'),
(90, 27, 9, '14.00', '15.00', '13.00', 1, '2025-03-20 11:29:00'),
(91, 30, 9, '16.00', '17.00', '15.00', 1, '2025-03-20 11:29:00'),
(92, 33, 9, '17.00', '18.00', '16.00', 1, '2025-03-20 11:29:00'),
(93, 36, 9, '15.00', '16.00', '14.00', 1, '2025-03-20 11:29:00'),
(94, 39, 9, '18.00', '19.00', '17.00', 1, '2025-03-20 11:29:00'),
(95, 42, 9, '13.00', '14.00', '12.00', 1, '2025-03-20 11:29:00'),
(96, 45, 9, '16.00', '17.00', '15.00', 1, '2025-03-20 11:29:00'),
(97, 48, 9, '14.00', '13.00', '12.00', 1, '2025-03-20 11:29:00'),
(98, 54, 9, '17.00', '16.00', '15.00', 1, '2025-03-20 11:29:00'),
(99, 51, 9, '15.00', '14.00', '13.00', 1, '2025-03-20 11:29:00');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
CREATE TABLE IF NOT EXISTS `roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nom` (`nom`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `nom`) VALUES
(1, 'admin'),
(2, 'enseignant'),
(3, 'directeur');

-- --------------------------------------------------------

--
-- Table structure for table `utilisateurs`
--

DROP TABLE IF EXISTS `utilisateurs`;
CREATE TABLE IF NOT EXISTS `utilisateurs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) NOT NULL,
  `prenom` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `mot_de_passe` varchar(255) NOT NULL,
  `telephone` varchar(20) DEFAULT NULL,
  `role_id` int(11) NOT NULL,
  `date_inscription` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `statut` varchar(20) DEFAULT 'pending',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `utilisateurs`
--

INSERT INTO `utilisateurs` (`id`, `nom`, `prenom`, `email`, `mot_de_passe`, `telephone`, `role_id`, `date_inscription`, `statut`) VALUES
(4, 'Admin', 'System', 'admin@admin.com', '$2y$10$mNVm2muGr3xa5eSSz44ZjOe4qtSAy0CvANSPOj9LR1kXrkxAuHyhS', '0123456789', 1, '2025-03-19 23:35:48', 'approved'),
(5, 'Directeur', 'Principal', 'directeur@admin.com', '$2y$10$mNVm2muGr3xa5eSSz44ZjOe4qtSAy0CvANSPOj9LR1kXrkxAuHyhS', '0987654321', 3, '2025-03-19 23:35:48', 'approved'),
(11, 'USER', 'user', 'user@users.com', '$2y$10$qataF8J6vnw8WcCh13yD3eSi86Y3QemUY8OKU9hfwnBwqllVswPTO', '1234', 2, '2025-06-21 08:21:00', 'approved');
--
-- Database: `learn`
--
CREATE DATABASE IF NOT EXISTS `learn` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `learn`;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` text NOT NULL,
  `profile_image` varchar(255) DEFAULT 'default.jpg',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `profile_image`) VALUES
(9, 'Pro1', 'moisesamledev@gmail.com', '1234', 'default-avatar.png'),
(6, 'Test', 'test@test.com', '1234', 'default-avatar.png'),
(10, 'uhe', 'admin@admin.com', '1234', 'default-avatar.png');
--
-- Database: `panier`
--
CREATE DATABASE IF NOT EXISTS `panier` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `panier`;

-- --------------------------------------------------------

--
-- Table structure for table `produits`
--

DROP TABLE IF EXISTS `produits`;
CREATE TABLE IF NOT EXISTS `produits` (
  `id` int(7) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `price` int(6) NOT NULL,
  `img` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `produits`
--

INSERT INTO `produits` (`id`, `name`, `price`, `img`) VALUES
(1, 'gourde', 20, 'gourde.png'),
(2, 'gourde 2', 15, 'gourde2.png');
--
-- Database: `portfolio`
--
CREATE DATABASE IF NOT EXISTS `portfolio` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `portfolio`;

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

DROP TABLE IF EXISTS `messages`;
CREATE TABLE IF NOT EXISTS `messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `status` enum('unread','read') DEFAULT 'unread',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_status` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `name`, `email`, `subject`, `message`, `status`, `created_at`) VALUES
(1, 'moi', 'sam@gmail.com', 'fgbfdf', 'xccccccccccccccc              vvvvvv', 'unread', '2025-03-06 20:19:59'),
(2, 'moi', 'sam@gmail.com', 'fgbfdf', 'xccccccccccccccc              vvvvvv', 'unread', '2025-03-06 20:21:21');

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

DROP TABLE IF EXISTS `projects`;
CREATE TABLE IF NOT EXISTS `projects` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `full_description` text NOT NULL,
  `image` varchar(255) NOT NULL,
  `category` enum('web','mobile','desktop','robotique','ai') NOT NULL,
  `tags` json DEFAULT NULL,
  `tech_stack` json DEFAULT NULL,
  `features` json DEFAULT NULL,
  `live_link` varchar(255) DEFAULT NULL,
  `github_link` varchar(255) DEFAULT NULL,
  `gallery` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `skills`
--

DROP TABLE IF EXISTS `skills`;
CREATE TABLE IF NOT EXISTS `skills` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `level` int(11) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `skills`
--

INSERT INTO `skills` (`id`, `category`, `name`, `level`, `created_at`, `updated_at`) VALUES
(1, 'Développement Web', 'HTML5 / CSS3', 0, '2025-03-06 18:19:29', '2025-03-06 18:19:29'),
(2, 'Développement Web', 'JavaScript', 0, '2025-03-06 18:19:29', '2025-03-06 18:19:29'),
(3, 'Développement Web', 'PHP', 0, '2025-03-06 18:19:29', '2025-03-06 18:19:29'),
(4, 'Développement Web', 'React.js', 0, '2025-03-06 18:19:29', '2025-03-06 18:19:29'),
(5, 'Développement Web', 'Node.js', 0, '2025-03-06 18:19:29', '2025-03-06 18:19:29'),
(6, 'Développement Mobile', 'React Native', 0, '2025-03-06 18:19:29', '2025-03-06 18:19:29'),
(7, 'Développement Mobile', 'Flutter', 0, '2025-03-06 18:19:29', '2025-03-06 18:19:29'),
(8, 'Développement Mobile', 'Android Studio', 0, '2025-03-06 18:19:29', '2025-03-06 18:19:29'),
(9, 'Robotique', 'Arduino', 0, '2025-03-06 18:19:29', '2025-03-06 18:19:29'),
(10, 'Robotique', 'Raspberry Pi', 0, '2025-03-06 18:19:29', '2025-03-06 18:19:29'),
(11, 'Robotique', 'Python', 0, '2025-03-06 18:19:29', '2025-03-06 18:19:29'),
(12, 'Robotique', 'C++', 0, '2025-03-06 18:19:29', '2025-03-06 18:19:29');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `profile_photo` varchar(255) DEFAULT 'images/default-avatar.webp',
  `role` enum('user','admin') DEFAULT 'user',
  `reset_token` varchar(255) DEFAULT NULL,
  `reset_token_expiry` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `idx_email` (`email`),
  KEY `idx_reset_token` (`reset_token`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `profile_photo`, `role`, `reset_token`, `reset_token_expiry`, `created_at`) VALUES
(1, 'Admin', 'admin@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'images/default-avatar.webp', 'admin', NULL, NULL, '2025-03-06 18:19:29');
--
-- Database: `portifolio`
--
CREATE DATABASE IF NOT EXISTS `portifolio` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `portifolio`;

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

DROP TABLE IF EXISTS `projects`;
CREATE TABLE IF NOT EXISTS `projects` (
  `id` int(7) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `Description` text NOT NULL,
  `link` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `projects`
--

INSERT INTO `projects` (`id`, `name`, `Description`, `link`, `image`) VALUES
(3, 'Premier', 'hudeuihdeu  uhuhdeyuuyie  uuwuwy fkmkdjddjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjj  jjjjjjjjjdd dddddddddddddddddddddddddddddddddddddddd ppppppppppppppppppppppppppppppppppppppppppppppppppppppppppppppppppppppppppppppppp eeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeee', 'https://exo.tg', 'logo12_14_205245.png'),
(4, 'machideau', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Impedit excepturi laborum enim, vitae ipsam atque eum, ad iusto consequuntur voluptas, esse doloribus. Perferendis porro quisquam vitae exercitationem aliquid, minus eos laborum repudiandae, cumque debitis iusto omnis praesentium? Laborum placeat sit adipisci illum tempore maxime, esse qui quae? Molestias excepturi corporis similique doloribus. Esse vitae earum architecto nulla non dolores illum at perspiciatis quod, et deleniti cupiditate reiciendis harum facere, delectus eum commodi soluta distinctio sit repudiandae possimus sunt. Ipsum, rem.', 'https://exo.ti', 'net.png');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(7) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
--
-- Database: `projetdb`
--
CREATE DATABASE IF NOT EXISTS `projetdb` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `projetdb`;

-- --------------------------------------------------------

--
-- Table structure for table `image`
--

DROP TABLE IF EXISTS `image`;
CREATE TABLE IF NOT EXISTS `image` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `Chemin` varchar(255) NOT NULL,
  `DateEnvoie` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`Id`)
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `image`
--

INSERT INTO `image` (`Id`, `Chemin`, `DateEnvoie`) VALUES
(13, 'uploads/logo12_23_181757.png', '2024-11-02 15:03:50'),
(15, 'uploads/logo12_23_181757.png', '2024-11-02 15:06:17'),
(1, 'uploads/1730558996_logo12_14_205245.png', '2024-11-02 14:49:56'),
(17, 'uploads/1741788534_s2.jpg', '2025-03-12 14:08:54'),
(18, 'uploads/1741788562_s2.jpg', '2025-03-12 14:09:22');

-- --------------------------------------------------------

--
-- Table structure for table `session`
--

DROP TABLE IF EXISTS `session`;
CREATE TABLE IF NOT EXISTS `session` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `Id_Utilisateur` int(11) DEFAULT NULL,
  `Date_Connexion` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`Id`),
  KEY `Id_Utilisateur` (`Id_Utilisateur`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `utilisateur`
--

DROP TABLE IF EXISTS `utilisateur`;
CREATE TABLE IF NOT EXISTS `utilisateur` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `Nom` varchar(50) NOT NULL,
  `Prenom` varchar(50) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `Role` enum('Admin','Utilisateur') NOT NULL,
  `Id_Image` int(11) DEFAULT NULL,
  `Password` varchar(255) NOT NULL,
  PRIMARY KEY (`Id`),
  UNIQUE KEY `Email` (`Email`),
  KEY `Id_Image` (`Id_Image`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `utilisateur`
--

INSERT INTO `utilisateur` (`Id`, `Nom`, `Prenom`, `Email`, `Role`, `Id_Image`, `Password`) VALUES
(1, 'admin', 'admin', 'admin@admin.tg', 'Admin', 1, '$2y$10$zgMzXeOgYSG97wOUpgbYSOKnnkELkm8BGpWzh.AFzhY8MmYkUSwha'),
(11, 'Test', 'hdhd', 'machideau@gmail.com', 'Utilisateur', 18, '$2y$10$tCXDbU9Qi/ZFJl3bNUZjMe741OE6xtPCNKlWy6AymheVGN9gPER6G');
--
-- Database: `sh`
--
CREATE DATABASE IF NOT EXISTS `sh` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `sh`;

-- --------------------------------------------------------

--
-- Table structure for table `eleves`
--

DROP TABLE IF EXISTS `eleves`;
CREATE TABLE IF NOT EXISTS `eleves` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) NOT NULL,
  `prenom` varchar(50) NOT NULL,
  `classe` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `eleves`
--

INSERT INTO `eleves` (`id`, `nom`, `prenom`, `classe`) VALUES
(1, 'Dupont', 'Jean', '6emeA'),
(2, 'Martin', 'Marie', '5emeB'),
(3, 'Durand', 'Pierre', '4emeC'),
(4, 'Bernard', 'Sophie', '3emeD'),
(5, 'Leroy', 'Thomas', '2emeE'),
(6, 'Petit', 'Lucie', '1ereF'),
(7, 'Moreau', 'Julien', 'TerminaleG'),
(8, 'Roux', 'Camille', '6emeA'),
(9, 'Girard', 'Hugo', '5emeB'),
(10, 'Laurent', 'In�s', '4emeC');
--
-- Database: `tuto-food`
--
CREATE DATABASE IF NOT EXISTS `tuto-food` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `tuto-food`;

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

DROP TABLE IF EXISTS `admin`;
CREATE TABLE IF NOT EXISTS `admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `username` varchar(30) NOT NULL,
  `password` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
--
-- Database: `tuto-upload_image`
--
CREATE DATABASE IF NOT EXISTS `tuto-upload_image` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `tuto-upload_image`;

-- --------------------------------------------------------

--
-- Table structure for table `user_form`
--

DROP TABLE IF EXISTS `user_form`;
CREATE TABLE IF NOT EXISTS `user_form` (
  `id` int(7) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` text NOT NULL,
  `image` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_form`
--

INSERT INTO `user_form` (`id`, `name`, `email`, `password`, `image`) VALUES
(1, 'machideau', 'machideau@gmail.com', '202cb962ac59075b964b07152d234b70', 'logo12_14_205245.png');
--
-- Database: `user_management`
--
CREATE DATABASE IF NOT EXISTS `user_management` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `user_management`;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `status` enum('pending','approved') DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password_hash`, `status`, `created_at`) VALUES
(1, 'mit', 'mit@mit.com', '$2y$10$y5fBgPf5sYc0.yCXHJ2NT.fmhgjSZXI3jLKJZpKWlIWAWe3.RWPZC', 'approved', '2025-03-11 07:30:33'),
(2, 'turc', 'turc@turc.com', '$2y$10$44Sog4sWkqdwT1CpCf7di.gAoUsmTR0Gvqch3mZzPDlJuZZUnlBFu', 'approved', '2025-03-11 07:40:43'),
(3, 'sam', 'sam@sam.tg', '$2y$10$E88xSh5nvCv4LupQYTjUv.1bqgf4.WMs5fJZIALzXIrMGZx6rpxtG', 'approved', '2025-04-22 12:26:57');
--
-- Database: `wap`
--
CREATE DATABASE IF NOT EXISTS `wap` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `wap`;

-- --------------------------------------------------------

--
-- Table structure for table `chats`
--

DROP TABLE IF EXISTS `chats`;
CREATE TABLE IF NOT EXISTS `chats` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` enum('individual','group') NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `createdAt` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `device_tokens`
--

DROP TABLE IF EXISTS `device_tokens`;
CREATE TABLE IF NOT EXISTS `device_tokens` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(255) NOT NULL,
  `userId` int(11) NOT NULL,
  `createdAt` datetime DEFAULT CURRENT_TIMESTAMP,
  `updatedAt` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `token` (`token`),
  KEY `userId` (`userId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

DROP TABLE IF EXISTS `messages`;
CREATE TABLE IF NOT EXISTS `messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `chatId` int(11) NOT NULL,
  `text` text NOT NULL,
  `senderId` int(11) NOT NULL,
  `createdAt` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `chatId` (`chatId`),
  KEY `senderId` (`senderId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `otps`
--

DROP TABLE IF EXISTS `otps`;
CREATE TABLE IF NOT EXISTS `otps` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `phone` varchar(20) NOT NULL,
  `code` varchar(6) NOT NULL,
  `expiresAt` datetime NOT NULL,
  `createdAt` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `phone_index` (`phone`),
  KEY `expiresAt_index` (`expiresAt`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `avatar` varchar(255) DEFAULT '',
  `status` varchar(255) DEFAULT 'Hey there! I am using SWap',
  `about` text,
  `isOnline` tinyint(1) DEFAULT '0',
  `lastSeen` datetime DEFAULT CURRENT_TIMESTAMP,
  `createdAt` datetime DEFAULT CURRENT_TIMESTAMP,
  `updatedAt` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `phone` (`phone`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `device_tokens`
--
ALTER TABLE `device_tokens`
  ADD CONSTRAINT `device_tokens_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`chatId`) REFERENCES `chats` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`senderId`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
