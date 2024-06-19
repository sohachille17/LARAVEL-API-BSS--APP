-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:3306
-- Généré le : jeu. 28 déc. 2023 à 10:55
-- Version du serveur : 10.3.31-MariaDB-0+deb10u1
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `oos_bloo`
--

-- --------------------------------------------------------

--
-- Structure de la table `bills`
--

CREATE TABLE `bills` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `customer_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customerName` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `dateLimit` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `billNumber` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `compantName` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `serviceAddress` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `postalAddress` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phoneNumber` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customerEmailAddress` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `websiteLink` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `small_note` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` enum('Proforma','Redevance') COLLATE utf8mb4_unicode_ci NOT NULL,
  `currency` enum('Fcfa','Euro','Dollar') COLLATE utf8mb4_unicode_ci NOT NULL,
  `tvaAmount` double DEFAULT NULL,
  `droit_daccises` double NOT NULL DEFAULT 0,
  `montant_ttc` double NOT NULL DEFAULT 0,
  `status` tinyint(4) NOT NULL DEFAULT 0,
  `discount` double NOT NULL DEFAULT 0,
  `sub_total` double NOT NULL DEFAULT 0,
  `reduction_in` double NOT NULL DEFAULT 0,
  `tax_in` double NOT NULL DEFAULT 0,
  `total` double NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `bills`
--

INSERT INTO `bills` (`id`, `customer_id`, `customerName`, `dateLimit`, `billNumber`, `compantName`, `serviceAddress`, `postalAddress`, `phoneNumber`, `customerEmailAddress`, `websiteLink`, `small_note`, `type`, `currency`, `tvaAmount`, `droit_daccises`, `montant_ttc`, `status`, `discount`, `sub_total`, `reduction_in`, `tax_in`, `total`, `created_at`, `updated_at`) VALUES
(110, '15', 'Client Test  du 19 dec', '2023-12-01', 'FAC1703239603687', 'BLOOSAT SA', 'Bloosat dragage', 'BP 750', '+237 655439652', 'gildassob@gmail.com', 'centre', 'merci', 'Proforma', 'Fcfa', 0, 0, 55000, 1, 0, 55000, 0, 0, 55000, '2023-12-22 10:09:30', '2023-12-22 10:09:30'),
(111, '15', 'Client Test  du 19 dec', '2023-12-01', 'RED1703240165', 'BLOOSAT SA', 'Bloosat dragage', 'BP 750', '+237 655439652', 'gildassob@gmail.com', 'centre', 'merci', 'Redevance', 'Fcfa', 0, 0, 30000, 0, 0, 30000, 0, 0, 30000, '2023-12-22 10:16:05', '2023-12-22 10:16:05'),
(112, '25', 'SIAPP PHARMA_DLA_CMR', '2023-12-20', 'FAC1703243797901', 'BLOOSAT SA', 'Dragage Yaoundé', 'BP750', '651585256', 'direction@siappharma.com', 'Douala', 'Merci pour la confiance renouvelée', 'Redevance', 'Fcfa', 19.25, 3800, 231107, 0, 0, 190000, 0, 37307, 193800, '2023-12-22 10:48:53', '2023-12-22 11:17:01'),
(113, '25', 'SIAPP PHARMA_DLA_CMR', '2023-12-20', 'RED1703242305', 'BLOOSAT SA', 'Dragage Yaoundé', 'BP750', '651585256', 'direction@siappharma.com', 'Douala', 'Merci pour la confiance renouvelée', 'Redevance', 'Fcfa', 19.25, 3800, 3924450, 0, 0, 190000, 0, 3730650, 193800, '2023-12-22 10:51:45', '2023-12-22 10:51:45'),
(114, '27', 'SARATEL HOTEL', '2023-12-23', 'FAC1703324548961', 'BLOOSAT SA', 'Bloosat dragage', 'BP 750', '699548332', 'thimotakoufack@yahoo.fr', 'KYE OSSI', 'MERCI POUR LA CONFIANCE RENOUVELÉ', 'Redevance', 'Fcfa', 0, 0, 109000, 0, 0, 109000, 0, 0, 109000, '2023-12-23 09:46:06', '2023-12-23 09:46:06');

-- --------------------------------------------------------

--
-- Structure de la table `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `categoryName` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `categoryDescription` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `categories`
--

