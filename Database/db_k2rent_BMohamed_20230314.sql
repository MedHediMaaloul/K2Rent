-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : mar. 14 mars 2023 à 10:49
-- Version du serveur : 10.4.22-MariaDB
-- Version de PHP : 7.3.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `db_k2rent`
--

-- --------------------------------------------------------

--
-- Structure de la table `agence`
--

CREATE TABLE `agence` (
  `id_agence` int(10) NOT NULL,
  `nom_agence` varchar(200) NOT NULL,
  `email_agence` varchar(200) NOT NULL,
  `tel_agence` varchar(200) NOT NULL,
  `action_agence` enum('0','1') NOT NULL DEFAULT '1',
  `date_created_agence` timestamp NOT NULL DEFAULT current_timestamp(),
  `date_updated_agence` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `agence`
--

INSERT INTO `agence` (`id_agence`, `nom_agence`, `email_agence`, `tel_agence`, `action_agence`, `date_created_agence`, `date_updated_agence`) VALUES
(0, '', '', '', '1', '2023-01-24 10:50:19', '2023-01-24 10:50:19'),
(1, 'Djerba', 'k2djerba@k2rent.tn', '75660251', '1', '2023-01-24 10:50:19', '2023-02-24 14:54:38'),
(2, 'Tunis', 'k2tunis@k2rent.tn', '73381456', '1', '2023-01-24 16:47:54', '2023-02-24 14:54:38');

-- --------------------------------------------------------

--
-- Structure de la table `assurance_voiture`
--

CREATE TABLE `assurance_voiture` (
  `id_assurancevoiture` int(11) NOT NULL,
  `id_voiture` int(11) NOT NULL,
  `prix_assurance` double NOT NULL,
  `date_fin_assurance` date NOT NULL,
  `file_assurance` varchar(200) NOT NULL,
  `view_notif` enum('0','1') NOT NULL DEFAULT '1',
  `etat_assurance` enum('0','1') NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `assurance_voiture`
--

INSERT INTO `assurance_voiture` (`id_assurancevoiture`, `id_voiture`, `prix_assurance`, `date_fin_assurance`, `file_assurance`, `view_notif`, `etat_assurance`) VALUES
(1, 6, 850, '2023-03-03', '82741640e163d5092c5e1bfb8a025757_2023.png', '1', '1'),
(3, 5, 800, '2023-03-05', 'ded7c475835b34538bbfeaac79242ee1.png', '1', '1'),
(4, 22, 800, '2023-03-11', '939f6cb473ce51c0662b184d53ab5bc1_2023.png', '1', '0'),
(5, 22, 800, '2023-03-09', '939f6cb473ce51c0662b184d53ab5bc1_2023.png', '1', '0'),
(6, 22, 400, '2023-06-09', '939f6cb473ce51c0662b184d53ab5bc1_2023.png', '1', '1'),
(7, 23, 10, '2023-03-09', '71a76a773dfe08c05b17fde2986a65cb_2023.png', '1', '1');

-- --------------------------------------------------------

--
-- Structure de la table `carburant_voiture`
--

CREATE TABLE `carburant_voiture` (
  `id_carburantvoiture` int(11) NOT NULL,
  `label_carburant` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `carburant_voiture`
--

INSERT INTO `carburant_voiture` (`id_carburantvoiture`, `label_carburant`) VALUES
(1, 'Essence'),
(2, 'Gasoil');

-- --------------------------------------------------------

--
-- Structure de la table `client`
--

CREATE TABLE `client` (
  `id_client` int(11) NOT NULL,
  `nom_client` varchar(200) NOT NULL,
  `prenom_client` varchar(200) NOT NULL,
  `datenaissance_client` date DEFAULT NULL,
  `lieunaissance_client` varchar(200) NOT NULL,
  `email_client` varchar(200) NOT NULL,
  `tel_client` varchar(200) NOT NULL,
  `adresse_client` varchar(200) NOT NULL,
  `numcin_client` varchar(200) NOT NULL,
  `datecin_client` date DEFAULT NULL,
  `numpassport_client` varchar(200) NOT NULL,
  `datepassport_client` date DEFAULT NULL,
  `numpermis_client` varchar(200) NOT NULL,
  `datepermis_client` date DEFAULT NULL,
  `lieupermis_client` varchar(200) NOT NULL,
  `cin_client` varchar(200) NOT NULL,
  `cin_verso_client` varchar(200) NOT NULL,
  `passport_client` varchar(200) NOT NULL,
  `permis_client` varchar(200) NOT NULL,
  `action_client` enum('0','1') NOT NULL DEFAULT '1',
  `date_created_client` timestamp NOT NULL DEFAULT current_timestamp(),
  `date_updated_client` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `client`
--

INSERT INTO `client` (`id_client`, `nom_client`, `prenom_client`, `datenaissance_client`, `lieunaissance_client`, `email_client`, `tel_client`, `adresse_client`, `numcin_client`, `datecin_client`, `numpassport_client`, `datepassport_client`, `numpermis_client`, `datepermis_client`, `lieupermis_client`, `cin_client`, `cin_verso_client`, `passport_client`, `permis_client`, `action_client`, `date_created_client`, `date_updated_client`) VALUES
(1, 'Maaloul', 'Mohamed Hedi', '1995-06-03', 'sousse', 'maaloulmedhedi@gmail.com', '53116288', '4135 Ajim', '09332541', '2022-02-10', '', '0000-00-00', '12475242', '2023-03-13', 'sousse', 'bae381c94c7bc535de2ff758a21098b4_recto.png', 'bae381c94c7bc535de2ff758a21098b4_verso.png', '', 'bae381c94c7bc535de2ff758a21098b4.png', '1', '2023-03-13 13:23:52', '2023-03-14 09:21:33'),
(2, 'Maaloul ', 'Mohamed Hedi', '2023-03-13', 'bgh', 'maaloulmhghedhedi@gmail.com', '53116288', '4135 Ajim', '', '0000-00-00', 'hngfhn', '2023-03-13', '252543', '2023-03-13', 'hfgh', '', '', '5fe69dae6ab3c7de3d603849cbb0973d.png', '5fe69dae6ab3c7de3d603849cbb0973d.png', '1', '2023-03-13 15:11:16', '2023-03-14 09:21:48'),
(3, 'Maaloul ', 'Mohamed Hedi', '2023-03-13', 'trfhth', 'maaloulmedgggrhedi@gmail.com', '53116288', '4135 Ajim', '12458725', '2023-03-13', '', '0000-00-00', '23567', '2023-03-13', 'enfidha', 'fc6b1538cd6c6118d75382f22ca48875_recto.png', 'fc6b1538cd6c6118d75382f22ca48875_verso.png', '', 'fc6b1538cd6c6118d75382f22ca48875.png', '1', '2023-03-13 15:28:13', '2023-03-14 09:22:09'),
(4, 'Maaloul', 'Mohamed Hedi', '2023-03-13', 'hjyhkh', 'maaloulmeddssshedi@gmail.com', '+21653116288', '4135 Ajim', 'grfg', '2023-03-13', '', '0000-00-00', '524635', '2023-03-13', 'sousse', '28abe5c54884c9906ab52b3612bea9da_recto.png', '28abe5c54884c9906ab52b3612bea9da_verso.png', '', '28abe5c54884c9906ab52b3612bea9da.png', '1', '2023-03-13 15:49:31', '2023-03-14 09:19:07');

-- --------------------------------------------------------

--
-- Structure de la table `contrat`
--

CREATE TABLE `contrat` (
  `id_contrat` int(11) NOT NULL,
  `id_client` int(11) NOT NULL,
  `id_agence` int(11) NOT NULL,
  `id_voiture` int(11) NOT NULL,
  `datedebut_contrat` date NOT NULL,
  `datefin_contrat` date NOT NULL,
  `prix_contrat` double NOT NULL,
  `view_fin` enum('0','1') NOT NULL DEFAULT '1',
  `view_create` enum('0','1') NOT NULL DEFAULT '1',
  `action_contrat` enum('0','1') NOT NULL DEFAULT '1',
  `date_created_contrat` timestamp NOT NULL DEFAULT current_timestamp(),
  `date_updated_contrat` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `entretien_voiture`
--

CREATE TABLE `entretien_voiture` (
  `id_entretien` int(11) NOT NULL,
  `id_voiture` int(11) NOT NULL,
  `datedebut_entretien` date DEFAULT NULL,
  `datefin_entretien` date DEFAULT NULL,
  `blockage_voiture` enum('0','1') NOT NULL,
  `commentaire` text NOT NULL,
  `prix_entretien` double NOT NULL,
  `action_entretien` enum('0','1') NOT NULL DEFAULT '1',
  `date_created_entretien` timestamp NOT NULL DEFAULT current_timestamp(),
  `date_updated_entretien` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `entretien_voiture`
--

INSERT INTO `entretien_voiture` (`id_entretien`, `id_voiture`, `datedebut_entretien`, `datefin_entretien`, `blockage_voiture`, `commentaire`, `prix_entretien`, `action_entretien`, `date_created_entretien`, `date_updated_entretien`) VALUES
(1, 6, '2023-03-01', '2023-04-03', '1', '  test', 2000, '1', '2023-03-02 10:27:02', '2023-03-06 08:44:51'),
(2, 6, '2023-03-01', '2023-03-04', '1', 'test', 1200, '0', '2023-03-02 13:28:49', '2023-03-02 13:44:43'),
(3, 5, '2023-03-01', '2023-03-03', '0', 'tesst', 15200, '1', '2023-03-02 13:46:08', '2023-03-02 13:46:08');

-- --------------------------------------------------------

--
-- Structure de la table `horaire_agence`
--

CREATE TABLE `horaire_agence` (
  `id_horaire` int(11) NOT NULL,
  `id_agence` int(11) NOT NULL,
  `jour_horaire` enum('lundi','mardi','mercredi','jeudi','vendredi','samedi','dimanche') NOT NULL,
  `debut_horaire` time NOT NULL,
  `fin_horaire` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `horaire_agence`
--

INSERT INTO `horaire_agence` (`id_horaire`, `id_agence`, `jour_horaire`, `debut_horaire`, `fin_horaire`) VALUES
(1, 1, 'lundi', '17:14:00', '22:15:00');

-- --------------------------------------------------------

--
-- Structure de la table `marque_voiture`
--

CREATE TABLE `marque_voiture` (
  `id_marquevoiture` int(11) NOT NULL,
  `marque` varchar(200) NOT NULL,
  `model` varchar(200) NOT NULL,
  `action_marquevoiture` enum('0','1') NOT NULL DEFAULT '1',
  `date_created_marque` timestamp NOT NULL DEFAULT current_timestamp(),
  `date_updated_marque` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `marque_voiture`
--

INSERT INTO `marque_voiture` (`id_marquevoiture`, `marque`, `model`, `action_marquevoiture`, `date_created_marque`, `date_updated_marque`) VALUES
(1, 'Hyundai', 'Grand i10', '1', '2023-03-01 09:30:34', '2023-03-06 08:18:05'),
(2, 'test', 'res', '0', '2023-03-08 14:21:42', '2023-03-08 16:05:38');

-- --------------------------------------------------------

--
-- Structure de la table `prix_marque_voiture`
--

CREATE TABLE `prix_marque_voiture` (
  `id_prixmarquevoiture` int(11) NOT NULL,
  `id_marque_voiture` int(11) NOT NULL,
  `id_month` int(2) NOT NULL,
  `prix_marquevoiture` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `prix_marque_voiture`
--

INSERT INTO `prix_marque_voiture` (`id_prixmarquevoiture`, `id_marque_voiture`, `id_month`, `prix_marquevoiture`) VALUES
(1, 1, 1, 80),
(2, 1, 2, 90),
(3, 1, 3, 80),
(4, 1, 4, 80),
(5, 1, 5, 90),
(6, 1, 6, 100),
(7, 1, 7, 140),
(8, 1, 8, 140),
(9, 1, 9, 100),
(10, 1, 10, 80),
(11, 1, 11, 80),
(12, 1, 12, 90),
(13, 2, 1, 10),
(14, 2, 2, 10),
(15, 2, 3, 10),
(16, 2, 4, 10),
(17, 2, 5, 10),
(18, 2, 6, 10),
(19, 2, 7, 10),
(20, 2, 8, 10),
(21, 2, 9, 10),
(22, 2, 10, 10),
(23, 2, 11, 10),
(24, 2, 12, 10);

-- --------------------------------------------------------

--
-- Structure de la table `role_user`
--

CREATE TABLE `role_user` (
  `id_roleuser` int(11) NOT NULL,
  `label_roleuser` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `role_user`
--

INSERT INTO `role_user` (`id_roleuser`, `label_roleuser`) VALUES
(0, 'superadmin'),
(1, 'admin'),
(2, 'agent');

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `id_user` int(11) NOT NULL,
  `id_agence` int(11) NOT NULL,
  `nom_user` varchar(200) NOT NULL,
  `login_user` varchar(200) NOT NULL,
  `motdepasse_user` varchar(200) NOT NULL,
  `email_user` varchar(200) NOT NULL,
  `role_user` varchar(200) NOT NULL,
  `etat_user` enum('T','F','S') NOT NULL DEFAULT 'T',
  `date_created_user` timestamp NOT NULL DEFAULT current_timestamp(),
  `date_updated_user` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id_user`, `id_agence`, `nom_user`, `login_user`, `motdepasse_user`, `email_user`, `role_user`, `etat_user`, `date_created_user`, `date_updated_user`) VALUES
(1, 0, 'superadmin', 'superadmin', '17c4520f6cfd1ab53d8745e84681eb49', 'k2rent-app@k2rent.tn', '0', 'T', '2023-01-24 14:19:13', '2023-01-24 14:22:17'),
(2, 0, 'Mohamed Maaloul', 'mohamed', '4a7d1ed414474e4033ac29ccb8653d9b', '', '1', 'F', '2023-01-24 14:19:13', '2023-02-17 16:18:59'),
(3, 2, 'Mouayed Mander', 'mouayed', '4a7d1ed414474e4033ac29ccb8653d9b', '', '2', 'T', '2023-01-24 14:19:13', '2023-02-06 10:05:50'),
(4, 0, 'hedi fourati', 'hedi', '4a7d1ed414474e4033ac29ccb8653d9b', 'hedifourati@ste-sitem.com', '1', 'T', '2023-02-08 15:17:48', '2023-02-08 15:17:48'),
(5, 1, 'Malek Moslah', 'malek', '4a7d1ed414474e4033ac29ccb8653d9b', '', '2', 'T', '2023-02-15 10:41:55', '2023-02-15 10:41:55');

-- --------------------------------------------------------

--
-- Structure de la table `valise_voiture`
--

CREATE TABLE `valise_voiture` (
  `id_valisevoiture` int(11) NOT NULL,
  `label_valise` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `valise_voiture`
--

INSERT INTO `valise_voiture` (`id_valisevoiture`, `label_valise`) VALUES
(1, '1G + 1P'),
(2, '1G + 2P'),
(3, '2G + 1P'),
(4, '2G + 2P'),
(5, '3G + 1P'),
(6, '3G + 2P'),
(7, '3G + 3P');

-- --------------------------------------------------------

--
-- Structure de la table `vignette_voiture`
--

CREATE TABLE `vignette_voiture` (
  `id_vignettevoiture` int(11) NOT NULL,
  `id_voiture` int(11) NOT NULL,
  `prix_vignette` double NOT NULL,
  `file_vignette` varchar(200) NOT NULL,
  `etat_vignette` enum('0','1') NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `vignette_voiture`
--

INSERT INTO `vignette_voiture` (`id_vignettevoiture`, `id_voiture`, `prix_vignette`, `file_vignette`, `etat_vignette`) VALUES
(1, 6, 130, 'a474312fbdd6dc59f008c232d33068e9_2023.png', '1'),
(2, 23, 0, '71a76a773dfe08c05b17fde2986a65cb_2023.png', '1');

-- --------------------------------------------------------

--
-- Structure de la table `visite_voiture`
--

CREATE TABLE `visite_voiture` (
  `id_visitevoiture` int(11) NOT NULL,
  `id_voiture` int(11) NOT NULL,
  `prix_visite` double NOT NULL,
  `date_fin_visite` date NOT NULL,
  `file_visite` varchar(200) NOT NULL,
  `view_notif` enum('0','1') NOT NULL DEFAULT '1',
  `etat_visite` enum('0','1') NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `visite_voiture`
--

INSERT INTO `visite_voiture` (`id_visitevoiture`, `id_voiture`, `prix_visite`, `date_fin_visite`, `file_visite`, `view_notif`, `etat_visite`) VALUES
(1, 6, 35, '2023-03-04', '82741640e163d5092c5e1bfb8a025757_2023.png', '1', '1'),
(2, 22, 40, '2023-03-12', '939f6cb473ce51c0662b184d53ab5bc1_2023.png', '1', '0'),
(3, 22, 13, '2023-03-10', '939f6cb473ce51c0662b184d53ab5bc1_2023.png', '1', '0'),
(4, 22, 20, '2023-06-09', '939f6cb473ce51c0662b184d53ab5bc1_2023.png', '1', '1'),
(5, 23, 20, '2023-03-23', '71a76a773dfe08c05b17fde2986a65cb_2023.png', '1', '1');

-- --------------------------------------------------------

--
-- Structure de la table `voiture`
--

CREATE TABLE `voiture` (
  `id_voiture` int(11) NOT NULL,
  `pimm_voiture` varchar(200) NOT NULL,
  `id_marquemodel` int(11) NOT NULL,
  `id_agence` int(11) NOT NULL,
  `id_typecarburant` int(11) NOT NULL,
  `boitevitesse_voiture` varchar(200) NOT NULL,
  `nbreplace_voiture` int(11) NOT NULL,
  `valise_voiture` int(11) NOT NULL,
  `puissance_voiture` int(11) NOT NULL,
  `climatisation_voiture` enum('0','1') NOT NULL,
  `cartegrise_voiture` varchar(200) NOT NULL,
  `assurance_voiture` varchar(200) NOT NULL,
  `action_voiture` enum('0','1') NOT NULL DEFAULT '1',
  `date_created_voiture` timestamp NOT NULL DEFAULT current_timestamp(),
  `date_updated_voiture` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `voiture`
--

INSERT INTO `voiture` (`id_voiture`, `pimm_voiture`, `id_marquemodel`, `id_agence`, `id_typecarburant`, `boitevitesse_voiture`, `nbreplace_voiture`, `valise_voiture`, `puissance_voiture`, `climatisation_voiture`, `cartegrise_voiture`, `assurance_voiture`, `action_voiture`, `date_created_voiture`, `date_updated_voiture`) VALUES
(1, '235 TU 3983', 1, 1, 1, 'Manuelle', 5, 1, 5, '1', 'ded7c475835b34538bbfeaac79242ee1.png', 'ded7c475835b34538bbfeaac79242ee1.png', '1', '2023-03-01 14:01:41', '2023-03-08 14:22:08'),
(2, '235 TU 4984', 1, 1, 1, 'Manuelle', 5, 1, 5, '1', 'c73f14b8ad6abcac4db62d56f00b33f3.png', 'c73f14b8ad6abcac4db62d56f00b33f3.png', '1', '2023-03-02 09:50:10', '2023-03-08 14:28:48'),
(3, '235 TU 3985', 1, 1, 1, 'Manuelle', 5, 1, 5, '1', '8f7c4a0d7806a58ea943421cc056fcbd.png', '8f7c4a0d7806a58ea943421cc056fcbd.png', '1', '2023-03-06 08:19:06', '2023-03-06 08:19:06'),
(4, '235 TU 3986', 1, 1, 1, 'Manuelle', 5, 1, 5, '1', '417a3157418ebff3d79ad53558af4e73.png', '417a3157418ebff3d79ad53558af4e73.png', '1', '2023-03-06 08:19:53', '2023-03-06 08:19:53'),
(5, '235 TU 3987', 1, 1, 1, 'Manuelle', 5, 1, 5, '1', 'f3986af1eb2c8be660db6b94b75aeb4d.png', 'f3986af1eb2c8be660db6b94b75aeb4d.png', '1', '2023-03-06 08:20:29', '2023-03-06 08:20:29'),
(6, '235 TU 3988', 1, 1, 1, 'Manuelle', 5, 1, 5, '1', '82741640e163d5092c5e1bfb8a025757.png', '82741640e163d5092c5e1bfb8a025757.png', '1', '2023-03-06 08:20:57', '2023-03-06 08:20:57'),
(7, '235 TU 3989', 1, 1, 1, 'Manuelle', 5, 1, 5, '1', '9a92ee6acbfa685258cd31a1863f6475.png', '9a92ee6acbfa685258cd31a1863f6475.png', '1', '2023-03-06 08:21:30', '2023-03-06 08:21:30'),
(8, '235 TU 3990', 1, 1, 1, 'Manuelle', 5, 1, 5, '1', '0d6aa036e3aa0f34d789d86ce888b51f.png', '0d6aa036e3aa0f34d789d86ce888b51f.png', '1', '2023-03-06 08:22:04', '2023-03-06 08:22:04'),
(9, '235 TU 3991', 1, 1, 1, 'Manuelle', 5, 1, 5, '1', 'ea2ef1331e509e67138a23a5fdd21561.png', 'ea2ef1331e509e67138a23a5fdd21561.png', '1', '2023-03-06 08:22:34', '2023-03-06 08:22:34'),
(10, '235 TU 3992', 1, 1, 1, 'Manuelle', 5, 1, 5, '1', 'b4b1d2ee79c8176b6802b8f4e81436fc.png', 'b4b1d2ee79c8176b6802b8f4e81436fc.png', '1', '2023-03-06 08:23:03', '2023-03-06 08:23:03'),
(11, '235 TU 3993', 1, 1, 1, 'Manuelle', 5, 1, 5, '1', '3208e369d7c2ab3daf616e2e24c624e9.png', '3208e369d7c2ab3daf616e2e24c624e9.png', '1', '2023-03-06 08:23:34', '2023-03-06 08:23:34'),
(12, '235 TU 3994', 1, 1, 1, 'Manuelle', 5, 1, 5, '1', '4546ab193c2d6d836747f06606fc928f.png', '4546ab193c2d6d836747f06606fc928f.png', '1', '2023-03-06 08:24:04', '2023-03-06 08:24:04'),
(13, '235 TU 3995', 1, 1, 1, 'Manuelle', 5, 1, 5, '1', 'fddd3012f3f51a89e26f46b6c7c8985d.png', 'fddd3012f3f51a89e26f46b6c7c8985d.png', '1', '2023-03-06 08:24:31', '2023-03-06 08:24:31'),
(14, '235 TU 3996', 1, 1, 1, 'Manuelle', 5, 1, 5, '1', 'bc8d108fbf5d504e53d760313b6eb1f7.png', 'bc8d108fbf5d504e53d760313b6eb1f7.png', '1', '2023-03-06 08:25:05', '2023-03-06 08:25:05'),
(15, '235 TU 3997', 1, 1, 1, 'Manuelle', 5, 1, 5, '1', 'a79c605f8c9d3d78809d4d203ad8c52c.png', 'a79c605f8c9d3d78809d4d203ad8c52c.png', '1', '2023-03-06 08:25:41', '2023-03-06 08:25:41'),
(16, '235 TU 3998', 1, 1, 1, 'Manuelle', 5, 1, 5, '1', '66bd30b9304bb8e28be16890a10ed1cb.png', '66bd30b9304bb8e28be16890a10ed1cb.png', '1', '2023-03-06 08:26:18', '2023-03-06 08:26:18'),
(17, '235 TU 3999', 1, 1, 1, 'Manuelle', 5, 1, 5, '1', '1669215fd621f48a6ede4a038b98b7ae.png', '1669215fd621f48a6ede4a038b98b7ae.png', '1', '2023-03-06 08:26:58', '2023-03-06 08:26:58'),
(18, '235 TU 4000', 1, 1, 1, 'Manuelle', 5, 1, 5, '1', 'b9a7d802a0884b31d6bf00354158ae7c.png', 'b9a7d802a0884b31d6bf00354158ae7c.png', '1', '2023-03-06 08:27:28', '2023-03-06 08:27:28'),
(19, '235 TU 4001', 1, 1, 1, 'Manuelle', 5, 1, 5, '1', '0054a5bf7881d97b0b7d1ab78e61c0b1.png', '0054a5bf7881d97b0b7d1ab78e61c0b1.png', '1', '2023-03-06 08:28:01', '2023-03-06 08:28:01'),
(20, '235 TU 4002', 1, 1, 1, 'Manuelle', 5, 1, 5, '1', '7fbae97d476d6c1f2bf3fe0edbac8021.png', '7fbae97d476d6c1f2bf3fe0edbac8021.png', '1', '2023-03-06 08:41:39', '2023-03-06 08:41:39'),
(21, '235 TU 4003', 1, 1, 1, 'Manuelle', 5, 1, 5, '1', 'fdcb3f6e2d4afbfdb60fdcb6a581391d.png', 'fdcb3f6e2d4afbfdb60fdcb6a581391d.png', '0', '2023-03-06 08:43:07', '2023-03-06 08:43:07'),
(22, '125 TU 2544', 1, 1, 1, 'Manuelle', 5, 1, 4, '1', '939f6cb473ce51c0662b184d53ab5bc1.png', '', '1', '2023-03-07 14:48:45', '2023-03-09 16:07:58'),
(23, '422 TU 2424', 1, 1, 1, 'Manuelle', 2, 1, 1, '1', '71a76a773dfe08c05b17fde2986a65cb.png', '', '1', '2023-03-09 16:10:23', '2023-03-09 16:10:23');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `agence`
--
ALTER TABLE `agence`
  ADD PRIMARY KEY (`id_agence`);

--
-- Index pour la table `assurance_voiture`
--
ALTER TABLE `assurance_voiture`
  ADD PRIMARY KEY (`id_assurancevoiture`);

--
-- Index pour la table `carburant_voiture`
--
ALTER TABLE `carburant_voiture`
  ADD PRIMARY KEY (`id_carburantvoiture`);

--
-- Index pour la table `client`
--
ALTER TABLE `client`
  ADD PRIMARY KEY (`id_client`);

--
-- Index pour la table `contrat`
--
ALTER TABLE `contrat`
  ADD PRIMARY KEY (`id_contrat`);

--
-- Index pour la table `entretien_voiture`
--
ALTER TABLE `entretien_voiture`
  ADD PRIMARY KEY (`id_entretien`);

--
-- Index pour la table `horaire_agence`
--
ALTER TABLE `horaire_agence`
  ADD PRIMARY KEY (`id_horaire`);

--
-- Index pour la table `marque_voiture`
--
ALTER TABLE `marque_voiture`
  ADD PRIMARY KEY (`id_marquevoiture`);

--
-- Index pour la table `prix_marque_voiture`
--
ALTER TABLE `prix_marque_voiture`
  ADD PRIMARY KEY (`id_prixmarquevoiture`);

--
-- Index pour la table `role_user`
--
ALTER TABLE `role_user`
  ADD PRIMARY KEY (`id_roleuser`);

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`);

--
-- Index pour la table `valise_voiture`
--
ALTER TABLE `valise_voiture`
  ADD PRIMARY KEY (`id_valisevoiture`);

--
-- Index pour la table `vignette_voiture`
--
ALTER TABLE `vignette_voiture`
  ADD PRIMARY KEY (`id_vignettevoiture`);

--
-- Index pour la table `visite_voiture`
--
ALTER TABLE `visite_voiture`
  ADD PRIMARY KEY (`id_visitevoiture`);

--
-- Index pour la table `voiture`
--
ALTER TABLE `voiture`
  ADD PRIMARY KEY (`id_voiture`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `agence`
--
ALTER TABLE `agence`
  MODIFY `id_agence` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `assurance_voiture`
--
ALTER TABLE `assurance_voiture`
  MODIFY `id_assurancevoiture` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `carburant_voiture`
--
ALTER TABLE `carburant_voiture`
  MODIFY `id_carburantvoiture` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `client`
--
ALTER TABLE `client`
  MODIFY `id_client` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `contrat`
--
ALTER TABLE `contrat`
  MODIFY `id_contrat` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `entretien_voiture`
--
ALTER TABLE `entretien_voiture`
  MODIFY `id_entretien` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `horaire_agence`
--
ALTER TABLE `horaire_agence`
  MODIFY `id_horaire` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `marque_voiture`
--
ALTER TABLE `marque_voiture`
  MODIFY `id_marquevoiture` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `prix_marque_voiture`
--
ALTER TABLE `prix_marque_voiture`
  MODIFY `id_prixmarquevoiture` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT pour la table `role_user`
--
ALTER TABLE `role_user`
  MODIFY `id_roleuser` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `valise_voiture`
--
ALTER TABLE `valise_voiture`
  MODIFY `id_valisevoiture` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `vignette_voiture`
--
ALTER TABLE `vignette_voiture`
  MODIFY `id_vignettevoiture` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `visite_voiture`
--
ALTER TABLE `visite_voiture`
  MODIFY `id_visitevoiture` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `voiture`
--
ALTER TABLE `voiture`
  MODIFY `id_voiture` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
