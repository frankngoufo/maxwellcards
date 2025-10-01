-- phpMyAdmin SQL Dump
-- version 5.1.1deb5ubuntu1
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:3306
-- Généré le : ven. 27 juin 2025 à 11:05
-- Version du serveur : 8.0.42-0ubuntu0.22.04.1
-- Version de PHP : 8.1.2-1ubuntu2.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `maxwellcards`
--

-- --------------------------------------------------------

--
-- Structure de la table `beneficiaries`
--

CREATE TABLE `beneficiaries` (
  `beneficiary_id` int UNSIGNED NOT NULL,
  `benefactor_user_id` int UNSIGNED DEFAULT NULL,
  `beneficiary_name` varchar(255) DEFAULT NULL,
  `beneficiary_mobile_number` varchar(20) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `beneficiaries`
--

INSERT INTO `beneficiaries` (`beneficiary_id`, `benefactor_user_id`, `beneficiary_name`, `beneficiary_mobile_number`, `created_at`, `updated_at`) VALUES
(11, 9, 'FRANK TEST', '237699112233', '2025-06-26 17:43:35', '2025-06-26 17:43:35'),
(12, 9, 'FRANK TEST', '237699112233', '2025-06-26 17:48:23', '2025-06-26 17:48:23');

-- --------------------------------------------------------

--
-- Structure de la table `cards`
--

CREATE TABLE `cards` (
  `id` int UNSIGNED NOT NULL,
  `card_number` varchar(255) NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `name_card` varchar(255) NOT NULL,
  `expiry_date` date NOT NULL,
  `CVV_number` varchar(255) NOT NULL,
  `last_4digits` char(4) NOT NULL,
  `card_status` enum('pending','active','frozen','deleted') NOT NULL DEFAULT 'pending',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `cards`
--

INSERT INTO `cards` (`id`, `card_number`, `user_id`, `name_card`, `expiry_date`, `CVV_number`, `last_4digits`, `card_status`, `created_at`, `updated_at`) VALUES
(1, '4000123456780001', 1, 'Amina Diallo', '2026-12-31', '123', '0001', 'active', '2025-06-24 12:57:32', '2025-06-24 12:57:32'),
(2, '4000123456780002', 2, 'Marc Ndongo', '2027-01-31', '456', '0002', 'pending', '2025-06-24 12:57:32', '2025-06-24 12:57:32'),
(3, '4000123456780003', 3, 'Sophie Manga', '2026-11-30', '789', '0003', 'active', '2025-06-24 12:57:32', '2025-06-24 12:57:32'),
(4, '4000123456780004', 4, 'Olivier Kouam', '2028-03-31', '101', '0004', 'frozen', '2025-06-24 12:57:32', '2025-06-24 12:57:32'),
(5, '4000123456780005', 5, 'Chantal Biloa', '2027-05-31', '202', '0005', 'active', '2025-06-24 12:57:32', '2025-06-24 12:57:32'),
(6, '4000123456780006', 1, 'Amina Diallo', '2026-06-30', '303', '0006', 'pending', '2025-06-24 12:57:32', '2025-06-24 12:57:32'),
(7, '4000123456780007', 2, 'Marc Ndongo', '2028-08-31', '404', '0007', 'active', '2025-06-24 12:57:32', '2025-06-24 12:57:32'),
(8, '4000123456780008', 3, 'Sophie Manga', '2027-09-30', '505', '0008', 'active', '2025-06-24 12:57:32', '2025-06-24 12:57:32'),
(9, '4000123456780009', 9, 'Olivier Kouam', '2026-07-31', '606', '0009', 'frozen', '2025-06-24 12:57:32', '2025-06-27 11:02:26'),
(10, '4000123456780010', 5, 'Chantal Biloa', '2027-10-31', '707', '0010', 'active', '2025-06-24 12:57:32', '2025-06-24 12:57:32'),
(11, '4000123456780011', 1, 'Amina Diallo', '2028-01-31', '808', '0011', 'active', '2025-06-24 12:57:32', '2025-06-24 12:57:32'),
(12, '4000123456780012', 2, 'Marc Ndongo', '2027-02-28', '909', '0012', 'pending', '2025-06-24 12:57:32', '2025-06-24 12:57:32'),
(13, '4000123456780013', 3, 'Sophie Manga', '2026-03-31', '111', '0013', 'active', '2025-06-24 12:57:32', '2025-06-24 12:57:32'),
(14, '4000123456780014', 4, 'Olivier Kouam', '2028-04-30', '222', '0014', 'frozen', '2025-06-24 12:57:32', '2025-06-24 12:57:32'),
(15, '4000123456780015', 5, 'Chantal Biloa', '2027-06-30', '333', '0015', 'active', '2025-06-24 12:57:32', '2025-06-24 12:57:32'),
(16, '4000123456780016', 1, 'Amina Diallo', '2026-09-30', '444', '0016', 'pending', '2025-06-24 12:57:32', '2025-06-24 12:57:32'),
(17, '4000123456780017', 2, 'Marc Ndongo', '2028-11-30', '555', '0017', 'active', '2025-06-24 12:57:32', '2025-06-24 12:57:32'),
(18, '4000123456780018', 3, 'Sophie Manga', '2027-12-31', '666', '0018', 'active', '2025-06-24 12:57:32', '2025-06-24 12:57:32'),
(19, '4000123456780019', 4, 'Olivier Kouam', '2026-10-31', '777', '0019', 'frozen', '2025-06-24 12:57:32', '2025-06-24 12:57:32'),
(20, '4000123456780020', 5, 'Chantal Biloa', '2028-02-29', '888', '0020', 'active', '2025-06-24 12:57:32', '2025-06-24 12:57:32');

-- --------------------------------------------------------

--
-- Structure de la table `card_transactions`
--

CREATE TABLE `card_transactions` (
  `id` int NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `currency` varchar(3) NOT NULL,
  `card_id` int UNSIGNED NOT NULL,
  `date_time_transaction` datetime DEFAULT CURRENT_TIMESTAMP,
  `type_transaction` enum('deposit','withdrawal','purchase') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `card_transactions`
--

INSERT INTO `card_transactions` (`id`, `amount`, `currency`, `card_id`, `date_time_transaction`, `type_transaction`) VALUES
(1, '5000.00', 'XAF', 1, '2025-06-24 13:05:56', 'purchase'),
(2, '25000.00', 'XAF', 2, '2025-06-24 13:05:56', 'deposit'),
(3, '1200.50', 'XAF', 3, '2025-06-24 13:05:56', 'withdrawal'),
(4, '7500.00', 'XAF', 4, '2025-06-24 13:05:56', 'purchase'),
(5, '15000.00', 'XAF', 5, '2025-06-24 13:05:56', 'deposit'),
(6, '3000.00', 'XAF', 6, '2025-06-24 13:05:56', 'purchase'),
(7, '10000.00', 'XAF', 7, '2025-06-24 13:05:56', 'withdrawal'),
(8, '2000.00', 'XAF', 8, '2025-06-24 13:05:56', 'purchase'),
(9, '50000.00', 'XAF', 9, '2025-06-24 13:05:56', 'deposit'),
(10, '800.00', 'XAF', 10, '2025-06-24 13:05:56', 'withdrawal'),
(11, '6500.00', 'XAF', 11, '2025-06-24 13:05:56', 'purchase'),
(12, '30000.00', 'XAF', 12, '2025-06-24 13:05:56', 'deposit'),
(13, '4500.00', 'XAF', 13, '2025-06-24 13:05:56', 'withdrawal'),
(14, '9000.00', 'XAF', 14, '2025-06-24 13:05:56', 'purchase'),
(15, '20000.00', 'XAF', 15, '2025-06-24 13:05:56', 'deposit'),
(16, '1800.00', 'XAF', 16, '2025-06-24 13:05:56', 'withdrawal'),
(17, '7000.00', 'XAF', 17, '2025-06-24 13:05:56', 'purchase'),
(18, '40000.00', 'XAF', 18, '2025-06-24 13:05:56', 'deposit'),
(19, '2500.00', 'XAF', 19, '2025-06-24 13:05:56', 'withdrawal'),
(20, '11000.00', 'XAF', 20, '2025-06-24 13:05:56', 'purchase'),
(21, '1500.00', 'XAF', 1, '2025-06-24 13:05:56', 'withdrawal'),
(22, '35000.00', 'XAF', 2, '2025-06-24 13:05:56', 'deposit'),
(23, '6000.00', 'XAF', 3, '2025-06-24 13:05:56', 'purchase'),
(24, '8000.00', 'XAF', 4, '2025-06-24 13:05:56', 'withdrawal'),
(25, '45000.00', 'XAF', 5, '2025-06-24 13:05:56', 'deposit'),
(26, '2200.00', 'XAF', 6, '2025-06-24 13:05:56', 'purchase'),
(27, '13000.00', 'XAF', 7, '2025-06-24 13:05:56', 'withdrawal'),
(28, '5500.00', 'XAF', 8, '2025-06-24 13:05:56', 'purchase'),
(29, '60000.00', 'XAF', 9, '2025-06-24 13:05:56', 'deposit'),
(30, '950.00', 'XAF', 10, '2025-06-24 13:05:56', 'withdrawal'),
(31, '7200.00', 'XAF', 11, '2025-06-24 13:05:56', 'purchase'),
(32, '28000.00', 'XAF', 12, '2025-06-24 13:05:56', 'deposit'),
(33, '3800.00', 'XAF', 13, '2025-06-24 13:05:56', 'withdrawal'),
(34, '10500.00', 'XAF', 14, '2025-06-24 13:05:56', 'purchase'),
(35, '55000.00', 'XAF', 15, '2025-06-24 13:05:56', 'deposit'),
(36, '5000.00', 'XAF', 9, '2025-06-26 16:17:29', 'deposit'),
(37, '15000.00', 'XAF', 9, '2025-06-26 16:27:25', 'withdrawal'),
(38, '45000.00', 'XAF', 9, '2025-06-26 16:30:53', 'withdrawal'),
(39, '45000.00', 'XAF', 9, '2025-06-26 16:34:42', 'withdrawal'),
(40, '5000.00', 'XAF', 9, '2025-06-26 16:39:17', 'withdrawal'),
(41, '5000.00', 'XAF', 9, '2025-06-26 16:41:36', 'withdrawal'),
(42, '50000.00', 'XAF', 9, '2025-06-26 16:43:00', 'deposit'),
(43, '5000.00', 'XAF', 9, '2025-06-27 10:45:05', 'deposit'),
(44, '50000.00', 'XAF', 9, '2025-06-27 10:47:24', 'withdrawal'),
(45, '5000.00', 'XAF', 9, '2025-06-27 10:47:55', 'withdrawal');

-- --------------------------------------------------------

--
-- Structure de la table `cities`
--

CREATE TABLE `cities` (
  `id_city` int UNSIGNED NOT NULL,
  `city_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `cities`
--

INSERT INTO `cities` (`id_city`, `city_name`) VALUES
(1, 'Douala'),
(2, 'Yaoundé'),
(3, 'Bafoussam'),
(4, 'Garoua'),
(5, 'Maroua'),
(6, 'Bamenda'),
(7, 'Buea'),
(8, 'Bertoua'),
(9, 'Ebolowa'),
(10, 'Kribi'),
(11, 'Ngaoundéré'),
(12, 'Nkongsamba'),
(13, 'Limbe'),
(14, 'Dschang'),
(15, 'Loum'),
(16, 'Mbalmayo'),
(17, 'Sangmélima'),
(18, 'Foumban'),
(19, 'Kumba'),
(20, 'Mutengene'),
(21, 'Paris'),
(22, 'Londres'),
(23, 'New York'),
(24, 'Berlin'),
(25, 'Toronto'),
(26, 'Lagos'),
(27, 'Dakar'),
(28, 'Abidjan'),
(29, 'Bruxelles'),
(30, 'Tokyo');

-- --------------------------------------------------------

--
-- Structure de la table `countries`
--

CREATE TABLE `countries` (
  `id_country` int UNSIGNED NOT NULL,
  `code` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `countries`
--

INSERT INTO `countries` (`id_country`, `code`, `name`) VALUES
(1, 'CM', 'Cameroun'),
(2, 'FR', 'France'),
(3, 'US', 'États-Unis'),
(4, 'GB', 'Royaume-Uni'),
(5, 'DE', 'Allemagne'),
(6, 'CA', 'Canada'),
(7, 'NG', 'Nigeria'),
(8, 'SN', 'Sénégal'),
(9, 'CI', 'Côte d\'Ivoire'),
(10, 'BE', 'Belgique');

-- --------------------------------------------------------

--
-- Structure de la table `notifications`
--

CREATE TABLE `notifications` (
  `id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED DEFAULT NULL,
  `category` enum('deposit','withdrawal','card frozen','card deleted','card transaction') DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `notification_time` datetime DEFAULT CURRENT_TIMESTAMP,
  `message` text,
  `is_read` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `category`, `amount`, `notification_time`, `message`, `is_read`) VALUES
(1, 9, 'deposit', '200.00', '2025-06-26 15:43:14', 'djursij diuhes djoidshwek dsid notificationsioeokdsoie  rjirermer o ', 0),
(2, 9, 'card frozen', '200.00', '2025-06-26 15:43:14', 'jdjdud iejijejeie oieoiekodoie oioiiesje', 0),
(3, 9, 'withdrawal', '150.00', '2025-06-26 15:44:18', 'jjjjjjjjjjjjjfyryuryu yureyrueuee gdteedtyeve notificationsvdgfdgfdvdgfd gdgdgdgd gdgdgd ', 1),
(4, 9, 'card deleted', '200.00', '2025-06-26 15:44:18', 'ureueueueje jfdjdjdjd didieieie oeoeoeo', 0),
(5, 9, 'card frozen', '300.00', '2025-06-26 15:44:18', 'qqqqqqqqqqqaaaaaaaa aaaaaaaaaaaaaaa aaaaaaaaaaa', 0),
(8, 9, 'card deleted', '500.00', '2025-06-26 15:45:27', 'yyyyyyyyyyree rrrrrrwds ggggghh ', 0),
(9, 9, 'card transaction', '400.00', '2025-06-26 15:45:27', 'qqaaaww rrrrrrrrrreeddv vvvvvv', 0),
(10, 9, 'card transaction', '500.00', '2025-06-26 15:45:56', 'vvvvvvvvvvvxfs wwwwwwwwwwwwwwwa qqqqqqqqqqss', 0),
(11, 9, 'withdrawal', '1000.00', '2025-06-26 16:02:35', 'test postman balance 10000 thkansas', 0),
(12, 9, 'deposit', '5000.00', '2025-06-26 16:17:29', 'Votre carte a été créditée de 5000 XAF', 0),
(13, 9, 'withdrawal', '15000.00', '2025-06-26 16:27:25', 'A withdrawal of 15000 XAF was made from your card.', 0),
(14, 9, 'withdrawal', '45000.00', '2025-06-26 16:30:53', 'A withdrawal of 45000 XAF was made from your card.', 0),
(15, 9, 'withdrawal', '45000.00', '2025-06-26 16:34:42', 'A withdrawal of 45000 XAF was made from your card.', 0),
(16, 9, 'withdrawal', '5000.00', '2025-06-26 16:39:17', 'Un retrait de 5000 XAF a été effectué depuis votre carte.', 0),
(17, 9, 'withdrawal', '5000.00', '2025-06-26 16:41:36', 'A withdrawal of 5000 XAF was made from your card.', 0),
(18, 9, 'deposit', '50000.00', '2025-06-26 16:43:00', 'Your card has been credited with 50000 XAF', 0),
(19, 9, 'card frozen', NULL, '2025-06-26 16:57:44', 'Votre carte a été gelée.', 0),
(20, 9, 'deposit', '5000.00', '2025-06-27 10:45:05', 'Your card has been credited with 5000 XAF', 0),
(21, 9, 'withdrawal', '50000.00', '2025-06-27 10:47:24', 'A withdrawal of 50000 XAF was made from your card.', 0),
(22, 9, 'withdrawal', '5000.00', '2025-06-27 10:47:55', 'A withdrawal of 5000 XAF was made from your card.', 0),
(23, 9, 'card frozen', NULL, '2025-06-27 11:02:26', 'Your card has been frozen.', 0);

-- --------------------------------------------------------

--
-- Structure de la table `transactions`
--

CREATE TABLE `transactions` (
  `id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `status` tinytext NOT NULL,
  `amount` decimal(9,2) NOT NULL,
  `currency` tinytext NOT NULL,
  `payment_type` tinytext NOT NULL,
  `charged_amount` decimal(9,2) NOT NULL DEFAULT '0.00',
  `reference` tinytext NOT NULL,
  `transaction_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `transaction_type` enum('expense','income','withdrawal') NOT NULL DEFAULT 'expense',
  `gateway_reference` tinytext,
  `gateway_transaction_id` tinytext NOT NULL,
  `product_id` int UNSIGNED NOT NULL DEFAULT '0',
  `comments` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `transactions`
--

INSERT INTO `transactions` (`id`, `user_id`, `status`, `amount`, `currency`, `payment_type`, `charged_amount`, `reference`, `transaction_time`, `transaction_type`, `gateway_reference`, `gateway_transaction_id`, `product_id`, `comments`) VALUES
(1, 1, 'completed', '15000.00', 'XAF', 'card', '15150.00', 'TXNREF0001', '2025-06-24 13:11:01', 'expense', 'GATEWAYREF1A', 'GWID12345678A', 1, 'Achat en ligne de fournitures'),
(2, 2, 'pending', '50000.00', 'XAF', 'mobile_money', '50500.00', 'TXNREF0002', '2025-06-24 13:11:01', 'income', NULL, 'GWID87654321B', 0, 'Rechargement compte'),
(3, 9, 'failed', '2500.00', 'XAF', 'bank_transfer', '2500.00', 'TXNREF0003', '2025-06-26 14:38:38', 'withdrawal', 'GATEWAYREF3C', 'GWID23456789C', 0, 'Échec de retrait DAB'),
(4, 9, 'completed', '7500.00', 'EUR', 'card', '7575.00', 'TXNREF0004', '2025-06-26 14:38:38', 'expense', 'GATEWAYREF4D', 'GWID34567890D', 2, 'Abonnement service streaming'),
(5, 9, 'completed', '100000.00', 'XAF', 'bank_transfer', '101000.00', 'TXNREF0005', '2025-06-26 14:19:00', 'income', NULL, 'GWID45678901E', 0, 'Salaire mensuel'),
(6, 1, 'completed', '3000.00', 'XAF', 'mobile_money', '3030.00', 'TXNREF0006', '2025-06-24 13:11:01', 'expense', 'GATEWAYREF6F', 'GWID56789012F', 3, 'Paiement facture téléphone'),
(7, 2, 'pending', '12000.00', 'USD', 'card', '12120.00', 'TXNREF0007', '2025-06-24 13:11:01', 'expense', 'GATEWAYREF7G', 'GWID67890123G', 4, 'Achat logiciel'),
(8, 3, 'completed', '8000.00', 'XAF', 'withdrawal', '8000.00', 'TXNREF0008', '2025-06-24 13:11:01', 'withdrawal', 'GATEWAYREF8H', 'GWID78901234H', 0, 'Retrait distributeur'),
(9, 9, 'completed', '20000.00', 'XAF', 'card', '20200.00', 'TXNREF0009', '2025-06-26 14:38:38', 'expense', 'GATEWAYREF9I', 'GWID89012345I', 5, 'Courses supermarché'),
(10, 5, 'failed', '5000.00', 'EUR', 'bank_transfer', '5000.00', 'TXNREF0010', '2025-06-24 13:11:01', 'expense', NULL, 'GWID90123456J', 0, 'Tentative paiement loyer échouée'),
(11, 9, 'completed', '70000.00', 'XAF', 'bank_transfer', '70700.00', 'TXNREF0011', '2025-06-26 14:38:38', 'income', 'GATEWAYREF11K', 'GWID01234567K', 0, 'Virement ami'),
(12, 9, 'completed', '1800.00', 'XAF', 'mobile_money', '1818.00', 'TXNREF0012', '2025-06-26 14:19:09', 'expense', 'GATEWAYREF12L', 'GWID12345678L', 6, 'Achat crédit communication'),
(13, 9, 'pending', '30000.00', 'USD', 'card', '30300.00', 'TXNREF0013', '2025-06-26 14:38:38', 'expense', 'GATEWAYREF13M', 'GWID23456789M', 7, 'Réservation hôtel'),
(14, 4, 'completed', '4000.00', 'XAF', 'withdrawal', '4000.00', 'TXNREF0014', '2025-06-24 13:11:01', 'withdrawal', 'GATEWAYREF14N', 'GWID34567890N', 0, 'Retrait espèces'),
(15, 5, 'completed', '8500.00', 'EUR', 'card', '8585.00', 'TXNREF0015', '2025-06-24 13:11:01', 'expense', NULL, 'GWID45678901O', 8, 'Achat livre'),
(16, 1, 'completed', '25000.00', 'XAF', 'bank_transfer', '25250.00', 'TXNREF0016', '2025-06-24 13:11:01', 'income', 'GATEWAYREF16P', 'GWID56789012P', 0, 'Remboursement'),
(17, 9, 'completed', '600.00', 'XAF', 'mobile_money', '606.00', 'TXNREF0017', '2025-06-26 14:38:38', 'expense', 'GATEWAYREF17Q', 'GWID67890123Q', 9, 'Achat petit déjeuner'),
(18, 9, 'failed', '10000.00', 'XAF', 'card', '10000.00', 'TXNREF0018', '2025-06-26 14:38:38', 'expense', 'GATEWAYREF18R', 'GWID78901234R', 10, 'Paiement facture électricité'),
(19, 4, 'completed', '1500.00', 'USD', 'bank_transfer', '1515.00', 'TXNREF0019', '2025-06-24 13:11:01', 'expense', NULL, 'GWID89012345S', 0, 'Transfert international'),
(20, 5, 'completed', '12000.00', 'XAF', 'withdrawal', '12000.00', 'TXNREF0020', '2025-06-24 13:11:01', 'withdrawal', 'GATEWAYREF20T', 'GWID90123456T', 0, 'Retrait pour dépenses courantes'),
(21, 9, 'pending', '3500.00', 'XAF', 'card', '3535.00', 'TXNREF0021', '2025-06-26 14:38:38', 'expense', 'GATEWAYREF21A', 'GWID12345678U', 1, 'Achat de médicaments'),
(22, 9, 'completed', '80000.00', 'XAF', 'bank_transfer', '80800.00', 'TXNREF0022', '2025-06-26 14:38:38', 'income', NULL, 'GWID87654321V', 0, 'Vente de services'),
(23, 9, 'completed', '4500.00', 'XAF', 'mobile_money', '4545.00', 'TXNREF0023', '2025-06-26 14:38:38', 'expense', 'GATEWAYREF23B', 'GWID23456789W', 2, 'Paiement transport'),
(24, 9, 'completed', '18000.00', 'EUR', 'card', '18180.00', 'TXNREF0024', '2025-06-26 14:18:52', 'expense', 'GATEWAYREF24C', 'GWID34567890X', 3, 'Voyage aérien'),
(25, 9, 'failed', '6000.00', 'XAF', 'withdrawal', '6000.00', 'TXNREF0025', '2025-06-26 14:38:38', 'withdrawal', 'GATEWAYREF25D', 'GWID45678901Y', 0, 'Échec retrait'),
(26, 1, 'completed', '500.00', 'XAF', 'mobile_money', '505.00', 'TXNREF0026', '2025-06-24 13:11:01', 'expense', 'GATEWAYREF26E', 'GWID56789012Z', 4, 'Recharge data'),
(27, 2, 'completed', '22000.00', 'XAF', 'card', '22220.00', 'TXNREF0027', '2025-06-24 13:11:01', 'expense', 'GATEWAYREF27F', 'GWID67890123AA', 5, 'Achat électroménager'),
(28, 3, 'pending', '7000.00', 'USD', 'bank_transfer', '7070.00', 'TXNREF0028', '2025-06-24 13:11:01', 'income', NULL, 'GWID78901234BB', 0, 'Réception paiement client'),
(29, 4, 'completed', '950.00', 'XAF', 'withdrawal', '950.00', 'TXNREF0029', '2025-06-24 13:11:01', 'withdrawal', 'GATEWAYREF29G', 'GWID89012345CC', 0, 'Petit retrait'),
(30, 5, 'completed', '30000.00', 'XAF', 'card', '30300.00', 'TXNREF0030', '2025-06-24 13:11:01', 'expense', 'GATEWAYREF30H', 'GWID90123456DD', 6, 'Achat mobilier'),
(31, 1, 'failed', '1200.00', 'XAF', 'mobile_money', '1200.00', 'TXNREF0031', '2025-06-24 13:11:01', 'expense', 'GATEWAYREF31I', 'GWID01234567EE', 7, 'Achat en ligne'),
(32, 2, 'completed', '5000.00', 'XAF', 'withdrawal', '5000.00', 'TXNREF0032', '2025-06-24 13:11:01', 'withdrawal', 'GATEWAYREF32J', 'GWID12345678FF', 0, 'Retrait bancaire'),
(33, 3, 'completed', '40000.00', 'EUR', 'bank_transfer', '40400.00', 'TXNREF0033', '2025-06-24 13:11:01', 'income', NULL, 'GWID23456789GG', 0, 'Investissement'),
(34, 4, 'pending', '15000.00', 'USD', 'card', '15150.00', 'TXNREF0034', '2025-06-24 13:11:01', 'expense', 'GATEWAYREF34K', 'GWID34567890HH', 8, 'Achat équipement informatique'),
(35, 5, 'completed', '2000.00', 'XAF', 'mobile_money', '2020.00', 'TXNREF0035', '2025-06-24 13:11:01', 'expense', 'GATEWAYREF35L', 'GWID45678901II', 9, 'Paiement transport en commun'),
(36, 1, 'completed', '900.00', 'XAF', 'card', '909.00', 'TXNREF0036', '2025-06-24 13:11:01', 'expense', 'GATEWAYREF36M', 'GWID56789012JJ', 10, 'Achat café'),
(37, 2, 'completed', '10000.00', 'XAF', 'withdrawal', '10000.00', 'TXNREF0037', '2025-06-24 13:11:01', 'withdrawal', 'GATEWAYREF37N', 'GWID67890123KK', 0, 'Retrait urgence'),
(38, 3, 'failed', '750.00', 'EUR', 'bank_transfer', '750.00', 'TXNREF0038', '2025-06-24 13:11:01', 'expense', NULL, 'GWID78901234LL', 0, 'Tentative de virement'),
(39, 4, 'completed', '25000.00', 'XAF', 'bank_transfer', '25250.00', 'TXNREF0039', '2025-06-24 13:11:01', 'income', 'GATEWAYREF39O', 'GWID89012345MM', 0, 'Virement familial'),
(40, 5, 'completed', '5500.00', 'XAF', 'card', '5555.00', 'TXNREF0040', '2025-06-24 13:11:01', 'expense', 'GATEWAYREF40P', 'GWID90123456NN', 1, 'Restauration'),
(41, 1, 'completed', '300.00', 'XAF', 'mobile_money', '303.00', 'TXNREF0041', '2025-06-24 13:11:01', 'expense', 'GATEWAYREF41Q', 'GWID01234567OO', 2, 'Petit achat'),
(42, 2, 'pending', '18000.00', 'USD', 'card', '18180.00', 'TXNREF0042', '2025-06-24 13:11:01', 'expense', 'GATEWAYREF42R', 'GWID12345678PP', 3, 'Logiciel professionnel'),
(43, 3, 'completed', '4200.00', 'XAF', 'withdrawal', '4200.00', 'TXNREF0043', '2025-06-24 13:11:01', 'withdrawal', 'GATEWAYREF43S', 'GWID23456789QQ', 0, 'Retrait pour loyer'),
(44, 4, 'completed', '90000.00', 'XAF', 'bank_transfer', '90900.00', 'TXNREF0044', '2025-06-24 13:11:01', 'income', NULL, 'GWID34567890RR', 0, 'Revenu freelance'),
(45, 5, 'failed', '8000.00', 'EUR', 'card', '8000.00', 'TXNREF0045', '2025-06-24 13:11:01', 'expense', 'GATEWAYREF45T', 'GWID45678901SS', 4, 'Achat vêtements'),
(46, 1, 'completed', '7500.00', 'XAF', 'card', '7575.00', 'TXNREF0046', '2025-06-24 13:11:01', 'expense', 'GATEWAYREF46U', 'GWID56789012TT', 5, 'Facture internet'),
(47, 2, 'completed', '6000.00', 'XAF', 'withdrawal', '6000.00', 'TXNREF0047', '2025-06-24 13:11:01', 'withdrawal', 'GATEWAYREF47V', 'GWID67890123UU', 0, 'Retrait de fonds'),
(48, 3, 'pending', '3000.00', 'XAF', 'mobile_money', '3030.00', 'TXNREF0048', '2025-06-24 13:11:01', 'expense', 'GATEWAYREF48W', 'GWID78901234VV', 6, 'Paiement abonnement'),
(49, 4, 'completed', '11000.00', 'USD', 'bank_transfer', '11110.00', 'TXNREF0049', '2025-06-24 13:11:01', 'income', NULL, 'GWID89012345WW', 0, 'Virement de l\'étranger'),
(50, 5, 'completed', '2000.00', 'XAF', 'card', '2020.00', 'TXNREF0050', '2025-06-24 13:11:01', 'expense', 'GATEWAYREF50X', 'GWID90123456XX', 7, 'Achat petit matériel'),
(51, 1, 'completed', '200.00', 'XAF', 'mobile_money', '202.00', 'TXNREF0051', '2025-06-24 13:11:01', 'expense', 'GATEWAYREF51Y', 'GWID01234567YY', 8, 'Achat boisson'),
(52, 2, 'completed', '14000.00', 'XAF', 'withdrawal', '14000.00', 'TXNREF0052', '2025-06-24 13:11:01', 'withdrawal', 'GATEWAYREF52Z', 'GWID12345678ZZ', 0, 'Retrait pour voyage'),
(53, 3, 'failed', '9000.00', 'EUR', 'card', '9000.00', 'TXNREF0053', '2025-06-24 13:11:01', 'expense', 'GATEWAYREF53A', 'GWID23456789AB', 9, 'Paiement cours en ligne'),
(54, 4, 'completed', '65000.00', 'XAF', 'bank_transfer', '65650.00', 'TXNREF0054', '2025-06-24 13:11:01', 'income', NULL, 'GWID34567890CD', 0, 'Bonus annuel'),
(55, 5, 'completed', '4500.00', 'XAF', 'mobile_money', '4545.00', 'TXNREF0055', '2025-06-24 13:11:01', 'expense', 'GATEWAYREF55B', 'GWID45678901EF', 10, 'Facture eau'),
(56, 1, 'completed', '1000.00', 'XAF', 'card', '1010.00', 'TXNREF0056', '2025-06-24 13:11:01', 'expense', 'GATEWAYREF56C', 'GWID56789012GH', 1, 'Dons'),
(57, 2, 'pending', '28000.00', 'USD', 'bank_transfer', '28280.00', 'TXNREF0057', '2025-06-24 13:11:01', 'income', NULL, 'GWID67890123IJ', 0, 'Virement professionnel'),
(58, 3, 'completed', '300.00', 'XAF', 'withdrawal', '300.00', 'TXNREF0058', '2025-06-24 13:11:01', 'withdrawal', 'GATEWAYREF58D', 'GWID78901234KL', 0, 'Petit retrait caisse'),
(59, 4, 'completed', '17500.00', 'XAF', 'card', '17675.00', 'TXNREF0059', '2025-06-24 13:11:01', 'expense', 'GATEWAYREF59E', 'GWID89012345MN', 2, 'Achat cadeau'),
(60, 5, 'completed', '7000.00', 'XAF', 'mobile_money', '7070.00', 'TXNREF0060', '2025-06-24 13:11:01', 'expense', 'GATEWAYREF60F', 'GWID90123456OP', 3, 'Paiement restaurant');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int UNSIGNED NOT NULL,
  `first_name` tinytext NOT NULL,
  `last_name` tinytext,
  `telephone` tinytext,
  `status` enum('pending','nonverified','verified') DEFAULT NULL,
  `otp` int UNSIGNED NOT NULL DEFAULT '0',
  `otp_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `payment_reference` tinytext,
  `address` tinytext,
  `profession` tinytext,
  `activity_sector` tinytext,
  `city` varchar(255) DEFAULT NULL,
  `country_id` int UNSIGNED DEFAULT NULL,
  `id_type` enum('nid','passport') DEFAULT NULL,
  `id_number` tinytext,
  `display_photo` tinytext,
  `id_card_image` tinytext,
  `photo_with_id_card` tinytext,
  `pin_code` smallint UNSIGNED DEFAULT NULL,
  `email` tinytext
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `telephone`, `status`, `otp`, `otp_time`, `payment_reference`, `address`, `profession`, `activity_sector`, `city`, `country_id`, `id_type`, `id_number`, `display_photo`, `id_card_image`, `photo_with_id_card`, `pin_code`, `email`) VALUES
(1, 'nbhbj', 'log', '6666666', 'verified', 1, '2025-06-23 17:38:27', 'visa', 'logpmszj', 'etudiant', 'school', 'douala', NULL, NULL, '2', 'dsjkfok', 'ksjdijdf', 'sijdh', 2000, 'mega'),
(2, 'Amina', 'Diallo', '+237670123456', 'verified', 876543, '2025-06-24 12:20:12', 'PAYREF101', 'Rue 10, Quartier Bonamoussadi', 'Développeuse Web', 'Informatique', 'yaounde', 1, 'nid', 'CNI1234567890', 'https://example.com/amina_dp.jpg', 'https://example.com/amina_nid.jpg', 'https://example.com/amina_photo_id.jpg', 1122, 'amina.diallo@email.com'),
(3, 'Marc', 'Ndongo', '+237699876543', 'pending', 234567, '2025-06-24 12:20:12', 'PAYREF102', 'Avenue de l\'Indépendance, Tsinga', 'Architecte', 'Construction', 'yaounde', 1, 'passport', 'PASSPORTXYZ789', 'https://example.com/marc_dp.jpg', 'https://example.com/marc_pass.jpg', 'https://example.com/marc_photo_id.jpg', 3344, 'marc.ndongo@email.com'),
(4, 'Sophie', 'Manga', '+237655443322', 'nonverified', 987123, '2025-06-24 12:20:12', 'PAYREF103', 'Rue des Ecoles, Carrefour Kenedy', 'Journaliste', 'Médias', 'douala', 1, 'nid', 'CNI0987654321', 'https://example.com/sophie_dp.jpg', 'https://example.com/sophie_nid.jpg', 'https://example.com/sophie_photo_id.jpg', 5566, 'sophie.manga@email.com'),
(5, 'Olivier', 'Kouam', '+237680001122', 'verified', 123987, '2025-06-24 12:20:12', 'PAYREF104', 'BP 789, Centre Ville', 'Médecin', 'Santé', 'kumba', 1, 'passport', 'PASSPORTABC456', 'https://example.com/olivier_dp.jpg', 'https://example.com/olivier_pass.jpg', 'https://example.com/olivier_photo_id.jpg', 7788, 'olivier.kouam@email.com'),
(6, 'Chantal', 'Biloa', '+237660998877', 'pending', 567890, '2025-06-24 12:20:12', 'PAYREF105', 'Rue de la Mairie, Quartier Administratif', 'Commerciale', 'Vente et Marketing', 'yaounde', 1, 'nid', 'CNI5678901234', 'https://example.com/chantal_dp.jpg', 'https://example.com/chantal_nid.jpg', 'https://example.com/chantal_photo_id.jpg', 9900, 'chantal.biloa@email.com'),
(7, 'No Name', '', '+237659068655', NULL, 565102, '2025-06-24 15:16:08', NULL, NULL, NULL, NULL, NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, 'frankngoufo@email.com'),
(8, 'No Name', '', '+237670107417', NULL, 278732, '2025-06-24 15:56:12', NULL, NULL, NULL, NULL, NULL, 9, NULL, NULL, NULL, NULL, NULL, NULL, 'phpvisa@email.com'),
(9, 'peter', 'maxwell', '+237650909090', 'pending', 707365, '2025-06-24 16:06:19', NULL, 'bonanjo air france building', '', 'driver', 'douala', 2, 'passport', '100365510', 'uploads/photo_685c12b70f918.png', 'uploads/photo_685c12b70f989.png', 'uploads/photo_685c12b70f9c0.png', NULL, 'phpvisa'),
(10, 'frank ngoufo', 'ngoufo', '+237670107417', 'verified', 98123, '2025-06-24 17:57:08', NULL, 'yaounde', 'it', NULL, 'yaounde', 5, NULL, NULL, NULL, NULL, NULL, NULL, 'frankngoufo1@gmail.com');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `beneficiaries`
--
ALTER TABLE `beneficiaries`
  ADD PRIMARY KEY (`beneficiary_id`),
  ADD KEY `beneficiaries_ibfk_1` (`benefactor_user_id`);

--
-- Index pour la table `cards`
--
ALTER TABLE `cards`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `card_number` (`card_number`),
  ADD KEY `user_id` (`user_id`);

--
-- Index pour la table `card_transactions`
--
ALTER TABLE `card_transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `card_id` (`card_id`);

--
-- Index pour la table `cities`
--
ALTER TABLE `cities`
  ADD PRIMARY KEY (`id_city`);

--
-- Index pour la table `countries`
--
ALTER TABLE `countries`
  ADD PRIMARY KEY (`id_country`);

--
-- Index pour la table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Index pour la table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_user_id` (`user_id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_country` (`country_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `beneficiaries`
--
ALTER TABLE `beneficiaries`
  MODIFY `beneficiary_id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT pour la table `cards`
--
ALTER TABLE `cards`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT pour la table `card_transactions`
--
ALTER TABLE `card_transactions`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT pour la table `cities`
--
ALTER TABLE `cities`
  MODIFY `id_city` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT pour la table `countries`
--
ALTER TABLE `countries`
  MODIFY `id_country` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT pour la table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT pour la table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `beneficiaries`
--
ALTER TABLE `beneficiaries`
  ADD CONSTRAINT `beneficiaries_ibfk_1` FOREIGN KEY (`benefactor_user_id`) REFERENCES `users` (`id`);

--
-- Contraintes pour la table `cards`
--
ALTER TABLE `cards`
  ADD CONSTRAINT `cards_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Contraintes pour la table `card_transactions`
--
ALTER TABLE `card_transactions`
  ADD CONSTRAINT `card_transactions_ibfk_1` FOREIGN KEY (`card_id`) REFERENCES `cards` (`id`);

--
-- Contraintes pour la table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Contraintes pour la table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `fk_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Contraintes pour la table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_country` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id_country`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
