-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : mer. 01 fév. 2023 à 09:29
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
(1, 2, 1, 1, '2023-01-23', '2023-01-27', 1000, '1', '2023-01-30 09:48:50', '2023-01-30 09:48:50'),
(2, 1, 1, 1, '2023-01-30', '2023-02-01', 1000, '1', '2023-01-30 10:06:20', '2023-01-30 10:06:20'),
(3, 2, 2, 1, '2023-02-03', '2023-02-05', 100, '1', '2023-01-30 10:21:48', '2023-01-30 10:21:48'),
(4, 1, 1, 6, '2023-01-30', '2023-02-04', 100, '0', '2023-01-30 10:25:03', '2023-02-01 08:25:51');

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
  `action_marquevoiture` enum('0','1') NOT NULL DEFAULT '1',
  `date_created_marque` timestamp NOT NULL DEFAULT current_timestamp(),
  `date_updated_marque` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `marque_voiture`
--

INSERT INTO `marque_voiture` (`id_marquevoiture`, `marque`, `model`, `action_marquevoiture`, `date_created_marque`, `date_updated_marque`) VALUES
(1, 'Renault', 'Clio 4', '1', '2023-01-27 08:57:51', '2023-01-27 10:00:10'),
(2, 'Skoda', 'Fabia', '1', '2023-01-27 08:57:51', '2023-01-27 09:11:22'),
(3, 'Chevrolet', 'Sonic', '1', '2023-01-27 09:12:01', '2023-01-27 09:12:01'),
(4, 'Fiat', 'Punto', '1', '2023-01-27 09:12:23', '2023-01-27 09:12:23'),
(5, 'Peugeot', '301', '1', '2023-01-27 09:12:59', '2023-01-27 09:12:59'),
(6, 'Hyundai', 'i20', '1', '2023-01-27 09:13:48', '2023-01-27 09:13:48'),
(7, 'Renault', 'Clio 5', '1', '2023-01-27 09:14:05', '2023-01-27 09:14:05'),
(8, 'Seat', 'Ibiza', '1', '2023-01-27 09:14:26', '2023-01-27 09:14:26'),
(9, 'Suzuki', 'Swift', '1', '2023-01-27 09:14:52', '2023-01-27 09:14:52'),
(10, 'Volkswagen', 'Polo Sedan', '1', '2023-01-27 09:15:32', '2023-01-27 09:15:32'),
(11, 'Citroën', 'C3', '1', '2023-01-27 09:16:38', '2023-01-27 09:16:38'),
(12, 'Hyundai', 'i10', '1', '2023-01-27 09:17:03', '2023-01-27 09:17:03'),
(13, 'Volkswagen', 'Polo 7', '1', '2023-01-27 09:17:37', '2023-01-27 09:17:37'),
(14, 'Volkswagen', 'Polo 8', '1', '2023-01-27 09:17:43', '2023-01-27 09:17:43');

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
(2, 0, 'Hama', 'hama', '25f9e794323b453885f5181f1b624d0b', '', '1', 'S', '2023-01-24 14:19:13', '2023-01-24 14:24:04'),
(3, 0, 'Mouayed', 'mouayed', '17c4520f6cfd1ab53d8745e84681eb49', '', '2', 'S', '2023-01-24 14:19:13', '2023-01-24 14:24:04'),
(4, 1, 'Mouayed', 'moua', '4a7d1ed414474e4033ac29ccb8653d9b', '', '2', 'T', '2023-01-24 14:19:13', '2023-01-24 16:03:00'),
(5, 2, 'radhouane', 'radhouane', '25f9e794323b453885f5181f1b624d0b', '', '2', 'F', '2023-01-24 14:19:13', '2023-01-24 14:26:20');

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
  `cartegrise_voiture` varchar(200) NOT NULL,
  `assurance_voiture` varchar(200) NOT NULL,
  `action_voiture` enum('0','1') NOT NULL DEFAULT '1',
  `date_created_voiture` timestamp NOT NULL DEFAULT current_timestamp(),
  `date_updated_voiture` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `voiture`
--

INSERT INTO `voiture` (`id_voiture`, `pimm_voiture`, `id_marquemodel`, `id_agence`, `id_typecarburant`, `boitevitesse_voiture`, `cartegrise_voiture`, `assurance_voiture`, `action_voiture`, `date_created_voiture`, `date_updated_voiture`) VALUES
(1, '225 TU 5550', 1, 1, 1, 'Manuelle', 'cartegrise.PNG', 'assurance.PNG', '1', '2023-01-26 10:02:06', '2023-01-26 10:02:06'),
(2, '124 TU 7770', 1, 2, 2, 'Automatique', '3c954f286f8d9196560878bdfea4f449.png', '3c954f286f8d9196560878bdfea4f449.png', '1', '2023-01-26 10:07:57', '2023-01-26 10:07:57'),
(3, '125 TU 2555', 1, 1, 2, 'Manuelle', 'ce0865b86bbf2624342ffd3eb3e511b9.png', 'ce0865b86bbf2624342ffd3eb3e511b9.png', '1', '2023-01-26 13:16:18', '2023-01-26 13:16:18'),
(4, '140 TU 1005', 1, 2, 1, 'Manuelle', '150309b272164191931ad570da522c37.png', '150309b272164191931ad570da522c37.png', '1', '2023-01-26 13:24:01', '2023-01-26 16:23:31'),
(5, '111 TU 4560', 1, 1, 1, 'Manuelle', '859d0e6b309f381c750b8798b0f76dc4.png', '859d0e6b309f381c750b8798b0f76dc4.png', '0', '2023-01-26 13:30:02', '2023-01-26 17:10:00'),
(6, '100 TU 4502', 2, 1, 1, 'Manuelle', '1668695623df0815923c0afd772cb093.png', '1668695623df0815923c0afd772cb093.png', '1', '2023-01-26 13:36:08', '2023-01-26 15:34:16'),
(7, '100 TU 1000', 3, 1, 1, 'Manuelle', '30286e4bc2ab227da4a9cd9e831c82e2.png', '30286e4bc2ab227da4a9cd9e831c82e2.png', '0', '2023-01-27 09:51:08', '2023-01-27 09:51:19'),
(8, '100 TU 4522', 11, 2, 1, 'Manuelle', 'c979f8370a869fc9537ae9a69deae6c0.png', 'c979f8370a869fc9537ae9a69deae6c0.png', '1', '2023-01-27 15:33:15', '2023-01-27 15:33:15');

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
  MODIFY `id_contrat` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `horaire_agence`
--
ALTER TABLE `horaire_agence`
  MODIFY `id_horaire` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT pour la table `marque_voiture`
--
ALTER TABLE `marque_voiture`
  MODIFY `id_marquevoiture` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT pour la table `role_user`
--
ALTER TABLE `role_user`
  MODIFY `id_roleuser` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `voiture`
--
ALTER TABLE `voiture`
  MODIFY `id_voiture` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
