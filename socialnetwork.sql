-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le :  ven. 07 déc. 2018 à 11:34
-- Version du serveur :  10.1.36-MariaDB
-- Version de PHP :  7.2.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `socialnetwork`
--

-- --------------------------------------------------------

--
-- Structure de la table `comments`
--

CREATE TABLE `comments` (
  `comment_id` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `postid` int(11) NOT NULL,
  `comment` varchar(255) NOT NULL,
  `comment_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `comments`
--

INSERT INTO `comments` (`comment_id`, `userid`, `postid`, `comment`, `comment_date`) VALUES
(1, 1, 1, 'nice one', '2018-12-01 14:00:41'),
(2, 1, 3, 'b1 dit', '2018-12-02 14:43:28');

-- --------------------------------------------------------

--
-- Structure de la table `friends`
--

CREATE TABLE `friends` (
  `friendid` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `id_friend_user` int(11) NOT NULL,
  `friend_ok` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `friends`
--

INSERT INTO `friends` (`friendid`, `userid`, `id_friend_user`, `friend_ok`) VALUES
(1, 5, 1, 1),
(2, 1, 5, 1);

-- --------------------------------------------------------

--
-- Structure de la table `friendsrequest`
--

CREATE TABLE `friendsrequest` (
  `friendsrequestID` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `friendid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `friends_post`
--

CREATE TABLE `friends_post` (
  `postid` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `friendid` int(11) NOT NULL,
  `description` varchar(255) CHARACTER SET utf8 COLLATE utf8_estonian_ci NOT NULL,
  `image` varchar(255) NOT NULL,
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `friends_post`
--

INSERT INTO `friends_post` (`postid`, `userid`, `friendid`, `description`, `image`, `create_date`) VALUES
(1, 1, 5, '', '', '2018-12-01 14:19:11');

-- --------------------------------------------------------

--
-- Structure de la table `likes`
--

CREATE TABLE `likes` (
  `likeid` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `postid` int(11) NOT NULL,
  `like_ok` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `likes`
--

INSERT INTO `likes` (`likeid`, `userid`, `postid`, `like_ok`) VALUES
(1, 1, 1, 1),
(2, 5, 2, 1),
(3, 1, 3, 1);

-- --------------------------------------------------------

--
-- Structure de la table `post`
--

CREATE TABLE `post` (
  `postid` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `description` varchar(255) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `post`
--

INSERT INTO `post` (`postid`, `userid`, `description`, `image`, `create_date`) VALUES
(1, 1, '', '5c0293f3ed2ab.jpg', '2018-12-01 14:00:20'),
(2, 5, '', '', '2018-12-01 14:15:49'),
(3, 1, 'post', '', '2018-12-02 14:43:18');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `userid` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `designation` varchar(255) DEFAULT NULL,
  `degree` varchar(255) DEFAULT NULL,
  `university` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `skills` text,
  `aboutme` text,
  `createdat` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `profileimage` varchar(255) DEFAULT NULL,
  `online` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`userid`, `name`, `email`, `password`, `designation`, `degree`, `university`, `city`, `country`, `skills`, `aboutme`, `createdat`, `profileimage`, `online`) VALUES
(1, 'mohamed tidda', 'tidda@t.com', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'student', '3 rd years', 'UHBC', 'chlef', 'algerie', 'javascript-php-java-python', 'mohamed tidda', '2018-12-02 14:44:26', '', 0),
(5, 'khaled', 'khaled@k.com', '7c4a8d09ca3762af61e59520943dc26494f8941b', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2018-12-01 14:15:59', NULL, 0),
(6, 'halima', 'halima@h.com', 'ae453dae41c0400eea6fcf4cc0fecf0209fde956', '', '', '', '', '', '', '', '2018-12-02 14:45:56', '5c03f01d3ab07.jpg', 0);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`comment_id`);

--
-- Index pour la table `friends`
--
ALTER TABLE `friends`
  ADD PRIMARY KEY (`friendid`);

--
-- Index pour la table `friendsrequest`
--
ALTER TABLE `friendsrequest`
  ADD PRIMARY KEY (`friendsrequestID`);

--
-- Index pour la table `friends_post`
--
ALTER TABLE `friends_post`
  ADD PRIMARY KEY (`postid`);

--
-- Index pour la table `likes`
--
ALTER TABLE `likes`
  ADD PRIMARY KEY (`likeid`);

--
-- Index pour la table `post`
--
ALTER TABLE `post`
  ADD PRIMARY KEY (`postid`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userid`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `comments`
--
ALTER TABLE `comments`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `friends`
--
ALTER TABLE `friends`
  MODIFY `friendid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `friendsrequest`
--
ALTER TABLE `friendsrequest`
  MODIFY `friendsrequestID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `friends_post`
--
ALTER TABLE `friends_post`
  MODIFY `postid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `likes`
--
ALTER TABLE `likes`
  MODIFY `likeid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `post`
--
ALTER TABLE `post`
  MODIFY `postid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `userid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
