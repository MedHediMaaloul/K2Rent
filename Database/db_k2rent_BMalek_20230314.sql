-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : mar. 14 mars 2023 à 10:43
-- Version du serveur : 10.4.27-MariaDB
-- Version de PHP : 7.4.3

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Déchargement des données de la table `agence`
--

INSERT INTO `agence` (`id_agence`, `nom_agence`, `email_agence`, `tel_agence`, `action_agence`, `date_created_agence`, `date_updated_agence`) VALUES
(0, '', '', '', '1', '2023-03-13 10:17:33', '2023-03-13 10:17:33'),
(5, 'Djerba', 'malekmoslah@gmail.com', '92420452', '1', '2023-03-14 08:43:23', '2023-03-14 08:43:23');

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
  `etat_assurance` enum('0','1') NOT NULL DEFAULT '1',
  `date_created_assurance` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `assurance_voiture`
--

INSERT INTO `assurance_voiture` (`id_assurancevoiture`, `id_voiture`, `prix_assurance`, `date_fin_assurance`, `file_assurance`, `view_notif`, `etat_assurance`, `date_created_assurance`) VALUES
(42, 29, 5, '2023-03-18', '1597d34fa0b78c0f9cf04da39921e276_2023.jpeg', '1', '0', '2022-03-14 09:17:53'),
(43, 29, 800, '2023-03-31', '1597d34fa0b78c0f9cf04da39921e276_2023.jpeg', '1', '1', '2023-03-14 09:22:38');

-- --------------------------------------------------------

--
-- Structure de la table `carburant_voiture`
--

