-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : lun. 06 fév. 2023 à 11:26
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
(1, 'Djerba', 'k2djerba@k2rent.tn', '75660251', '1', '2023-01-24 10:50:19', '2023-01-27 13:41:05'),
(2, 'Tunis', 'k2tunis@k2rent.tn', '73381456', '1', '2023-01-24 16:47:54', '2023-01-24 16:47:54');

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
(2, 'Gasoil 50');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `client`
--

INSERT INTO `client` (`id_client`, `nom_client`, `email_client`, `tel_client`, `adresse_client`, `cin_client`, `permis_client`, `action_client`, `date_created_client`, `date_updated_client`) VALUES
(1, 'mohamed', 'mohamedmaaloul@gmail.com', '53116288', 'jerba ajim', '395066255cc9572555a185921b249987.png', '395066255cc9572555a185921b249987.png', '1', '2023-01-25 09:39:47', '2023-01-27 09:40:09'),
(2, 'Mouayed', 'mouayedmander@gmail.com', '+21653116288', '4135 Ajim', '1b70234a2b8d17111820a6f43882b750.png', '1b70234a2b8d17111820a6f43882b750.png', '1', '2023-01-25 10:05:53', '2023-01-25 14:01:43');

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
  `action_contrat` enum('0','1') NOT NULL DEFAULT '1',
  `date_created_contrat` timestamp NOT NULL DEFAULT current_timestamp(),
  `date_updated_contrat` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `contrat`
--

INSERT INTO `contrat` (`id_contrat`, `id_client`, `id_agence`, `id_voiture`, `datedebut_contrat`, `datefin_contrat`, `prix_contrat`, `action_contrat`, `date_created_contrat`, `date_updated_contrat`) VALUES
(1, 1, 1, 1, '2023-02-03', '2023-02-05', 490000, '1', '2023-02-03 09:31:37', '2023-02-03 14:44:02');

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
(1, 1, 'lundi', '10:00:00', '17:00:00'),
(2, 1, 'mardi', '10:56:00', '10:59:00'),
(3, 2, 'lundi', '10:00:00', '17:00:00'),
(4, 2, 'mardi', '10:00:00', '17:00:00');

-- --------------------------------------------------------

--
-- Structure de la table `marque_voiture`
--

CREATE TABLE `marque_voiture` (
  `id_marquevoiture` int(11) NOT NULL,
  `marque` varchar(200) NOT NULL,
  `model` varchar(200) NOT NULL,
  `prix_marquevoiture` double NOT NULL,
  `action_marquevoiture` enum('0','1') NOT NULL DEFAULT '1',
  `date_created_marque` timestamp NOT NULL DEFAULT current_timestamp(),
  `date_updated_marque` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `marque_voiture`
--

INSERT INTO `marque_voiture` (`id_marquevoiture`, `marque`, `model`, `prix_marquevoiture`, `action_marquevoiture`, `date_created_marque`, `date_updated_marque`) VALUES
(1, 'Renault', 'Clio 4', 60000, '1', '2023-01-27 08:57:51', '2023-02-02 13:38:54'),
(2, 'Skoda', 'Fabia', 50000, '1', '2023-01-27 08:57:51', '2023-02-02 13:39:11'),
(3, 'Chevrolet', 'Sonic', 50000, '1', '2023-01-27 09:12:01', '2023-02-02 13:39:19'),
(4, 'Fiat', 'Punto', 40000, '1', '2023-01-27 09:12:23', '2023-02-02 13:39:25'),
(5, 'Peugeot', '301', 60000, '1', '2023-01-27 09:12:59', '2023-02-02 13:39:30'),
(6, 'Hyundai', 'i20', 70000, '1', '2023-01-27 09:13:48', '2023-02-02 13:39:38'),
(7, 'Renault', 'Clio 5', 70000, '1', '2023-01-27 09:14:05', '2023-02-02 13:39:45'),
(8, 'Seat', 'Ibiza', 90000, '1', '2023-01-27 09:14:26', '2023-02-02 13:39:52'),
(9, 'Suzuki', 'Swift', 60000, '1', '2023-01-27 09:14:52', '2023-02-02 13:39:59'),
(10, 'Volkswagen', 'Polo Sedan', 80000, '1', '2023-01-27 09:15:32', '2023-02-02 13:40:06'),
(11, 'Citroën', 'C3', 60000, '1', '2023-01-27 09:16:38', '2023-02-02 14:00:46'),
(12, 'Hyundai', 'i10', 70000, '1', '2023-01-27 09:17:03', '2023-02-02 13:40:19'),
(13, 'Volkswagen', 'Polo 7', 80000, '1', '2023-01-27 09:17:37', '2023-02-02 13:40:27'),
(14, 'Volkswagen', 'Polo 8', 110000, '1', '2023-01-27 09:17:43', '2023-02-02 13:40:34'),
(15, 'Hyundai', 'i10 G', 70000, '1', '2023-02-02 13:33:36', '2023-02-02 13:33:36');

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
(2, 0, 'Mohamed Maaloul', 'mohamed', '4a7d1ed414474e4033ac29ccb8653d9b', '', '1', 'T', '2023-01-24 14:19:13', '2023-02-06 10:05:28'),
(3, 1, 'Mouayed Mander', 'mouayed', '4a7d1ed414474e4033ac29ccb8653d9b', '', '2', 'T', '2023-01-24 14:19:13', '2023-02-06 10:05:50'),
(4, 0, 'test', 'test', '4a7d1ed414474e4033ac29ccb8653d9b', '', '1', 'T', '2023-02-06 10:08:45', '2023-02-06 10:08:45');

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
(1, '235 TU 1450', 12, 2, 1, 'Manuelle', 5, 1, 4, '1', '3f6772654b4e43591800b656fcedeb76.png', '3f6772654b4e43591800b656fcedeb76.png', '1', '2023-02-03 09:21:27', '2023-02-06 09:19:38');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `agence`
--
ALTER TABLE `agence`
  ADD PRIMARY KEY (`id_agence`);

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
  MODIFY `id_agence` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `carburant_voiture`
--
ALTER TABLE `carburant_voiture`
  MODIFY `id_carburantvoiture` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `client`
--
ALTER TABLE `client`
  MODIFY `id_client` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `contrat`
--
ALTER TABLE `contrat`
  MODIFY `id_contrat` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `horaire_agence`
--
ALTER TABLE `horaire_agence`
  MODIFY `id_horaire` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT pour la table `marque_voiture`
--
ALTER TABLE `marque_voiture`
  MODIFY `id_marquevoiture` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT pour la table `role_user`
--
ALTER TABLE `role_user`
  MODIFY `id_roleuser` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `valise_voiture`
--
ALTER TABLE `valise_voiture`
  MODIFY `id_valisevoiture` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `voiture`
--
ALTER TABLE `voiture`
  MODIFY `id_voiture` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