INSERT INTO `categories` (`id`, `categoryName`, `categoryDescription`, `created_at`, `updated_at`) VALUES
(3, 'modem HT2010', 'modem du bla bla bla', '2023-12-19 10:14:37', '2023-12-19 10:14:37'),
(4, 'Non definie', 'Aucune description pour', '2023-12-20 10:36:29', '2023-12-20 10:36:29'),
(5, 'RB951 Wifi', 'aucune description pour le moment', '2023-12-20 10:41:28', '2023-12-20 10:41:28'),
(6, '1000 Va', 'aucune description pour le moment', '2023-12-20 10:42:18', '2023-12-20 10:42:18'),
(7, 'Mise en service', 'aucune description pour le moment', '2023-12-20 10:42:51', '2023-12-20 10:42:51');

-- --------------------------------------------------------

--
-- Structure de la table `customers`
--

CREATE TABLE `customers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `customProfile` enum('bigAccount','simpleAccount') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'simpleAccount',
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `generatedCustomId` char(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `country` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telephone1` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telephone2` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` char(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_2` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('residential','business') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'residential',
  `status` int(11) NOT NULL,
  `active` int(11) NOT NULL,
  `deleted` int(11) NOT NULL,
  `region` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `register` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `customers`
--

INSERT INTO `customers` (`id`, `customProfile`, `username`, `name`, `generatedCustomId`, `country`, `city`, `telephone1`, `telephone2`, `email`, `email_2`, `type`, `status`, `active`, `deleted`, `region`, `register`, `created_at`, `updated_at`) VALUES
(15, 'simpleAccount', 'Client Test  du 19 dec', 'Client Test  du 19 dec', '854', 'Cameroun', 'Yaounde', '+237 655439652', '0', 'gildassob@gmail.com', '', 'residential', 1, 0, 0, '0', NULL, '2023-12-19 10:11:16', '2023-12-19 15:39:11'),
(17, 'simpleAccount', 'M_MBAI_GERARD_LOBO_CENTRE_CMR', 'M_MBAI_GERARD_LOBO_CENTRE_CMR', '745', 'CAMEROUN', 'LOBO', '+237 655949432', '0', 'mbaigerard2020@gmail.com', '', 'residential', 1, 0, 0, '0', NULL, '2023-12-19 15:06:13', '2023-12-19 15:31:45'),
(23, 'simpleAccount', 'M. PAUL MBGANELE_LOMIE_CMR', 'M. PAUL MBGANELE_LOMIE_CMR', '31', 'CAMEROUN', 'LOMIE', '+237 699673762', '1', 'pgbalene@gmail.com', '', 'residential', 1, 0, 0, 'Est CAMEROUN', NULL, '2023-12-22 10:19:01', '2023-12-22 10:19:01'),
(24, 'simpleAccount', 'YOUSSOUPHA_TALLA_6_BERTOUA_EST_CMR', 'YOUSSOUPHA_TALLA_6_BERTOUA_EST_CMR', '564', 'CAMEROUN', 'Bertoua', '1', '1', 'youssoupha@gmail', '', 'residential', 1, 0, 0, 'Est CAMEROUN', NULL, '2023-12-22 10:24:12', '2023-12-22 10:24:12'),
(25, 'simpleAccount', 'SIAPP PHARMA_DLA_CMR', 'SIAPP PHARMA_DLA_CMR', '868', 'CAMEROUN', 'DOUALA', '651585256', '1', 'direction@siappharma.com', '', 'business', 1, 0, 0, 'Littoral', NULL, '2023-12-22 10:42:50', '2023-12-22 10:42:50'),
(26, 'simpleAccount', 'CARE AND HEALTH PROGRAM_YDE_CMR', 'CARE AND HEALTH PROGRAM_YDE_CMR', '438', 'Cameroun', 'YAOUNDE', '693 07 67 61', '0000000000', 'franckolaga@yahoo.fr', '', 'residential', 1, 0, 0, 'CENTRE', NULL, '2023-12-22 12:12:16', '2023-12-22 12:12:16'),
(27, 'simpleAccount', 'SARATEL HOTEL', 'SARATEL HOTEL', '449', 'CAMEROUN', 'KYE OSSI', '699548332', '0000000000', 'thimotakoufack@yahoo.fr', '', 'residential', 1, 0, 0, 'Cameroun (KYE OSSI)', NULL, '2023-12-23 09:33:10', '2023-12-23 09:33:10'),
(28, 'simpleAccount', 'MOTA ENGIL_NGOMEZAP_CMR', 'MOTA ENGIL_NGOMEZAP_CMR', '283', 'Cameroun', 'NGOMEZAP', '677 75 13 39', '0000000000', 'caetano@mota-engil.cm', '', 'residential', 1, 0, 0, 'Centre', NULL, '2023-12-26 11:08:45', '2023-12-26 11:08:45');

-- --------------------------------------------------------

--
-- Structure de la table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `invoice_item`
--

CREATE TABLE `invoice_item` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `bills_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `unit_price` double NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `total` double NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `invoice_item`
--

INSERT INTO `invoice_item` (`id`, `bills_id`, `product_id`, `product_name`, `unit_price`, `quantity`, `total`, `created_at`, `updated_at`) VALUES
(170, 110, 14, 'modem microtik (test)', 25000, 1, 25000, '2023-12-22 10:09:30', '2023-12-22 10:09:30'),
(171, 110, 15, 'Forfait Super 100 rapide (test)', 30000, 1, 30000, '2023-12-22 10:09:30', '2023-12-22 10:09:30'),
(172, 111, 15, 'Forfait Super 100 rapide (test)', 30000, 1, 30000, '2023-12-22 10:16:05', '2023-12-22 10:16:05'),
(174, 113, 24, 'UNLIMITED', 190000, 1, 190000, '2023-12-22 10:51:45', '2023-12-22 10:51:45'),
(175, 112, 24, 'UNLIMITED', 190000, 1, 190000, '2023-12-22 11:17:01', '2023-12-22 11:17:01'),
(176, 114, 25, 'PRO 100', 109000, 1, 109000, '2023-12-23 09:46:06', '2023-12-23 09:46:06');

-- --------------------------------------------------------

--
-- Structure de la table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `payments`
--

CREATE TABLE `payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `customerId` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payementDate` date NOT NULL,
  `siteName` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `billReference` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `paymentMethod` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payementAttachment1` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payementAttachment2` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `transactionNumber1` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `transactionNumber2` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `comment` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `registeredBy` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(16,3) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `personal_access_tokens`
--

INSERT INTO `personal_access_tokens` (`id`, `tokenable_type`, `tokenable_id`, `name`, `token`, `abilities`, `last_used_at`, `created_at`, `updated_at`) VALUES
(139, 'App\\Model\\User', 12, 'BssAppToken', 'd55f6bc090bba1879e0fc2cc7683c4b770e0688f4fb8941efbf5c756adb0c0a9', '[\"*\"]', '2023-12-22 08:41:41', '2023-12-18 14:31:01', '2023-12-22 08:41:41'),
(140, 'App\\Model\\User', 12, 'BssAppToken', '1a9a9aec59531e4e8d6d17183d03a01cf428affaedc8f9b9a396a986657ea88d', '[\"*\"]', '2023-12-19 09:15:03', '2023-12-18 14:34:24', '2023-12-19 09:15:03'),
(141, 'App\\Model\\User', 13, 'BssAppToken', '4319d1df95ad0bd67dda1ee42f98b1180601d6035a8e6383fee0c485ebc7d7ef', '[\"*\"]', NULL, '2023-12-18 14:38:09', '2023-12-18 14:38:09'),
(142, 'App\\Model\\User', 14, 'BssAppToken', '52c58342e1b9ffceb23b7e19d7dd39faa740ff9dfeb9c2c7b8088573babd6231', '[\"*\"]', NULL, '2023-12-18 14:39:40', '2023-12-18 14:39:40'),
(143, 'App\\Model\\User', 13, 'BssAppToken', 'b67d23a259ddbaa9be98f8de65021cba19c28055f024b0249e337f9f73f07321', '[\"*\"]', '2023-12-19 09:14:55', '2023-12-18 14:45:43', '2023-12-19 09:14:55'),
(144, 'App\\Model\\User', 14, 'BssAppToken', '1b0c1b73af2cb6bd69246d5ae804c60cd3f53cf97cb4a51a5a945d90bf315d03', '[\"*\"]', '2023-12-19 09:29:05', '2023-12-18 14:50:23', '2023-12-19 09:29:05'),
(145, 'App\\Model\\User', 13, 'BssAppToken', '3e94eb8400ae85fc7cded942d435e50ec5253896b3fa2ec2f9574c2d0d719811', '[\"*\"]', '2023-12-20 10:57:52', '2023-12-18 14:52:46', '2023-12-20 10:57:52'),
(146, 'App\\Model\\User', 12, 'BssAppToken', '3f288d2786d34cb99125413e9d5b4782023ab3191ed6a8c7a6076d3fdfbec21d', '[\"*\"]', '2023-12-19 09:25:30', '2023-12-19 09:21:22', '2023-12-19 09:25:30'),
(147, 'App\\Model\\User', 12, 'BssAppToken', '443a54b88d87320df144c16c1e9876d5b547d2e9c8724004ed06d78ddd7fb85f', '[\"*\"]', '2023-12-20 11:51:05', '2023-12-19 09:29:34', '2023-12-20 11:51:05'),
(148, 'App\\Model\\User', 14, 'BssAppToken', 'a9c68153554fe7835bf5969f07c194258363bf144c58928e0f4ab191ea8eed7e', '[\"*\"]', '2023-12-21 17:34:56', '2023-12-19 09:33:17', '2023-12-21 17:34:56'),
(149, 'App\\Model\\User', 12, 'BssAppToken', '6c56af8e413eda75d528ab4d5bfdc78ae6278a1eda5c4bc732a4219894a07848', '[\"*\"]', '2023-12-20 14:00:39', '2023-12-19 10:25:43', '2023-12-20 14:00:39'),
(150, 'App\\Model\\User', 13, 'BssAppToken', 'fc0cf434c2d9dd479e28aa447263dce312ff77cb6a666eff904862a68bb8d286', '[\"*\"]', '2023-12-23 09:43:25', '2023-12-20 11:03:27', '2023-12-23 09:43:25'),
(151, 'App\\Model\\User', 12, 'BssAppToken', '996d53b51c7c0511474546b4da33a14737aa185dfaafcd4db287626ed4ab85d9', '[\"*\"]', '2023-12-20 15:18:12', '2023-12-20 11:53:03', '2023-12-20 15:18:12'),
(152, 'App\\Model\\User', 12, 'BssAppToken', '95a56492b730617c59b59a07d12db7fa0fa8ee93d22f8f474ffc27bbb972ad8d', '[\"*\"]', '2023-12-22 07:44:32', '2023-12-20 14:23:02', '2023-12-22 07:44:32'),
(153, 'App\\Model\\User', 12, 'BssAppToken', 'e7853c6d3e4b45e3e839fa980fde7d353374ce2d24a6cf9660736cf38e949ac6', '[\"*\"]', '2023-12-22 11:50:30', '2023-12-21 07:06:37', '2023-12-22 11:50:30'),
(154, 'App\\Model\\User', 12, 'BssAppToken', 'a19f2bce968e46751eb8f9bcfc9c93e86be19ef751c226dc7849d8d19a9c363f', '[\"*\"]', '2023-12-23 08:40:06', '2023-12-22 07:44:48', '2023-12-23 08:40:06'),
(155, 'App\\Model\\User', 15, 'BssAppToken', '88616d6dc57232e716200162fbc14ac7b58c9a6f766dc084f4902ef02d6e991f', '[\"*\"]', NULL, '2023-12-22 11:48:08', '2023-12-22 11:48:08'),
(156, 'App\\Model\\User', 15, 'BssAppToken', '47e421472f9e91022e82eb4868469fbb4e5c0004d1645b6cbb12a3a509cf2c56', '[\"*\"]', '2023-12-26 11:09:17', '2023-12-22 11:52:33', '2023-12-26 11:09:17'),
(157, 'App\\Model\\User', 12, 'BssAppToken', '804d359a76bf2a12393abd9fe90d110bcf81c74e5c1e40681e35f12fb8416e07', '[\"*\"]', '2023-12-23 10:20:23', '2023-12-22 11:59:18', '2023-12-23 10:20:23'),
(158, 'App\\Model\\User', 12, 'BssAppToken', '82b76bff3fbb0b00a2efcdc6ab73f795f687dc48d9784da8a7d9bf593bd850c4', '[\"*\"]', '2023-12-22 12:07:17', '2023-12-22 12:07:14', '2023-12-22 12:07:17'),
(159, 'App\\Model\\User', 12, 'BssAppToken', '3a0bbf26a7b4fe631b91ebcc8f09f38297cb3d0bc3a27e2dff3863b9e180ff24', '[\"*\"]', '2023-12-26 07:24:43', '2023-12-22 12:07:56', '2023-12-26 07:24:43'),
(160, 'App\\Model\\User', 16, 'BssAppToken', '30ffc14e408cacc1d2489399243bba2746662baef2982c1b35d17a2c20f631e8', '[\"*\"]', NULL, '2023-12-22 17:03:47', '2023-12-22 17:03:47'),
(161, 'App\\Model\\User', 16, 'BssAppToken', '91db11076f1e7ad3ae369792ef37f69736c4019ea8ed3d637354b3c7dbb3e951', '[\"*\"]', '2023-12-22 17:05:02', '2023-12-22 17:05:01', '2023-12-22 17:05:02'),
(162, 'App\\Model\\User', 12, 'BssAppToken', 'c6f4c11101e2125bb562bab8b109735a9c9622c25d3c17a885183d49b8b95945', '[\"*\"]', '2023-12-23 08:52:15', '2023-12-23 08:52:13', '2023-12-23 08:52:15'),
(163, 'App\\Model\\User', 12, 'BssAppToken', '9dba6b7eaf68fb91a06b15d42813b5a789e1a8b5f33cb26ce6fcf5b94caea24f', '[\"*\"]', '2023-12-23 10:27:43', '2023-12-23 10:20:45', '2023-12-23 10:27:43'),
(164, 'App\\Model\\User', 12, 'BssAppToken', 'b3948921aafef97eb3e3847c53583e8526603d9a61a3045a66de417b106336ac', '[\"*\"]', '2023-12-27 12:12:24', '2023-12-23 10:32:06', '2023-12-27 12:12:24'),
(165, 'App\\Model\\User', 12, 'BssAppToken', '1a68807e70b33f378a2538fe705690f141a2ffa11a61e28c3de30547ed51cb16', '[\"*\"]', '2023-12-26 11:20:42', '2023-12-23 10:47:16', '2023-12-26 11:20:42'),
(166, 'App\\Model\\User', 12, 'BssAppToken', '40a972548df3d34962907d8920001664169db58b198c0c8812e66eda2a59a729', '[\"*\"]', '2023-12-26 07:43:18', '2023-12-26 07:43:16', '2023-12-26 07:43:18'),
(167, 'App\\Model\\User', 12, 'BssAppToken', '0d85d7fe2214ca16e097d2e1ec1c2aabe80dc225c915ede0955b957f12deaa02', '[\"*\"]', '2023-12-26 07:50:09', '2023-12-26 07:50:06', '2023-12-26 07:50:09'),
(168, 'App\\Model\\User', 12, 'BssAppToken', 'b2b639cb403fc69bb5724b0f8f18c5bb10cdd22aa1dd082a60b3120e73026d78', '[\"*\"]', '2023-12-26 08:34:41', '2023-12-26 08:34:40', '2023-12-26 08:34:41'),
(169, 'App\\Model\\User', 12, 'BssAppToken', '6ac89adfd0ee8067f07b07cb15a27fcafb9487027f552118334b445dbd373e2f', '[\"*\"]', '2023-12-27 11:03:57', '2023-12-27 11:03:56', '2023-12-27 11:03:57'),
(170, 'App\\Model\\User', 12, 'BssAppToken', 'aad9aa07b4b019d3414ec4c75142592dc66606031e1391f1965a27ec7678a3da', '[\"*\"]', '2023-12-27 12:21:50', '2023-12-27 12:21:49', '2023-12-27 12:21:50'),
(171, 'App\\Model\\User', 12, 'BssAppToken', '7b3fd5905660124128d528153f375421c784fbf182495b5d2e396744fd837206', '[\"*\"]', '2023-12-28 07:44:06', '2023-12-28 07:44:04', '2023-12-28 07:44:06');

-- --------------------------------------------------------

--
-- Structure de la table `products`
--

CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `category_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `capacity` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` double(8,2) NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `services`
--

CREATE TABLE `services` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `capacity` double(8,2) DEFAULT NULL,
  `amount` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `registeredBy` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `service_capacity` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` enum('product','service') COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `services`
--

INSERT INTO `services` (`id`, `name`, `category_name`, `capacity`, `amount`, `code`, `description`, `registeredBy`, `service_capacity`, `type`, `created_at`, `updated_at`) VALUES
(14, 'modem microtik (test)', 'modem HT2010', NULL, '25000', '506070', 'modem', NULL, NULL, 'product', '2023-12-19 10:16:04', '2023-12-19 10:16:55'),
(15, 'Forfait Super 100 rapide (test)', NULL, NULL, '30000', '4040', '5050', 'Soh Achille', 'modem HT2010', 'service', '2023-12-19 10:16:40', '2023-12-19 10:16:40'),
(16, 'Antenne 74cm, 2 WATTS', 'Non definie', NULL, '270000', '0000', 'Produit Hughes', NULL, NULL, 'product', '2023-12-20 10:36:45', '2023-12-21 09:44:08'),
(17, 'Routeur Mikrotik', 'RB951 Wifi', NULL, '85000', '0000', 'aucune description pour le moment', NULL, NULL, 'product', '2023-12-20 10:45:15', '2023-12-20 10:45:15'),
(18, 'Régulateur de tension', '1000 Va', NULL, '25000', '0000', 'aucune description pour le moment', NULL, NULL, 'product', '2023-12-20 10:45:59', '2023-12-20 10:45:59'),
(19, 'Frais d\'installation et de Configuration', 'Mise en service', NULL, '40000', '0000', 'aucune description pour le moment', NULL, NULL, 'product', '2023-12-20 10:46:42', '2023-12-20 10:46:42'),
(20, 'HOME 25', NULL, NULL, '39000', '(25GB + 150GB) 10/3 MBPS', 'Aucune description pour le moment', 'LIONEL NANGA', 'HOME 25 (25GB + 150GB) 10/3 MBPS', 'service', '2023-12-21 09:33:39', '2023-12-21 09:33:39'),
(21, 'HOME 5', NULL, NULL, '10600', '0', 'Aucune description pour le moment', 'LIONEL NANGA', 'CADENCE EN 5/3Mbps', 'service', '2023-12-22 10:20:42', '2023-12-22 10:20:42'),
(22, 'HOME ILLIMITE', NULL, NULL, '59000', '00', 'Aucune description pour le moment', 'LIONEL NANGA', 'WS20 Unlimited 2/1', 'service', '2023-12-22 10:25:46', '2023-12-22 10:25:46'),
(23, 'SPEED OPTION', NULL, NULL, '20000', '00', 'Aucune description pour le moment', 'LIONEL NANGA', 'Speed Option 3/1', 'service', '2023-12-22 10:28:36', '2023-12-22 10:28:36'),
(24, 'UNLIMITED', NULL, NULL, '190000', '00', 'Aucune description pour le moment', 'LIONEL NANGA', 'ADVANCE Broadband Ka Unlimited 10', 'service', '2023-12-22 10:44:41', '2023-12-22 10:44:41'),
(25, 'PRO 100', NULL, NULL, '109000', '0000', 'Aucune  description pour le moment', 'LIONEL NANGA', 'vitesse 25/3 Mbps', 'service', '2023-12-23 09:38:55', '2023-12-23 09:38:55');

-- --------------------------------------------------------

--
-- Structure de la table `subscriptions`
--

CREATE TABLE `subscriptions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `billReference` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customerId` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customerName` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `endingDate` date NOT NULL,
  `populationSouscription` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `serialNumber` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subscriptionId` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `serviceType` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `siteName` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `startingDate` date NOT NULL,
  `registeredBy` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `registratorName` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `billId` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `paymentStatus` enum('unpaid','paid') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'unpaid',
  `subscriptionStatus` enum('ongoing','finished','royalty') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'royalty',
  `suspentionStatus` enum('suspended','active') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `terminatedOn` date DEFAULT NULL,
  `nextTo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `subscriptionType` enum('KAF','IWAY','BLUESTAR') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'KAF'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `subscriptions`
--

INSERT INTO `subscriptions` (`id`, `billReference`, `customerId`, `customerName`, `endingDate`, `populationSouscription`, `serialNumber`, `subscriptionId`, `serviceType`, `siteName`, `startingDate`, `registeredBy`, `registratorName`, `billId`, `paymentStatus`, `subscriptionStatus`, `suspentionStatus`, `terminatedOn`, `nextTo`, `created_at`, `updated_at`, `subscriptionType`) VALUES
(122, 'FAC1703239603687', '15', 'Client Test  du 19 dec', '2024-01-01', 'BLUESTAR', '12345678', '12345678', 'Bande Ku', 'centre', '2023-12-01', '12', 'Soh Achille', '110', 'paid', 'ongoing', 'active', NULL, NULL, '2023-12-22 10:16:05', '2023-12-22 10:16:05', 'BLUESTAR'),
(123, 'RED1703240165', '15', 'Client Test  du 19 dec', '2024-02-02', 'BLUESTAR', '12345678', '12345678', 'Bande Ku', 'centre', '2024-01-02', '12', 'Soh Achille', '110', 'unpaid', 'royalty', 'active', NULL, '122', '2023-12-22 10:16:05', '2023-12-22 10:16:05', 'BLUESTAR'),
(124, 'FAC1703241960170', '25', 'SIAPP PHARMA_DLA_CMR', '2024-01-05', '200188', '12029550', '234518', 'Bande Ka', 'Douala', '2023-12-05', '13', 'LIONEL NANGA', '112', 'paid', 'ongoing', 'active', NULL, NULL, '2023-12-22 10:51:45', '2023-12-22 10:51:45', 'KAF'),
(125, 'RED1703242305', '25', 'SIAPP PHARMA_DLA_CMR', '2024-02-06', '200188', '12029550', '234518', 'Bande Ka', 'Douala', '2024-01-06', '13', 'LIONEL NANGA', '112', 'unpaid', 'royalty', 'active', NULL, '124', '2023-12-22 10:51:45', '2023-12-22 10:51:45', 'KAF');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('admin','financial','commercial') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'admin',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `status` enum('active','blocked','','') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `role`, `remember_token`, `created_at`, `updated_at`, `status`) VALUES
(12, 'Soh Achille', 'achillesoh15@gmail.com', NULL, '$2y$10$iAtTd1NoggFIxF4QNF5Ls.7uir22TTar6pDOWZsQFjg7eeYxp7SE.', 'admin', NULL, '2023-12-18 14:31:01', '2023-12-18 14:31:01', 'active'),
(13, 'LIONEL NANGA', 'lionel.nanga@bloosat.com', NULL, '$2y$10$7pm1jTJINJxSu6EOWhnXre6EMaK/VCgu4TicEXF7APoiRVafBalkK', 'financial', NULL, '2023-12-18 14:38:09', '2023-12-18 14:38:09', 'active'),
(15, 'Pélagie Maffo', 'pelagie.maffo@bloosat.com', NULL, '$2y$10$bDsRUwFNXgM8lnnc5G2YP.vl6zr.QAHDwWjYYO04i/MJyfwd0K9oy', 'financial', NULL, '2023-12-22 11:48:08', '2023-12-22 11:48:08', 'active'),
(16, 'Francis Bemyin', 'francis.bemyin@bloosat.com', NULL, '$2y$10$SxiObOMaV7mUznJ9qgP1oOXF6wm6OE53vQP8dNEVnCimyCYXM98ay', 'admin', NULL, '2023-12-22 17:03:47', '2023-12-22 17:03:47', 'active');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `bills`
--
ALTER TABLE `bills`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `customers_email_unique` (`email`);

--
-- Index pour la table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `invoice_item`
--
ALTER TABLE `invoice_item`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Index pour la table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Index pour la table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `bills`
--
ALTER TABLE `bills`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=115;

--
-- AUTO_INCREMENT pour la table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT pour la table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `invoice_item`
--
ALTER TABLE `invoice_item`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=177;

--
-- AUTO_INCREMENT pour la table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT pour la table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT pour la table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=172;

--
-- AUTO_INCREMENT pour la table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `services`
--
ALTER TABLE `services`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT pour la table `subscriptions`
--
ALTER TABLE `subscriptions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=126;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