CREATE TABLE `carburant_voiture` (
  `id_carburantvoiture` int(11) NOT NULL,
  `label_carburant` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

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
  `email_client` varchar(200) NOT NULL,
  `tel_client` varchar(200) NOT NULL,
  `adresse_client` varchar(200) NOT NULL,
  `cin_client` varchar(200) NOT NULL,
  `permis_client` varchar(200) NOT NULL,
  `action_client` enum('0','1') NOT NULL DEFAULT '1',
  `date_created_client` timestamp NOT NULL DEFAULT current_timestamp(),
  `date_updated_client` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Déchargement des données de la table `client`
--

INSERT INTO `client` (`id_client`, `nom_client`, `email_client`, `tel_client`, `adresse_client`, `cin_client`, `permis_client`, `action_client`, `date_created_client`, `date_updated_client`) VALUES
(1, 'mohamed', 'mohamedmaaloul@gmail.com', '53116288', 'jerba ajim', '395066255cc9572555a185921b249987.png', '395066255cc9572555a185921b249987.png', '1', '2023-01-25 09:39:47', '2023-01-27 09:40:09'),
(2, 'Mouayed', 'mouayedmander@gmail.com', '27451658', '4135 Ajim', '1b70234a2b8d17111820a6f43882b750.png', '1b70234a2b8d17111820a6f43882b750.png', '1', '2023-01-25 10:05:53', '2023-01-25 14:01:43'),
(3, 'Malek Moslah', 'malekmoslah@gmail.com', '15478542', '4135 Ajim', 'bae381c94c7bc535de2ff758a21098b4.png', 'bae381c94c7bc535de2ff758a21098b4.png', '1', '2023-02-10 08:19:32', '2023-03-01 08:51:05'),
(4, 'Mohamed Hedi Maaloul', 'maaloulmedhddedi@gmail.com', '+21653116288', '4135 Ajim', '545008a598b293caf3d553de7692c127.png', '545008a598b293caf3d553de7692c127.png', '0', '2023-02-22 15:31:59', '2023-02-22 15:32:29');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Déchargement des données de la table `contrat`
--

INSERT INTO `contrat` (`id_contrat`, `id_client`, `id_agence`, `id_voiture`, `datedebut_contrat`, `datefin_contrat`, `prix_contrat`, `view_fin`, `view_create`, `action_contrat`, `date_created_contrat`, `date_updated_contrat`) VALUES
(1, 1, 1, 18, '2023-03-13', '2023-03-17', 404, '0', '0', '1', '2023-03-13 09:11:07', '2023-03-13 09:11:07');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Déchargement des données de la table `horaire_agence`
--

INSERT INTO `horaire_agence` (`id_horaire`, `id_agence`, `jour_horaire`, `debut_horaire`, `fin_horaire`) VALUES
(1, 1, 'lundi', '17:14:00', '22:15:00'),
(2, 3, 'lundi', '10:00:00', '15:10:00'),
(3, 5, 'lundi', '10:00:00', '15:00:00');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Déchargement des données de la table `marque_voiture`
--

INSERT INTO `marque_voiture` (`id_marquevoiture`, `marque`, `model`, `action_marquevoiture`, `date_created_marque`, `date_updated_marque`) VALUES
(1, 'peugeot', '306', '1', '2023-03-01 10:04:15', '2023-03-08 08:38:55'),
(2, 'renault', 'clio 4', '1', '2023-03-08 08:39:16', '2023-03-08 08:39:16');

-- --------------------------------------------------------

--
-- Structure de la table `prix_marque_voiture`
--

CREATE TABLE `prix_marque_voiture` (
  `id_prixmarquevoiture` int(11) NOT NULL,
  `id_marque_voiture` int(11) NOT NULL,
  `id_month` int(2) NOT NULL,
  `prix_marquevoiture` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Déchargement des données de la table `prix_marque_voiture`
--

INSERT INTO `prix_marque_voiture` (`id_prixmarquevoiture`, `id_marque_voiture`, `id_month`, `prix_marquevoiture`) VALUES
(1, 1, 1, 10),
(2, 1, 2, 10),
(3, 1, 3, 101),
(4, 1, 4, 10),
(5, 1, 5, 101),
(6, 1, 6, 10),
(7, 1, 7, 101),
(8, 1, 8, 10),
(9, 1, 9, 1010),
(10, 1, 10, 101),
(11, 1, 11, 1010),
(12, 1, 12, 101),
(13, 2, 1, 1000),
(14, 2, 2, 1010),
(15, 2, 3, 1000),
(16, 2, 4, 101),
(17, 2, 5, 1010),
(18, 2, 6, 1010),
(19, 2, 7, 10),
(20, 2, 8, 1010),
(21, 2, 9, 101),
(22, 2, 10, 1010),
(23, 2, 11, 1010),
(24, 2, 12, 1010);

-- --------------------------------------------------------

--
-- Structure de la table `role_user`
--

CREATE TABLE `role_user` (
  `id_roleuser` int(11) NOT NULL,
  `label_roleuser` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id_user`, `id_agence`, `nom_user`, `login_user`, `motdepasse_user`, `email_user`, `role_user`, `etat_user`, `date_created_user`, `date_updated_user`) VALUES
(1, 0, 'superadmin', 'superadmin', '17c4520f6cfd1ab53d8745e84681eb49', 'k2rent-app@k2rent.tn', '0', 'T', '2023-01-24 14:19:13', '2023-01-24 14:22:17'),
(2, 0, 'Mohamed Maaloul', 'mohamed', '4a7d1ed414474e4033ac29ccb8653d9b', '', '1', 'T', '2023-01-24 14:19:13', '2023-03-08 10:50:03'),
(3, 2, 'Mouayed Mander', 'mouayed', '4a7d1ed414474e4033ac29ccb8653d9b', '', '2', 'T', '2023-01-24 14:19:13', '2023-03-07 16:23:41'),
(4, 0, 'hedi fourati', 'hedi', '4a7d1ed414474e4033ac29ccb8653d9b', 'hedifourati@ste-sitem.com', '1', 'T', '2023-02-08 15:17:48', '2023-02-08 15:17:48'),
(5, 1, 'Malek Moslah', 'malek', '4a7d1ed414474e4033ac29ccb8653d9b', '', '2', 'T', '2023-02-15 10:41:55', '2023-03-13 13:11:02');

-- --------------------------------------------------------

--
-- Structure de la table `valise_voiture`
--

CREATE TABLE `valise_voiture` (
  `id_valisevoiture` int(11) NOT NULL,
  `label_valise` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

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
  `etat_vignette` enum('0','1') NOT NULL DEFAULT '1',
  `date_created_voiture` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `vignette_voiture`
--

INSERT INTO `vignette_voiture` (`id_vignettevoiture`, `id_voiture`, `prix_vignette`, `file_vignette`, `etat_vignette`, `date_created_voiture`) VALUES
(28, 29, 0, '1597d34fa0b78c0f9cf04da39921e276_2023.jpeg', '1', '2023-03-14 09:18:36');

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
  `etat_visite` enum('0','1') NOT NULL DEFAULT '1',
  `date_created_visite` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `visite_voiture`
--

INSERT INTO `visite_voiture` (`id_visitevoiture`, `id_voiture`, `prix_visite`, `date_fin_visite`, `file_visite`, `view_notif`, `etat_visite`, `date_created_visite`) VALUES
(51, 29, 5, '2023-03-17', '1597d34fa0b78c0f9cf04da39921e276_2023.png', '1', '1', '2023-03-14 09:18:23');

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
  `action_voiture` enum('0','1') NOT NULL DEFAULT '1',
  `date_created_voiture` timestamp NOT NULL DEFAULT current_timestamp(),
  `date_updated_voiture` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Déchargement des données de la table `voiture`
--

INSERT INTO `voiture` (`id_voiture`, `pimm_voiture`, `id_marquemodel`, `id_agence`, `id_typecarburant`, `boitevitesse_voiture`, `nbreplace_voiture`, `valise_voiture`, `puissance_voiture`, `climatisation_voiture`, `cartegrise_voiture`, `action_voiture`, `date_created_voiture`, `date_updated_voiture`) VALUES
(29, '300 TU 300', 1, 5, 2, 'Manuelle', 5, 2, 5, '1', '1597d34fa0b78c0f9cf04da39921e276.png', '1', '2023-03-14 08:56:10', '2023-03-14 08:56:10');

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
  MODIFY `id_agence` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `assurance_voiture`
--
ALTER TABLE `assurance_voiture`
  MODIFY `id_assurancevoiture` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

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
  MODIFY `id_contrat` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `entretien_voiture`
--
ALTER TABLE `entretien_voiture`
  MODIFY `id_entretien` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `horaire_agence`
--
ALTER TABLE `horaire_agence`
  MODIFY `id_horaire` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

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
  MODIFY `id_valisevoiture` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT pour la table `vignette_voiture`
--
ALTER TABLE `vignette_voiture`
  MODIFY `id_vignettevoiture` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT pour la table `visite_voiture`
--
ALTER TABLE `visite_voiture`
  MODIFY `id_visitevoiture` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT pour la table `voiture`
--
ALTER TABLE `voiture`
  MODIFY `id_voiture` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
