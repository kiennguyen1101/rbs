-- phpMyAdmin SQL Dump
-- version 3.3.9
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jan 20, 2012 at 02:56 PM
-- Server version: 5.5.8
-- PHP Version: 5.3.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `rbs`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE IF NOT EXISTS `admins` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `admin_name` varchar(64) CHARACTER SET utf8 NOT NULL,
  `password` varchar(32) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `admin_name`, `password`) VALUES
(3, 'admin', 'e10adc3949ba59abbe56e057f20f883e');

-- --------------------------------------------------------

--
-- Table structure for table `affiliate_archive`
--

CREATE TABLE IF NOT EXISTS `affiliate_archive` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `email` varchar(50) NOT NULL,
  `subject` varchar(256) NOT NULL,
  `questions` varchar(256) NOT NULL,
  `answer` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=17 ;

--
-- Dumping data for table `affiliate_archive`
--


-- --------------------------------------------------------

--
-- Table structure for table `affiliate_payment`
--

CREATE TABLE IF NOT EXISTS `affiliate_payment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `buyer_affiliate_fee` varchar(256) NOT NULL,
  `buyer_min_amount` varchar(256) NOT NULL,
  `buyer_min_payout` varchar(256) NOT NULL,
  `buyer_max_payout` varchar(256) NOT NULL,
  `buyer_project_fee` varchar(256) NOT NULL,
  `seller_affiliate_fee` varchar(256) NOT NULL,
  `seller_min_amount` varchar(256) NOT NULL,
  `seller_min_payout` varchar(256) NOT NULL,
  `seller_max_payout` varchar(256) NOT NULL,
  `seller_project_fee` varchar(256) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `affiliate_payment`
--

INSERT INTO `affiliate_payment` (`id`, `buyer_affiliate_fee`, `buyer_min_amount`, `buyer_min_payout`, `buyer_max_payout`, `buyer_project_fee`, `seller_affiliate_fee`, `seller_min_amount`, `seller_min_payout`, `seller_max_payout`, `seller_project_fee`) VALUES
(2, '30', '10', '10', 'unlimited', '10', '20', '10', '10', 'unlimited', '20');

-- --------------------------------------------------------

--
-- Table structure for table `affiliate_questions`
--

CREATE TABLE IF NOT EXISTS `affiliate_questions` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `email` varchar(50) NOT NULL,
  `subject` varchar(256) NOT NULL,
  `questions` varchar(256) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=24 ;

--
-- Dumping data for table `affiliate_questions`
--


-- --------------------------------------------------------

--
-- Table structure for table `affiliate_released_payments`
--

CREATE TABLE IF NOT EXISTS `affiliate_released_payments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `refid` varchar(128) NOT NULL,
  `account_type` int(11) NOT NULL,
  `payment` varchar(20) NOT NULL,
  `created_date` int(11) NOT NULL,
  `created_date_forrmat` varchar(28) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `affiliate_released_payments`
--


-- --------------------------------------------------------

--
-- Table structure for table `affiliate_unreleased_payments`
--

CREATE TABLE IF NOT EXISTS `affiliate_unreleased_payments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `refid` varchar(128) NOT NULL,
  `account_type` int(11) NOT NULL,
  `payment` varchar(20) NOT NULL,
  `created_date` int(11) NOT NULL,
  `created_date_format` varchar(28) NOT NULL,
  `is_released` varchar(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `affiliate_unreleased_payments`
--


-- --------------------------------------------------------

--
-- Table structure for table `affiliate_welcome_msg`
--

CREATE TABLE IF NOT EXISTS `affiliate_welcome_msg` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `refid` varchar(128) NOT NULL,
  `referel` varchar(128) NOT NULL,
  `welcome_msg` text NOT NULL,
  `msg_status` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

--
-- Dumping data for table `affiliate_welcome_msg`
--


-- --------------------------------------------------------

--
-- Table structure for table `bans`
--

CREATE TABLE IF NOT EXISTS `bans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ban_type` varchar(20) NOT NULL,
  `ban_value` varchar(255) NOT NULL,
  `ban_time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=21 ;

--
-- Dumping data for table `bans`
--


-- --------------------------------------------------------

--
-- Table structure for table `bids`
--

CREATE TABLE IF NOT EXISTS `bids` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `bid_days` int(11) NOT NULL,
  `bid_hours` int(11) NOT NULL,
  `bid_amount` mediumint(9) NOT NULL,
  `bid_time` int(11) NOT NULL,
  `bid_desc` text CHARACTER SET utf8 NOT NULL,
  `lowbid_notify` enum('0','1') CHARACTER SET utf8 NOT NULL,
  `escrow_flag` smallint(6) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=71 ;

--
-- Dumping data for table `bids`
--


-- --------------------------------------------------------

--
-- Table structure for table `bookmark`
--

CREATE TABLE IF NOT EXISTS `bookmark` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `creator_id` varchar(128) NOT NULL,
  `creator_name` varchar(128) NOT NULL,
  `project_id` varchar(128) NOT NULL,
  `project_name` varchar(128) NOT NULL,
  `project_creator` varchar(128) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=31 ;

--
-- Dumping data for table `bookmark`
--


-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE IF NOT EXISTS `categories` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `category_name` varchar(255) CHARACTER SET utf8 NOT NULL,
  `group_id` smallint(6) unsigned NOT NULL,
  `description` text CHARACTER SET utf8 NOT NULL,
  `page_title` varchar(255) CHARACTER SET utf8 NOT NULL,
  `meta_keywords` text CHARACTER SET utf8 NOT NULL,
  `meta_description` text CHARACTER SET utf8 NOT NULL,
  `is_active` tinyint(4) NOT NULL DEFAULT '1',
  `created` int(11) NOT NULL,
  `modified` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=19 ;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `category_name`, `group_id`, `description`, `page_title`, `meta_keywords`, `meta_description`, `is_active`, `created`, `modified`) VALUES
(12, 'PHP', 11, 'PHP', 'PHP', 'PHP', 'This is mainly used in the Web site development', 1, 1255432752, 1256121533),
(13, 'Smart Phone', 12, 'Smart Phone (iPhone, android,v.v...)', 'smart-phone', 'smart phone', 'smart phone', 1, 1326861645, 1326861645),
(14, 'TV', 12, 'TV', 'TV', 'TV', 'TV', 1, 1326861670, 1326861670),
(15, 'DVD player', 12, 'DVD player', 'dvd-player', 'dvd player', 'dvd player', 1, 1326861701, 1326861701),
(16, 'Table', 13, 'Table', 'table', 'table', 'table', 1, 1326861766, 1326861766),
(17, 'chair', 13, 'chair', 'chair', 'chair', 'chair', 1, 1326862447, 1326862447),
(18, 'bed', 13, 'bed', 'bed', 'bed', 'bed', 1, 1326862485, 1326862485);

-- --------------------------------------------------------

--
-- Table structure for table `clickthroughs`
--

CREATE TABLE IF NOT EXISTS `clickthroughs` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `refid` varchar(20) DEFAULT 'none',
  `created_date` date NOT NULL DEFAULT '0000-00-00',
  `time` time NOT NULL DEFAULT '00:00:00',
  `browser` varchar(200) DEFAULT 'Could Not Find This Data',
  `ipaddress` varchar(50) DEFAULT 'Could Not Find This Data',
  `refferalurl` varchar(200) DEFAULT 'none detected (maybe a direct link)',
  `buy` varchar(10) DEFAULT 'NO',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=66 ;

--
-- Dumping data for table `clickthroughs`
--


-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE IF NOT EXISTS `contacts` (
  `id` int(12) NOT NULL AUTO_INCREMENT,
  `email_id` varchar(128) NOT NULL,
  `subject` varchar(128) CHARACTER SET utf8 NOT NULL,
  `comments` text CHARACTER SET utf8 NOT NULL,
  `created` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=23 ;

--
-- Dumping data for table `contacts`
--


-- --------------------------------------------------------

--
-- Table structure for table `country`
--

CREATE TABLE IF NOT EXISTS `country` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `country_symbol` varchar(3) CHARACTER SET utf8 NOT NULL,
  `country_name` varchar(255) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=238 ;

--
-- Dumping data for table `country`
--

INSERT INTO `country` (`id`, `country_symbol`, `country_name`) VALUES
(1, 'US', 'United States'),
(2, 'AF', 'Afghanistan'),
(3, 'AL', 'Albania'),
(4, 'DZ', 'Algeria'),
(5, 'AS', 'American Samoa'),
(6, 'AD', 'Andorra'),
(7, 'AO', 'Angola'),
(8, 'AI', 'Anguilla'),
(9, 'AG', 'Antigua and Barbuda'),
(10, 'AR', 'Argentina'),
(11, 'AM', 'Armenia'),
(12, 'AW', 'Aruba'),
(13, 'AU', 'Australia'),
(14, 'AT', 'Austria'),
(15, 'AZ', 'Azerbaijan'),
(16, 'BS', 'Bahamas'),
(17, 'BH', 'Bahrain'),
(18, 'BD', 'Bangladesh'),
(19, 'BB', 'Barbados'),
(20, 'BY', 'Belarus'),
(21, 'BE', 'Belgium'),
(22, 'BZ', 'Belize'),
(23, 'BJ', 'Benin'),
(24, 'BM', 'Bermuda'),
(25, 'BT', 'Bhutan'),
(26, 'BO', 'Bolivia'),
(27, 'BA', 'Bosnia and Herzegovina'),
(28, 'BW', 'Botswana'),
(29, 'BV', 'Bouvet Island'),
(30, 'BR', 'Brazil'),
(31, 'IO', 'British Indian Ocean Territory'),
(32, 'VG', 'British Virgin Islands'),
(33, 'BN', 'Brunei'),
(34, 'BG', 'Bulgaria'),
(35, 'BF', 'Burkina Faso'),
(36, 'BI', 'Burundi'),
(37, 'KH', 'Cambodia'),
(38, 'CM', 'Cameroon'),
(39, 'CA', 'Canada'),
(40, 'CV', 'Cape Verde'),
(41, 'KY', 'Cayman Islands'),
(42, 'CF', 'Central African Republic'),
(43, 'TD', 'Chad'),
(44, 'CL', 'Chile'),
(45, 'CN', 'China'),
(46, 'CX', 'Christmas Island'),
(47, 'CC', 'Cocos (Keeling) Islands'),
(48, 'CO', 'Colombia'),
(49, 'KM', 'Comoros'),
(50, 'CG', 'Congo'),
(51, 'CD', 'Congo - Democratic Republic of'),
(52, 'CK', 'Cook Islands'),
(53, 'CR', 'Costa Rica'),
(54, 'HR', 'Croatia'),
(55, 'CU', 'Cuba'),
(56, 'CY', 'Cyprus'),
(57, 'CZ', 'Czech Republic'),
(58, 'DK', 'Denmark'),
(59, 'DJ', 'Djibouti'),
(60, 'DM', 'Dominica'),
(61, 'DO', 'Dominican Republic'),
(62, 'TP', 'East Timor'),
(63, 'EC', 'Ecuador'),
(64, 'EG', 'Egypt'),
(65, 'SV', 'El Salvador'),
(66, 'GQ', 'Equitorial Guinea'),
(67, 'ER', 'Eritrea'),
(68, 'EE', 'Estonia'),
(69, 'ET', 'Ethiopia'),
(70, 'FK', 'Falkland Islands (Islas Malvinas)'),
(71, 'FO', 'Faroe Islands'),
(72, 'FJ', 'Fiji'),
(73, 'FI', 'Finland'),
(74, 'FR', 'France'),
(75, 'GF', 'French Guyana'),
(76, 'PF', 'French Polynesia'),
(77, 'TF', 'French Southern and Antarctic Lands'),
(78, 'GA', 'Gabon'),
(79, 'GM', 'Gambia'),
(80, 'GZ', 'Gaza Strip'),
(81, 'GE', 'Georgia'),
(82, 'DE', 'Germany'),
(83, 'GH', 'Ghana'),
(84, 'GI', 'Gibraltar'),
(85, 'GR', 'Greece'),
(86, 'GL', 'Greenland'),
(87, 'GD', 'Grenada'),
(88, 'GP', 'Guadeloupe'),
(89, 'GU', 'Guam'),
(90, 'GT', 'Guatemala'),
(91, 'GN', 'Guinea'),
(92, 'GW', 'Guinea-Bissau'),
(93, 'GY', 'Guyana'),
(94, 'HT', 'Haiti'),
(95, 'HM', 'Heard Island and McDonald Islands'),
(96, 'VA', 'Holy See (Vatican City)'),
(97, 'HN', 'Honduras'),
(98, 'HK', 'Hong Kong'),
(99, 'HU', 'Hungary'),
(100, 'IS', 'Iceland'),
(101, 'IN', 'India'),
(102, 'ID', 'Indonesia'),
(103, 'IR', 'Iran'),
(104, 'IQ', 'Iraq'),
(105, 'IE', 'Ireland'),
(106, 'IL', 'Israel'),
(107, 'IT', 'Italy'),
(108, 'JM', 'Jamaica'),
(109, 'JP', 'Japan'),
(110, 'JO', 'Jordan'),
(111, 'KZ', 'Kazakhstan'),
(112, 'KE', 'Kenya'),
(113, 'KI', 'Kiribati'),
(114, 'KW', 'Kuwait'),
(115, 'KG', 'Kyrgyzstan'),
(116, 'LA', 'Laos'),
(117, 'LV', 'Latvia'),
(118, 'LB', 'Lebanon'),
(119, 'LS', 'Lesotho'),
(120, 'LR', 'Liberia'),
(121, 'LY', 'Libya'),
(122, 'LI', 'Liechtenstein'),
(123, 'LT', 'Lithuania'),
(124, 'LU', 'Luxembourg'),
(125, 'MO', 'Macau'),
(126, 'MK', 'Macedonia - The Former Yugoslav Republic of'),
(127, 'MG', 'Madagascar'),
(128, 'MW', 'Malawi'),
(129, 'MY', 'Malaysia'),
(130, 'MV', 'Maldives'),
(131, 'ML', 'Mali'),
(132, 'MT', 'Malta'),
(133, 'MH', 'Marshall Islands'),
(134, 'MQ', 'Martinique'),
(135, 'MR', 'Mauritania'),
(136, 'MU', 'Mauritius'),
(137, 'YT', 'Mayotte'),
(138, 'MX', 'Mexico'),
(139, 'FM', 'Micronesia - Federated States of'),
(140, 'MD', 'Moldova'),
(141, 'MC', 'Monaco'),
(142, 'MN', 'Mongolia'),
(143, 'MS', 'Montserrat'),
(144, 'MA', 'Morocco'),
(145, 'MZ', 'Mozambique'),
(146, 'MM', 'Myanmar'),
(147, 'NA', 'Namibia'),
(148, 'NR', 'Naura'),
(149, 'NP', 'Nepal'),
(150, 'NL', 'Netherlands'),
(151, 'AN', 'Netherlands Antilles'),
(152, 'NC', 'New Caledonia'),
(153, 'NZ', 'New Zealand'),
(154, 'NI', 'Nicaragua'),
(155, 'NE', 'Niger'),
(156, 'NG', 'Nigeria'),
(157, 'NU', 'Niue'),
(158, 'NF', 'Norfolk Island'),
(159, 'KP', 'North Korea'),
(160, 'MP', 'Northern Mariana Islands'),
(161, 'NO', 'Norway'),
(162, 'OM', 'Oman'),
(163, 'PK', 'Pakistan'),
(164, 'PW', 'Palau'),
(165, 'PA', 'Panama'),
(166, 'PG', 'Papua New Guinea'),
(167, 'PY', 'Paraguay'),
(168, 'PE', 'Peru'),
(169, 'PH', 'Philippines'),
(170, 'PN', 'Pitcairn Islands'),
(171, 'PL', 'Poland'),
(172, 'PT', 'Portugal'),
(173, 'PR', 'Puerto Rico'),
(174, 'QA', 'Qatar'),
(175, 'RE', 'Reunion'),
(176, 'RO', 'Romania'),
(177, 'RU', 'Russia'),
(178, 'RW', 'wanda'),
(179, 'KN', 'Saint Kitts and Nevis'),
(180, 'LC', 'Saint Lucia'),
(181, 'VC', 'Saint Vincent and the Grenadines'),
(182, 'WS', 'Samoa'),
(183, 'SM', 'San Marino'),
(184, 'ST', 'Sao Tome and Principe'),
(185, 'SA', 'Saudi Arabia'),
(186, 'SN', 'Senegal'),
(187, 'CS', 'Serbia and Montenegro'),
(188, 'SC', 'Seychelles'),
(189, 'SL', 'Sierra Leone'),
(190, 'SG', 'Singapore'),
(191, 'SK', 'Slovakia'),
(192, 'SI', 'Slovenia'),
(193, 'SB', 'Solomon Islands'),
(194, 'SO', 'Somalia'),
(195, 'ZA', 'South Africa'),
(196, 'GS', 'South Georgia and the South Sandwich Islands'),
(197, 'KR', 'South Korea'),
(198, 'ES', 'Spain'),
(199, 'LK', 'Sri Lanka'),
(200, 'SH', 'St. Helena'),
(201, 'PM', 'St. Pierre and Miquelon'),
(202, 'SD', 'Sudan'),
(203, 'SR', 'Suriname'),
(204, 'SJ', 'Svalbard'),
(205, 'SZ', 'Swaziland'),
(206, 'SE', 'Sweden'),
(207, 'CH', 'Switzerland'),
(208, 'SY', 'Syria'),
(209, 'TW', 'Taiwan'),
(210, 'TJ', 'Tajikistan'),
(211, 'TZ', 'Tanzania'),
(212, 'TH', 'Thailand'),
(213, 'TG', 'Togo'),
(214, 'TK', 'Tokelau'),
(215, 'TO', 'Tonga'),
(216, 'TT', 'Trinidad and Tobago'),
(217, 'TN', 'Tunisia'),
(218, 'TR', 'Turkey'),
(219, 'TM', 'Turkmenistan'),
(220, 'TC', 'Turks and Caicos Islands'),
(221, 'TV', 'Tuvalu'),
(222, 'UG', 'Uganda'),
(223, 'UA', 'Ukraine'),
(224, 'AE', 'United Arab Emirates'),
(225, 'GB', 'United Kingdom'),
(226, 'VI', 'United States Virgin Islands'),
(227, 'UY', 'Uruguay'),
(228, 'UZ', 'Uzbekistan'),
(229, 'VU', 'Vanuatu'),
(230, 'VE', 'Venezuela'),
(231, 'VN', 'Vietnam'),
(232, 'WF', 'Wallis and Futuna'),
(233, 'PS', 'West Bank'),
(234, 'EH', 'Western Sahara'),
(235, 'YE', 'Yemen'),
(236, 'ZM', 'Zambia'),
(237, 'ZW', 'Zimbabwe');

-- --------------------------------------------------------

--
-- Table structure for table `dispute_agree`
--

CREATE TABLE IF NOT EXISTS `dispute_agree` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `case_id` int(11) NOT NULL,
  `buyer_agree` enum('disagree','agree') NOT NULL,
  `provider_agree` enum('disagree','agree') NOT NULL,
  `project_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `dispute_agree`
--


-- --------------------------------------------------------

--
-- Table structure for table `draftprojects`
--

CREATE TABLE IF NOT EXISTS `draftprojects` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `project_name` varchar(255) CHARACTER SET utf8 NOT NULL,
  `project_status` enum('0','1','2','3') NOT NULL,
  `description` text CHARACTER SET utf8 NOT NULL,
  `project_categories` text CHARACTER SET utf8 NOT NULL,
  `budget_min` int(11) unsigned DEFAULT '0',
  `budget_max` int(11) unsigned DEFAULT '0',
  `is_feature` int(1) DEFAULT NULL,
  `is_urgent` int(1) DEFAULT NULL,
  `is_hide_bids` int(1) DEFAULT NULL,
  `creator_id` int(10) unsigned NOT NULL,
  `created` int(11) NOT NULL,
  `enddate` int(11) NOT NULL,
  `seller_id` int(11) NOT NULL,
  `checkstamp` varchar(50) NOT NULL,
  `buyer_rated` enum('0','1') NOT NULL,
  `provider_rated` enum('0','1') NOT NULL,
  `project_paid` enum('0','1') NOT NULL,
  `is_private` int(11) DEFAULT NULL,
  `contact` text NOT NULL,
  `salary` varchar(15) NOT NULL,
  `flag` int(1) NOT NULL,
  `salarytype` varchar(100) NOT NULL,
  `private_users` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=48 ;

--
-- Dumping data for table `draftprojects`
--


-- --------------------------------------------------------

--
-- Table structure for table `email_templates`
--

CREATE TABLE IF NOT EXISTS `email_templates` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(64) CHARACTER SET utf8 NOT NULL,
  `title` text CHARACTER SET utf8 NOT NULL,
  `mail_subject` text CHARACTER SET utf8 NOT NULL,
  `mail_body` text CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=40 ;

--
-- Dumping data for table `email_templates`
--

INSERT INTO `email_templates` (`id`, `type`, `title`, `mail_subject`, `mail_body`) VALUES
(1, 'sellers_signup', 'sellers signup', 'Confirm E-mail for !site_title', 'Thank you for choosing !site_title for your programming project. Please click the link below to continue the signup process.\r\n\r\nConfirmation Link: !activation_link \r\n\r\nContact support if you have any problems signing up !contact_link'),
(4, 'buyers_signup', 'buyers signup', 'Confirm E-mail for !site_name', 'Thank you for choosing !site_name for your programming project. Please click here to continue the signup process. \r\n\r\nConfirmation Link:!activation_url \r\n\r\nContact support if you have any problems signing up.!contact_url'),
(5, 'awardBid', 'Award Project', 'Confirmation for bidding on !project_title', 'You were chosen for the project named !project_title.\r\n\r\nImportant: You must first accept (or deny) this offer by going to the following URL: !bid_url\r\nIf you wait too long another provider could be chosen! So accept the bid now.\r\n\r\nIf you have any problems with this step you can contact !contact_url'),
(6, 'project_accepted_buyer', 'Project accepted - buyer', 'Project start', 'The provider "!seller_username" accepted the project named "!project_title".\r\n\r\nYou may contact this provider at !seller_email\r\n\r\nIf you have any problems with this email you can contact !contact_url'),
(7, 'project_accepted_seller', 'Project accepted - seller', 'Project start', 'You have won and accepted the project named "!project_title".\r\n\r\nYou may contact the projects buyer "!buyer_username" at !buyer_email.\r\n\r\nIf you have any problems with this email you can contact !contact_url'),
(8, 'project_denied_buyer', 'Project denying', 'project denied', 'The provider "!provider_username" did not accept the project named "!project_title".\r\n\r\nThe project is now open again for bidding.\r\n\r\nIf you have any problems with this email you can contact !contact_url'),
(9, 'privateInvitation', 'Private Project Invitation', '!site_title Favorite Private Project Invitation', 'The Buyer !buyername! has posted a private project (!projectname!)\r\nand has invited you to bid on it. Only invited users can bid on private\r\nprojects.\r\nYou may login and view this project at \r\n!projecturl!\r\n\r\n\r\n--------------------------------------------------\r\nThis message has been sent automatically by !site_title.\r\nIf you need to contact us go to !siteurl!'),
(10, 'publicInvitation', 'Project Invitation', 'Project Invitation', '!buyername! has just invited you to place a bid on their project. The name of the project is "!projectname!" and you can view it at the following\r\nURL: !projecturl!\r\n\r\n--------------------------------------------------\r\nThis message has been sent automatically by ScriptLance.\r\nIf you need to contact us go to !siteurl!'),
(11, 'forget_password', 'Forget Password', 'New Password for Login', 'Thank you for creating an account at !url\r\n\r\nYour username: !username\r\nYour password: !newpassword'),
(12, 'projectpost_notification', 'New Project Post', 'New Project', '!username !\r\nThank you !username for Post project on !site_name site.\r\n\r\nProject Id   : !projectid\r\nProject Name : !projectname\r\nCreate Date  : !date\r\nProfile      : !profile'),
(13, 'project_cancelled', 'cancel projects', 'Project cancelled', 'Dear !buyer_name\r\n\r\nYour project "!project_name" is canceled.\r\n\r\nIf you have any problems with this email you can contact !contact_url'),
(14, 'project_end', 'Project End', 'Project End', 'The project !projectname has just ended. Unfortunately your bid was not chosen.\r\n\r\n\r\nThis message has been sent by !sitetitle. Do not reply to this email. Click here to !contact_url support.'),
(15, 'bid_notice', 'Bid notice', '!site_name Project Bid Notice', 'The Provider !provider_name has just bid !bid_amt in !bid_time on your project !project_name\r\n<br><br>\r\nIf you have any problems with this email you can contact !contact_url'),
(16, 'lowbid_notify', 'Low bid notification', 'Low bid notification', 'The provider "!provider_name" has just bid !bid_amt for the project !project_name lower than your bid amount !bid_amt2\r\n<br><br>\r\nIf you have any problems with this email you can contact !contact_url'),
(17, 'registration', 'New Registration', '!site_name New Registration', 'Hello !username,\r\nThank you for register in !siteurl. \r\n\r\nYou are successfully register in !siteurl as !usertype using the following information.\r\n\r\nUsername : !username\r\nPassword : !password\r\n\r\nYou should not post any questions or queries to this email. You can post any queries into the following url !contact_url. \r\n\r\nThank and Regards,\r\nAdmin '),
(18, 'transaction', 'Amount Transaction', '!site_name Amount Transaction', 'Hello !username,\r\n\r\nThank you for using !site_name.\r\n\r\nYour transaction is work in progress. After completion of the Transaction you will be receive an Email from !site_name.\r\n\r\nYour Transaction details as follows,\r\n\r\nCreator name      : !username\r\nTransaction Type  : !type\r\nAmount            : $ !amount\r\n!others\r\n!others1\r\n\r\nYou should not post any queries to this Email. Please post any question or queries to the url !contact_url.\r\n\r\nThanks and Regards,\r\n\r\nAdmin\r\n!site_name'),
(19, 'message_template', 'Message Template', 'New Message Received on !site_name', 'Hello !username,<br>\r\n\r\nYou are received new message from !sender_name regarding !reason on !site_name.<br>\r\n\r\nYou can login into !site_url and view your new messages. <br>\r\n\r\nYou should not post any question or comments to this email. Please post your comments or question to the url !contact_url.<br>\r\n\r\nThanks and Regards,<br>\r\n\r\nAdmin<br>\r\n!site_name'),
(20, 'buyer_review', 'Buyer review', 'You''ve got a review from !site_title', 'The Buyer <b>!buyer_name</b> have just posted a review for you on the project <b>!project_name</b> you did.\r\n<br><br>\r\nRegards,<br>\r\n!site_name'),
(21, 'profile_update', 'Profile Update Notification', 'Profile Update Notification on !site_name', 'Hello !username,<br>\r\n\r\nThanks for using !site_name. <br>\r\n\r\nYour profile has been successfully updated. <br>\r\nYour update profile datas are as follows, <br>\r\n\r\n!data1\r\n!data2\r\n!data3\r\n!data4\r\n!data5\r\n!data6\r\n!data7\r\n!data8\r\n!data9\r\n!data10\r\n!data11\r\n!data12\r\n\r\nYou should not post any question or comments to this email. Please post your comments or question to the url !contact_url.<br>\r\n\r\nThanks and Regards,<br>\r\n\r\nAdmin<br>\r\n!site_name'),
(22, 'seller_review', 'Seller review', 'You''ve got a review from !site_title', 'The Seller <b>!seller_name</b> have just posted a review for your project <b>!project_name</b> you did.\r\n<br><br>\r\nRegards,<br>\r\n!site_name'),
(23, 'project_cancel', 'Project Cancel', 'Project Cancel on !site_name', 'Hello !username,<br>\r\n\r\nThank you for using !site_name.<br>\r\n\r\nYour project has been cancelled by !creatorname in !site_url. <br>\r\n\r\nThe details as follows,<br>\r\n\r\nProject ID   : !projectid <br>\r\n\r\nProject Name : !projectname <br>\r\n\r\nCreator Name : !creatorname <br>\r\n\r\nYou want to reactive the project, please  login into !site_url. <br>\r\n\r\nYou should not post any question or comments to this email. Please post your comments or question to the url !contact_url.<br>\r\n\r\nThanks and Regards,<br>\r\n\r\nAdmin<br>\r\n!site_name\r\n'),
(24, 'consolidate_bids', 'Consolidate Bids Details', 'Consolidate Bids Details', 'Hello !username,\r\n\r\nThanks for Using !site_name. <br>\r\n\r\nThe consolidate project bids for your project !projectname as follows, <br>\r\n\r\n!records<br>\r\n\r\nYou should not post any question or comments to this email. Please post your comments or question to the url !contact_url.<br>\r\n\r\nThanks and Regards,<br>\r\n\r\nAdmin<br>\r\n!site_name\r\n\r\n'),
(25, 'email_banned', 'Email Banned', 'Email Baned in !site_name', 'Hello !username,<br>\r\n\r\nThanks for using !site_name.<br>\r\n\r\nYour !type has been Banned by !site_url.<br>\r\nIf you want to reactive, please contact !site_name or contact admin. <br>\r\n\r\nYou should not post any question or comments to this email. Please post your comments or question to the url !contact_url.<br>\r\n\r\nThanks and Regards,<br>\r\n\r\nAdmin<br>\r\n!site_name\r\n'),
(28, 'ticket_post', 'Ticket Post', 'Ticket Post', '!username !\r\nThank you !username for Post Ticket on !site_name site.\r\n\r\nCall Id           : !callid\r\nCategory          : !category\r\nSubject           : !subject\r\nDescription       : !description\r\nPriority          : !priority\r\nStatus            : !status'),
(27, 'response_ticket', 'Response Ticket', 'Response for your Post from !site_name. !question', '!username !\n\nThank you !username for Post Ticket on !site_name site.\n\nCall Id           : !callid\nSubject           : !subject\nDescription       : !description\n\n!response'),
(29, 'privateproject_post', 'Private Project', 'Private Project', '!username !\r\nThank you !username for Posting project on !site_name site.\r\n\r\nProject Id   : !projectid\r\nProject Name : !projectname\r\nCreate Date  : !date\r\nProfile      : !profile\r\nPrivate Providers:\r\n!privateproviders'),
(30, 'private_project_provider', 'Private Project', 'Private Project on !site_name', 'Hello !username,<br>\r\n\r\nThank you for using !site_name.<br>\r\n\r\nThe following  project has been created by !creatorname in !site_name for private project. <br>\r\n\r\nThe details as follows,<br>\r\n\r\nProject ID   : !projectid <br>\r\n\r\nProject Name : !projectname <br>\r\n\r\nCreator Name : !creatorname <br>\r\n\r\nView Project : !projecturl <br>\r\n\r\nThanks and Regards,<br>\r\n\r\nAdmin<br>\r\n!site_name\r\n'),
(31, 'project_cancel_admin', 'Admin acknowledgement', 'Project cancellation case opened', 'A cancellation case opened on the Project "!project_name" by the !user_type - "!user".<br><br>The case id is - !case_id'),
(32, 'cancellation_case', 'Project cancellation', '!site_title Project cancellation', 'Dear !other_user,<br><br>!user opened a cancellation case on the Project "!project_name"<br><br>Please click on the link to see the opened case !link<br><br>If you have any problems with this email you can contact !contact_url'),
(33, 'respond_case', 'Response for cancellation/dispute', 'Response from !user for "!pr_name" cancellation', 'Dear !other_user,<br><br>!user has responded for the cancellation case for the shippment "!project_name"<br><br>Please click on the link to see the case details !link<br><br>If you have any problems with this email you can contact !contact_url'),
(34, 'response_case_admin', 'response acknowledgment', 'Response for cancellation case', 'A response from the !user_type - "!user" for the cancellation case on the Shippment "!project_name".<br><br>The case id is - !case_id'),
(35, 'email_suspended', 'Email Suspended', 'Email Suspended in !site_name', 'Hello !username,<br>\r\n\r\nThanks for using !site_name.<br>\r\n\r\nYour !type has been suspended by !site_url.<br>\r\nIf you want to reactive, please contact !site_name or contact admin. <br>\r\n\r\nYou should not post any question or comments to this email. Please post your comments or question to the url !contact_url.<br>\r\n\r\nThanks and Regards,<br>\r\n\r\nAdmin<br>\r\n!site_name\r\n'),
(36, 'changeto_dispute_case', 'Cancellation case to dispute case', 'Cancellation case changed to dispute case', 'Dear !user,<br><br>Cancellation case of the Project\r\n"!project_name" has been changed to dispute case<br><br>Please click on the link to see the case details !link<br><br>If you have any problems with this email you can contact !contact_url'),
(37, 'remove_review', 'remove_review', '!site_title - Review Removed Admin', 'Hello !user, \n\nThe review for the Project !project_title has been removed.\n\nURL : !project_name\n\nIf you have any problems with this email you can contact !contact_url'),
(38, 'project_cancelled_admin', 'Admin acknowledgement', 'Project cancellation case opened', 'A cancellation case opened on the Project "!project_name" by the "!user_type" - "!user".<br><br>The case id is - "!case_id"'),
(39, 'case_closed', 'Case closed by admin', 'Shippment cancellation/Dispute case closed', 'Dear !user,<br><br>Cancellation case of the Project "!project_name" hase been closed by administrator<br><br>Please click on the link to see the case details !link<br><br>If you have any problems with this email you can contact !contact_url');

-- --------------------------------------------------------

--
-- Table structure for table `escrow_release_request`
--

CREATE TABLE IF NOT EXISTS `escrow_release_request` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `transaction_id` varchar(128) NOT NULL,
  `request_date` varchar(128) NOT NULL,
  `status` varchar(128) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=58 ;

--
-- Dumping data for table `escrow_release_request`
--


-- --------------------------------------------------------

--
-- Table structure for table `faqs`
--

CREATE TABLE IF NOT EXISTS `faqs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `faq_category_id` int(10) unsigned NOT NULL,
  `question` text NOT NULL,
  `answer` text NOT NULL,
  `is_frequent` char(1) NOT NULL DEFAULT 'N',
  `created` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `faqs`
--


-- --------------------------------------------------------

--
-- Table structure for table `faq_categories`
--

CREATE TABLE IF NOT EXISTS `faq_categories` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `category_name` varchar(128) CHARACTER SET utf8 NOT NULL,
  `created` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `faq_categories`
--


-- --------------------------------------------------------

--
-- Table structure for table `files`
--

CREATE TABLE IF NOT EXISTS `files` (
  `id` int(12) NOT NULL AUTO_INCREMENT,
  `user_id` int(128) NOT NULL,
  `location` varchar(128) CHARACTER SET utf8 NOT NULL,
  `created` int(11) NOT NULL,
  `delete` int(11) NOT NULL,
  `key` varchar(128) CHARACTER SET utf8 NOT NULL,
  `description` varchar(128) CHARACTER SET utf8 NOT NULL,
  `file_size` int(128) NOT NULL,
  `file_type` varchar(128) CHARACTER SET utf8 NOT NULL,
  `original_name` varchar(128) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=52 ;

--
-- Dumping data for table `files`
--


-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

CREATE TABLE IF NOT EXISTS `groups` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `group_name` varchar(255) CHARACTER SET utf8 NOT NULL,
  `descritpion` text CHARACTER SET utf8,
  `created` int(11) DEFAULT NULL,
  `modified` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=14 ;

--
-- Dumping data for table `groups`
--

INSERT INTO `groups` (`id`, `group_name`, `descritpion`, `created`, `modified`) VALUES
(11, 'web', 'web', 1255432734, 1255432734),
(12, 'Electronics', 'Electronic equipment', 1326861560, 1326861560),
(13, 'Furniture', 'Furnitur (chair, table,v.v...)', 1326861729, 1326861729);

-- --------------------------------------------------------

--
-- Table structure for table `home_category`
--

CREATE TABLE IF NOT EXISTS `home_category` (
  `id` int(11) NOT NULL,
  `category` varchar(25) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `home_category`
--

INSERT INTO `home_category` (`id`, `category`) VALUES
(1, 'Table'),
(2, 'Smart Phone'),
(3, 'TV'),
(4, 'chair'),
(5, 'bed'),
(6, 'DVD Player');

-- --------------------------------------------------------

--
-- Table structure for table `ipn_return`
--

CREATE TABLE IF NOT EXISTS `ipn_return` (
  `invoice` int(100) unsigned NOT NULL,
  `receiver_email` varchar(60) DEFAULT NULL,
  `item_name` varchar(100) DEFAULT NULL,
  `item_number` varchar(10) DEFAULT NULL,
  `quantity` varchar(6) DEFAULT NULL,
  `payment_status` varchar(10) DEFAULT NULL,
  `pending_reason` varchar(10) DEFAULT NULL,
  `payment_date` varchar(20) DEFAULT NULL,
  `mc_gross` varchar(20) DEFAULT NULL,
  `mc_fee` varchar(20) DEFAULT NULL,
  `tax` varchar(20) DEFAULT NULL,
  `mc_currency` varchar(3) DEFAULT NULL,
  `txn_id` varchar(20) DEFAULT NULL,
  `txn_type` varchar(10) DEFAULT NULL,
  `first_name` varchar(30) DEFAULT NULL,
  `last_name` varchar(40) DEFAULT NULL,
  `address_street` varchar(50) DEFAULT NULL,
  `address_city` varchar(30) DEFAULT NULL,
  `address_state` varchar(30) DEFAULT NULL,
  `address_zip` varchar(20) DEFAULT NULL,
  `address_country` varchar(30) DEFAULT NULL,
  `address_status` varchar(10) DEFAULT NULL,
  `payer_email` varchar(60) DEFAULT NULL,
  `payer_status` varchar(10) DEFAULT NULL,
  `payment_type` varchar(10) DEFAULT NULL,
  `notify_version` varchar(10) DEFAULT NULL,
  `verify_sign` varchar(10) DEFAULT NULL,
  `referrer_id` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`invoice`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ipn_return`
--


-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE IF NOT EXISTS `messages` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `project_id` bigint(20) unsigned NOT NULL,
  `from_id` int(11) NOT NULL,
  `to_id` int(11) NOT NULL,
  `message` text CHARACTER SET utf8 NOT NULL,
  `created` int(11) NOT NULL,
  `notification_status` int(11) NOT NULL DEFAULT '0',
  `deluserid` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=39 ;

--
-- Dumping data for table `messages`
--


-- --------------------------------------------------------

--
-- Table structure for table `packages`
--

CREATE TABLE IF NOT EXISTS `packages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `package_name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `start_date` int(11) NOT NULL,
  `end_date` int(11) NOT NULL,
  `isactive` tinyint(4) NOT NULL,
  `total_days` int(11) NOT NULL,
  `created_date` int(11) NOT NULL,
  `updated_date` int(11) NOT NULL,
  `amount` float NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=27 ;

--
-- Dumping data for table `packages`
--


-- --------------------------------------------------------

--
-- Table structure for table `page`
--

CREATE TABLE IF NOT EXISTS `page` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(64) CHARACTER SET utf8 NOT NULL,
  `name` varchar(128) CHARACTER SET utf8 NOT NULL,
  `page_title` varchar(128) CHARACTER SET utf8 NOT NULL,
  `content` text CHARACTER SET utf8 NOT NULL,
  `is_active` tinyint(4) NOT NULL DEFAULT '1',
  `created` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=22 ;

--
-- Dumping data for table `page`
--

INSERT INTO `page` (`id`, `url`, `name`, `page_title`, `content`, `is_active`, `created`) VALUES
(19, 'condition', 'Company & Conditions', 'Company & Conditions', '<p>This is terms and conditions page</p>', 1, 1247560783),
(21, 'privacy', 'Privacy Policy', 'Privacy Policy', '<p>This is the Privacy Policy Contents</p>', 1, 1247762486);

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE IF NOT EXISTS `payments` (
  `id` tinyint(4) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(32) NOT NULL,
  `deposit_description` text NOT NULL,
  `withdraw_description` text NOT NULL,
  `is_deposit_enabled` tinyint(4) NOT NULL DEFAULT '1',
  `is_withdraw_enabled` tinyint(4) NOT NULL DEFAULT '1',
  `deposit_minimum` tinyint(4) NOT NULL,
  `withdraw_minimum` tinyint(4) NOT NULL,
  `mail_id` varchar(128) NOT NULL,
  `url` varchar(255) NOT NULL,
  `commission` float NOT NULL,
  `is_enable` tinyint(1) NOT NULL,
  `url_status` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `title`, `deposit_description`, `withdraw_description`, `is_deposit_enabled`, `is_withdraw_enabled`, `deposit_minimum`, `withdraw_minimum`, `mail_id`, `url`, `commission`, `is_enable`, `url_status`) VALUES
(1, 'paypal', 'Make a deposit using online payment serviyPal accounts are accepted.Make a deposit using online payment serviyPal accounts are accepted.', 'Make a withdrawal using online payment service PayPal.com.', 1, 1, 5, 2, 'a.sath_1236167987_biz@yahoo.co.in', 'https://www.sandbox.paypal.com/cgi-bin/webscri', 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `popular_search`
--

CREATE TABLE IF NOT EXISTS `popular_search` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `keyword` varchar(256) CHARACTER SET utf8 NOT NULL,
  `type` enum('work','user') CHARACTER SET utf8 NOT NULL,
  `created` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=47 ;

--
-- Dumping data for table `popular_search`
--

INSERT INTO `popular_search` (`id`, `keyword`, `type`, `created`) VALUES
(11, '', 'work', 1326842381),
(12, '', 'work', 1326842389),
(13, '', 'work', 1326842447),
(14, '', 'work', 1326842455),
(15, '', 'work', 1326849335),
(16, '', 'work', 1326861386),
(17, '', 'work', 1326861937),
(18, '', 'work', 1326861950),
(19, '', 'work', 1326862111),
(20, '', 'work', 1326862234),
(21, 'tv', 'work', 1326862251),
(22, 'tv', 'work', 1326862268),
(23, '', 'work', 1326862748),
(24, '', 'work', 1326934131),
(25, '', 'work', 1326934144),
(26, '', 'work', 1326934167),
(27, '', 'work', 1326934171),
(28, '', 'work', 1326934178),
(29, '', 'work', 1326934254),
(30, '', 'work', 1326934272),
(31, '', 'work', 1326934289),
(32, 'tv', 'work', 1326934371),
(33, '', 'work', 1326934376),
(34, 'tv', 'work', 1326934382),
(35, '', 'work', 1326934711),
(36, '', 'work', 1326934718),
(37, '', 'work', 1326951293),
(38, '', 'work', 1327037542),
(39, '', 'work', 1327041372),
(40, '', 'work', 1327041380),
(41, '', 'work', 1327041395),
(42, '', 'work', 1327041398),
(43, '', 'work', 1327041402),
(44, '', 'work', 1327041407),
(45, '', 'work', 1327041416),
(46, '', 'work', 1327041429);

-- --------------------------------------------------------

--
-- Table structure for table `portfolio`
--

CREATE TABLE IF NOT EXISTS `portfolio` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `main_img` varchar(200) NOT NULL,
  `description` text NOT NULL,
  `categories` varchar(255) NOT NULL,
  `attachment1` varchar(200) NOT NULL,
  `attachment2` varchar(200) NOT NULL,
  `attachment3` varchar(255) NOT NULL,
  `attachment4` varchar(255) NOT NULL,
  `attachment5` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=37 ;

--
-- Dumping data for table `portfolio`
--


-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

CREATE TABLE IF NOT EXISTS `projects` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `project_name` varchar(255) CHARACTER SET utf8 NOT NULL,
  `project_status` enum('0','1','2','3') CHARACTER SET utf8 NOT NULL,
  `description` text CHARACTER SET utf8 NOT NULL,
  `project_categories` text CHARACTER SET utf8 NOT NULL,
  `budget_min` int(11) unsigned DEFAULT '0',
  `budget_max` int(11) unsigned DEFAULT '0',
  `is_feature` int(1) DEFAULT NULL,
  `is_urgent` int(1) DEFAULT NULL,
  `is_hide_bids` int(1) DEFAULT NULL,
  `creator_id` int(10) unsigned NOT NULL,
  `created` int(11) NOT NULL,
  `enddate` int(11) NOT NULL,
  `seller_id` int(11) NOT NULL,
  `checkstamp` varchar(50) CHARACTER SET utf8 NOT NULL,
  `buyer_rated` enum('0','1') CHARACTER SET utf8 NOT NULL,
  `provider_rated` enum('0','1') CHARACTER SET utf8 NOT NULL,
  `project_paid` enum('0','1') CHARACTER SET utf8 NOT NULL,
  `project_award_date` int(11) NOT NULL,
  `notification_status` int(11) NOT NULL DEFAULT '0',
  `attachment_url` longtext NOT NULL,
  `attachment_name` varchar(60) DEFAULT NULL,
  `is_private` int(11) NOT NULL DEFAULT '0',
  `private_users` text,
  `contact` text NOT NULL,
  `salary` varchar(15) NOT NULL,
  `flag` int(1) NOT NULL,
  `salarytype` varchar(100) NOT NULL,
  `escrow_due` int(11) NOT NULL,
  `number_of_buyers` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=156 ;

--
-- Dumping data for table `projects`
--

INSERT INTO `projects` (`id`, `project_name`, `project_status`, `description`, `project_categories`, `budget_min`, `budget_max`, `is_feature`, `is_urgent`, `is_hide_bids`, `creator_id`, `created`, `enddate`, `seller_id`, `checkstamp`, `buyer_rated`, `provider_rated`, `project_paid`, `project_award_date`, `notification_status`, `attachment_url`, `attachment_name`, `is_private`, `private_users`, `contact`, `salary`, `flag`, `salarytype`, `escrow_due`, `number_of_buyers`) VALUES
(132, 'table 4', '3', 'lorem ipsum dolo sit amet lorem ipsum dolo sit amet lorem ipsum dolo sit amet<br/>', 'Table', 0, 0, 0, 0, 0, 83, 1326861890, 1326965464, 0, '', '0', '0', '0', 0, 0, '', NULL, 0, NULL, '', '', 0, '', 0, 1),
(131, 'table 3', '0', 'lorem ipsum dolo sit amet lorem ipsum dolo sit amet lorem ipsum dolo sit amet<br/>', 'Table', 0, 0, 0, 0, 0, 83, 1326861875, 1327466675, 0, '', '0', '0', '0', 0, 0, '', NULL, 0, NULL, '', '', 0, '', 0, 1),
(130, 'table 2', '0', 'lorem ipsum dolo sit amet lorem ipsum dolo sit amet lorem ipsum dolo sit amet<br/>', 'Table', 0, 0, 0, 0, 0, 83, 1326861861, 1327466661, 0, '', '0', '0', '0', 0, 0, '', NULL, 0, NULL, '', '', 0, '', 0, 1),
(129, 'table 1', '0', 'lorem ipsum dolo sit amet lorem ipsum dolo sit amet lorem ipsum dolo sit amet<br/>', 'Table', 0, 0, 0, 0, 0, 83, 1326861840, 1327466640, 0, '', '0', '0', '0', 0, 0, '', NULL, 0, NULL, '', '', 0, '', 0, 2),
(133, 'smart phone 1', '0', 'lorem ipsum dolo sit amet lorem ipsum dolo sit amet lorem ipsum dolo sit amet<br/>', 'Smart Phone', 0, 0, 0, 0, 0, 83, 1326861966, 1327466766, 0, '', '0', '0', '0', 0, 0, '', NULL, 0, NULL, '', '', 0, '', 0, 2),
(134, 'smart phone 2', '0', 'lorem ipsum dolo sit amet lorem ipsum dolo sit amet lorem ipsum dolo sit amet<br/>', 'Smart Phone', 0, 0, 0, 0, 0, 83, 1326861976, 1327466776, 0, '', '0', '0', '0', 0, 0, '', NULL, 0, NULL, '', '', 0, '', 0, 1),
(135, 'smart phone 3', '0', 'lorem ipsum dolo sit amet lorem ipsum dolo sit amet lorem ipsum dolo sit amet<br/>', 'Smart Phone', 0, 0, 0, 0, 0, 83, 1326861992, 1327466792, 0, '', '0', '0', '0', 0, 0, '', NULL, 0, NULL, '', '', 0, '', 0, 2),
(136, 'smart phone 4', '0', 'lorem ipsum dolo sit amet lorem ipsum dolo sit amet lorem ipsum dolo sit amet<br/>', 'Smart Phone', 0, 0, 0, 0, 0, 83, 1326862011, 1327466811, 0, '', '0', '0', '0', 0, 0, '', NULL, 0, NULL, '', '', 0, '', 0, 2),
(137, 'TV LCD 1', '0', 'lorem ipsum dolo sit amet lorem ipsum dolo sit amet lorem ipsum dolo sit amet<br/>', 'TV', 0, 0, 0, 0, 0, 83, 1326862026, 1327466826, 0, '', '0', '0', '0', 0, 0, '', NULL, 0, NULL, '', '', 0, '', 0, 1),
(138, 'TV LCD 2', '0', 'lorem ipsum dolo sit amet lorem ipsum dolo sit amet lorem ipsum dolo sit amet<br/>', 'TV', 0, 0, 0, 0, 0, 83, 1326862037, 1327466837, 0, '', '0', '0', '0', 0, 0, '', NULL, 0, NULL, '', '', 0, '', 0, 1),
(139, 'TV LCD 3', '0', 'lorem ipsum dolo sit amet lorem ipsum dolo sit amet lorem ipsum dolo sit amet<br/>', 'TV', 0, 0, 0, 0, 0, 83, 1326862050, 1327466850, 0, '', '0', '0', '0', 0, 0, '', NULL, 0, NULL, '', '', 0, '', 0, 1),
(140, 'TV LCD 4', '0', 'lorem ipsum dolo sit amet lorem ipsum dolo sit amet lorem ipsum dolo sit amet<br/>', 'TV', 0, 0, 0, 0, 0, 83, 1326862063, 1327466863, 0, '', '0', '0', '0', 0, 0, '', NULL, 0, NULL, '', '', 0, '', 0, 1),
(141, 'chair 1', '0', 'lorem ipsum dolo sit amet lorem ipsum dolo sit amet lorem ipsum dolo sit amet<br/>', 'chair', 0, 0, 0, 0, 0, 83, 1326862503, 1327467303, 0, '', '0', '0', '0', 0, 0, '', NULL, 0, NULL, '', '', 0, '', 0, 1),
(142, 'chair 2', '0', 'lorem ipsum dolo sit amet lorem ipsum dolo sit amet lorem ipsum dolo sit amet<br/>', 'chair', 0, 0, 0, 0, 0, 83, 1326862516, 1327467316, 0, '', '0', '0', '0', 0, 0, '', NULL, 0, NULL, '', '', 0, '', 0, 1),
(143, 'chair 3', '0', 'lorem ipsum dolo sit amet lorem ipsum dolo sit amet lorem ipsum dolo sit amet<br/>', 'chair', 0, 0, 0, 0, 0, 83, 1326862604, 1327467404, 0, '', '0', '0', '0', 0, 0, '', NULL, 0, NULL, '', '', 0, '', 0, 1),
(144, 'chair 4', '0', 'lorem ipsum dolo sit amet lorem ipsum dolo sit amet lorem ipsum dolo sit amet<br/>', 'chair', 0, 0, 0, 0, 0, 83, 1326862629, 1327467429, 0, '', '0', '0', '0', 0, 0, '', NULL, 0, NULL, '', '', 0, '', 0, 1),
(145, 'bed 1', '0', 'lorem ipsum dolo sit amet lorem ipsum dolo sit amet lorem ipsum dolo sit amet<br/>', 'bed', 0, 0, 0, 0, 0, 83, 1326862666, 1327467466, 0, '', '0', '0', '0', 0, 0, '', NULL, 0, NULL, '', '', 0, '', 0, 1),
(146, 'bed 2', '0', 'lorem ipsum dolo sit amet lorem ipsum dolo sit amet lorem ipsum dolo sit amet<br/>', 'bed', 0, 0, 0, 0, 0, 83, 1326862676, 1327467476, 0, '', '0', '0', '0', 0, 0, '', NULL, 0, NULL, '', '', 0, '', 0, 1),
(147, 'bed 3', '0', 'lorem ipsum dolo sit amet lorem ipsum dolo sit amet lorem ipsum dolo sit amet<br/>', 'bed', 0, 0, 0, 0, 0, 83, 1326862686, 1327467486, 0, '', '0', '0', '0', 0, 0, '', NULL, 0, NULL, '', '', 0, '', 0, 1),
(148, 'bed 4', '0', 'lorem ipsum dolo sit amet lorem ipsum dolo sit amet lorem ipsum dolo sit amet<br/>', 'bed', 0, 0, 0, 0, 0, 83, 1326862695, 1327467495, 0, '', '0', '0', '0', 0, 0, '', NULL, 0, NULL, '', '', 0, '', 0, 2),
(149, 'dvd player 1', '0', 'lorem ipsum dolo sit amet lorem ipsum dolo sit amet lorem ipsum dolo sit amet<br/>', 'DVD player', 0, 0, 0, 0, 0, 83, 1326862706, 1327467506, 0, '', '0', '0', '0', 0, 0, '', NULL, 0, NULL, '', '', 0, '', 0, 1),
(150, 'dvd player 2', '0', 'lorem ipsum dolo sit amet lorem ipsum dolo sit ametlorem ipsum dolo sit amet<br/>', 'DVD player', 0, 0, 0, 0, 0, 83, 1326862719, 1327467519, 0, '', '0', '0', '0', 0, 0, '', NULL, 0, NULL, '', '', 0, '', 0, 1),
(151, 'dvd player 3', '0', 'lorem ipsum dolo sit amet lorem ipsum dolo sit amet lorem ipsum dolo sit amet<br/>', 'DVD player', 0, 0, 0, 0, 0, 83, 1326862729, 1327467529, 0, '', '0', '0', '0', 0, 0, '', NULL, 0, NULL, '', '', 0, '', 0, 1),
(152, 'dvd player 4', '0', 'lorem ipsum dolo sit amet lorem ipsum dolo sit amet lorem ipsum dolo sit amet<br/>', 'DVD player', 0, 0, 0, 0, 0, 83, 1326862739, 1327467539, 0, '', '0', '0', '0', 0, 0, '', NULL, 0, NULL, '', '', 0, '', 0, 2),
(153, 'adsf asdf asdf', '0', 'asdf asdf sadf sadf sadf sdf<br/>', 'bed', 0, 0, 0, 0, 0, 83, 1326930023, 1327534823, 0, '', '0', '0', '0', 0, 0, '', NULL, 0, NULL, '', '', 0, '', 0, 1),
(154, 'asd asd asd assssss', '0', 'asd asd asd asd asd asd asdasdas<br/>', 'bed', 0, 0, 0, 0, 0, 83, 1326930051, 1327534851, 0, '', '0', '0', '0', 0, 0, '', NULL, 0, NULL, '', '', 0, '', 0, 1),
(155, 'xxxczxczxczxczxc', '0', 'zxczxczxc zxc zc zc zxc zxc zxc<br/>', '', 0, 0, 0, 0, 0, 83, 1327037554, 1327642354, 0, '', '0', '0', '0', 0, 0, '', NULL, 0, NULL, '', '', 0, '', 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `projects_preview`
--

CREATE TABLE IF NOT EXISTS `projects_preview` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `project_name` varchar(255) CHARACTER SET utf8 NOT NULL,
  `project_status` enum('0','1','2','3') NOT NULL,
  `description` text CHARACTER SET utf8 NOT NULL,
  `project_categories` text CHARACTER SET utf8 NOT NULL,
  `budget_min` int(11) unsigned DEFAULT '0',
  `budget_max` int(11) unsigned DEFAULT '0',
  `is_feature` int(1) DEFAULT NULL,
  `is_urgent` int(1) DEFAULT NULL,
  `is_hide_bids` int(1) DEFAULT NULL,
  `is_private` int(1) DEFAULT NULL,
  `creator_id` int(10) unsigned NOT NULL,
  `created` int(11) NOT NULL,
  `enddate` int(11) NOT NULL,
  `open_days` int(11) NOT NULL,
  `seller_id` int(11) NOT NULL,
  `project_award_date` int(11) DEFAULT NULL,
  `checkstamp` varchar(50) NOT NULL,
  `buyer_rated` enum('0','1') NOT NULL,
  `provider_rated` enum('0','1') NOT NULL,
  `project_paid` enum('0','1') NOT NULL,
  `CONTACT` text NOT NULL,
  `SALARY` varchar(15) NOT NULL,
  `FLAG` int(1) NOT NULL,
  `SALARYTYPE` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=98 ;

--
-- Dumping data for table `projects_preview`
--


-- --------------------------------------------------------

--
-- Table structure for table `project_cases`
--

CREATE TABLE IF NOT EXISTS `project_cases` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `case_type` varchar(100) CHARACTER SET utf8 NOT NULL,
  `case_reason` varchar(100) CHARACTER SET utf8 NOT NULL,
  `problem_description` varchar(256) CHARACTER SET utf8 NOT NULL,
  `private_comments` varchar(256) CHARACTER SET utf8 NOT NULL,
  `review_type` varchar(100) CHARACTER SET utf8 NOT NULL,
  `payment` int(11) NOT NULL,
  `created` int(11) NOT NULL,
  `parent` int(11) NOT NULL,
  `updates` varchar(256) CHARACTER SET utf8 NOT NULL,
  `status` enum('open','closed') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=49 ;

--
-- Dumping data for table `project_cases`
--


-- --------------------------------------------------------

--
-- Table structure for table `project_invitation`
--

CREATE TABLE IF NOT EXISTS `project_invitation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` varchar(128) NOT NULL,
  `sender_id` varchar(128) NOT NULL,
  `receiver_id` varchar(128) NOT NULL,
  `invite_date` int(11) NOT NULL,
  `notification_status` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=40 ;

--
-- Dumping data for table `project_invitation`
--


-- --------------------------------------------------------

--
-- Table structure for table `rating_hold`
--

CREATE TABLE IF NOT EXISTS `rating_hold` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `rating` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `rating_hold`
--


-- --------------------------------------------------------

--
-- Table structure for table `report_violation`
--

CREATE TABLE IF NOT EXISTS `report_violation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` varchar(128) NOT NULL,
  `project_name` varchar(255) NOT NULL,
  `post_id` varchar(128) NOT NULL,
  `post_name` varchar(128) NOT NULL,
  `comment` text NOT NULL,
  `report_date` int(128) NOT NULL,
  `report_type` varchar(128) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

--
-- Dumping data for table `report_violation`
--


-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE IF NOT EXISTS `reviews` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `comments` varchar(256) CHARACTER SET utf8 NOT NULL,
  `rating` int(11) NOT NULL,
  `review_time` int(11) NOT NULL,
  `review_type` enum('1','2') CHARACTER SET utf8 NOT NULL,
  `project_id` int(11) NOT NULL,
  `buyer_id` int(11) NOT NULL,
  `provider_id` int(11) NOT NULL,
  `hold` enum('0','1') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=23 ;

--
-- Dumping data for table `reviews`
--


-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE IF NOT EXISTS `roles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `role_name` varchar(64) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `role_name`) VALUES
(1, 'buyer'),
(2, 'seller');

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE IF NOT EXISTS `sales` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `refid` varchar(20) NOT NULL DEFAULT '',
  `referral` varchar(128) NOT NULL,
  `account_type` smallint(6) NOT NULL,
  `created_date` date NOT NULL DEFAULT '0000-00-00',
  `signup_date` int(11) NOT NULL,
  `signup_date_format` varchar(50) NOT NULL,
  `created_time` time NOT NULL DEFAULT '00:00:00',
  `browser` varchar(100) NOT NULL DEFAULT '',
  `ipaddress` varchar(20) NOT NULL DEFAULT '',
  `payment` varchar(10) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `sales`
--


-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE IF NOT EXISTS `sessions` (
  `session_id` varchar(40) NOT NULL DEFAULT '0',
  `ip_address` varchar(16) NOT NULL DEFAULT '0',
  `user_agent` varchar(50) NOT NULL,
  `last_activity` int(10) unsigned NOT NULL DEFAULT '0',
  `user_data` text NOT NULL,
  PRIMARY KEY (`session_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sessions`
--


-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE IF NOT EXISTS `settings` (
  `id` int(12) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(100) CHARACTER SET utf8 NOT NULL,
  `name` varchar(255) CHARACTER SET utf8 NOT NULL,
  `setting_type` char(1) CHARACTER SET utf8 NOT NULL,
  `value_type` char(1) CHARACTER SET utf8 NOT NULL,
  `int_value` int(12) DEFAULT NULL,
  `string_value` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `text_value` text CHARACTER SET utf8,
  `created` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=41 ;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `code`, `name`, `setting_type`, `value_type`, `int_value`, `string_value`, `text_value`, `created`) VALUES
(1, 'SITE_TITLE', 'Site Title', 'S', 'S', 0, 'RBS 1.5', NULL, 1255445949),
(2, 'SITE_SLOGAN', 'Site Slogan', 'S', 'S', 0, 'shake your hands with skilled', NULL, 2009),
(3, 'SITE_STATUS', 'Site status', 'S', 'I', 0, '', NULL, 2009),
(4, 'OFFLINE_MESSAGE', 'Offline Message', 'S', 'T', 0, '', 'Updation is going on...we will run this system very soon', 2009),
(9, 'SITE_ADMIN_MAIL', 'Site Admin Mail', 'S', 'S', NULL, '', NULL, 1255445949),
(10, 'PAYMENT_SETTINGS', 'minimum maintanace amount', 'S', 'I', 0, 'initial payment details', NULL, 2009),
(11, 'LANGUAGE_CODE', 'Language', 'S', 'S', NULL, 'english', NULL, 2009),
(12, 'FEATURED_PROJECTS_LIMIT', 'Featured project list', 'S', 'I', 15, NULL, NULL, 2009),
(13, 'URGENT_PROJECTS_LIMIT', 'Urgent Projects list', 'S', 'I', 10, NULL, NULL, 2009),
(14, 'LATEST_PROJECTS_LIMIT', 'Latest Projects list', 'S', 'I', 10, NULL, NULL, 2009),
(15, 'FEATURED_PROJECT_AMOUNT', 'featured project minimum amount', 'S', 'I', 10, NULL, NULL, 2009),
(16, 'URGENT_PROJECT_AMOUNT', 'urgent project minimum', 'S', 'I', 5, NULL, NULL, 2009),
(17, 'HIDE_PROJECT_AMOUNT', 'hide project minimum amount', 'S', 'I', 1, NULL, NULL, 2009),
(19, 'USER_FILE_LIMIT', 'File management', 'S', 'I', 10, NULL, NULL, 2009),
(18, 'PROVIDER_COMMISSION_AMOUNT', 'Provider commission', 'S', 'I', 10, NULL, NULL, 2009),
(20, 'ESCROW_PAGE_LIMIT', 'escrow pagination limit', 'S', 'I', 10, NULL, NULL, 2009),
(21, 'TRANSACTION_PAGE_LIMIT', 'transaction pagination limit', 'S', 'I', 10, NULL, NULL, 2009),
(22, 'MAIL_LIMIT', 'define the mail limit', 'S', 'I', 10, NULL, NULL, 2009),
(23, 'PROJECT_PERIOD', 'project period limit', 'S', 'I', 14, NULL, NULL, 2009),
(24, 'BASE_URL', 'site url', 'S', 'S', NULL, 'http://localhost/rbs/rbs', 'http://localhost/rbs/', 1255445949),
(25, 'UPLOAD_LIMIT', 'Maximum Upload Limit', 'S', 'I', 10, NULL, NULL, 0),
(27, 'HOSTNAME', 'hostname', 'S', 'S', NULL, 'localhost', NULL, 0),
(28, 'TWITTER_USERNAME', 'twitter username', 'S', 'S', NULL, '', NULL, 0),
(29, 'TWITTER_PASSWORD', 'twitter password', 'S', 'S', NULL, '', NULL, 0),
(32, 'PRIVATE_PROJECT_AMOUNT', 'private project amount', 'S', 'I', 11, NULL, NULL, 2009),
(34, 'JOBLISTING_PROJECT_AMOUNT', 'joblisting_project_amount', 'S', 'I', 10, NULL, NULL, 0),
(35, 'FORCED_ESCROW', 'forced escrow', 'S', 'T', 1, NULL, '0', 0),
(36, 'FEATURED_PROJECT_AMOUNT_CM', 'featured_project_amount_cm', 'S', 'I', 2, NULL, NULL, 0),
(37, 'URGENT_PROJECT_AMOUNT_CM', 'urgent_project_amount_cm', 'S', 'I', 2, NULL, NULL, 0),
(38, 'PRIVATE_PROJECT_AMOUNT_CM', 'private_project_amount_cm', 'S', 'I', 2, NULL, NULL, 0),
(39, 'HIDE_PROJECT_AMOUNT_CM', 'hide_project_amount_cm', 'S', 'I', 2, NULL, NULL, 0),
(40, 'JOBLIST_VALIDITY_LIMIT', 'joblist validity limits', 'S', 'I', 20, NULL, NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `subscriptionuser`
--

CREATE TABLE IF NOT EXISTS `subscriptionuser` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` int(25) NOT NULL,
  `package_id` smallint(6) NOT NULL,
  `valid` int(15) NOT NULL,
  `amount` varchar(15) NOT NULL,
  `created` varchar(15) NOT NULL,
  `flag` smallint(6) NOT NULL,
  `updated_date` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=66 ;

--
-- Dumping data for table `subscriptionuser`
--


-- --------------------------------------------------------

--
-- Table structure for table `support`
--

CREATE TABLE IF NOT EXISTS `support` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `callid` varchar(40) NOT NULL,
  `category` int(11) NOT NULL,
  `subject` text NOT NULL,
  `description` longtext NOT NULL,
  `priority` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `user_id` int(11) NOT NULL,
  `reply` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `support`
--


-- --------------------------------------------------------

--
-- Table structure for table `suspend`
--

CREATE TABLE IF NOT EXISTS `suspend` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `suspend_type` varchar(20) NOT NULL,
  `suspend_value` varchar(255) NOT NULL,
  `suspend_time` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `suspend_value` (`suspend_value`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=25 ;

--
-- Dumping data for table `suspend`
--


-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE IF NOT EXISTS `transactions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(64) CHARACTER SET utf8 NOT NULL,
  `creator_id` int(11) NOT NULL DEFAULT '0',
  `buyer_id` int(11) NOT NULL DEFAULT '0',
  `provider_id` bigint(20) NOT NULL DEFAULT '0',
  `transaction_time` int(11) NOT NULL,
  `amount` float NOT NULL,
  `status` char(16) CHARACTER SET utf8 DEFAULT NULL,
  `description` text CHARACTER SET utf8 NOT NULL,
  `paypal_address` varchar(256) NOT NULL,
  `user_type` varchar(256) NOT NULL,
  `reciever_id` varchar(256) NOT NULL,
  `project_id` varchar(256) NOT NULL,
  `package_id` smallint(6) NOT NULL,
  `update_flag` tinyint(14) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=240 ;

--
-- Dumping data for table `transactions`
--


-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `refid` varchar(128) NOT NULL DEFAULT '0',
  `user_name` varchar(128) CHARACTER SET utf8 DEFAULT NULL,
  `name` varchar(128) CHARACTER SET utf8 NOT NULL,
  `role_id` smallint(6) NOT NULL,
  `password` varchar(32) CHARACTER SET utf8 DEFAULT NULL,
  `email` varchar(128) CHARACTER SET utf8 NOT NULL,
  `profile_desc` text CHARACTER SET utf8,
  `user_status` tinyint(4) NOT NULL DEFAULT '0',
  `activation_key` varchar(32) CHARACTER SET utf8 NOT NULL,
  `country_symbol` char(2) CHARACTER SET utf8 NOT NULL,
  `state` varchar(64) CHARACTER SET utf8 DEFAULT NULL,
  `city` varchar(64) CHARACTER SET utf8 DEFAULT NULL,
  `project_notify` char(10) CHARACTER SET utf8 DEFAULT NULL,
  `bid_notify` char(10) CHARACTER SET utf8 DEFAULT NULL,
  `message_notify` char(10) CHARACTER SET utf8 NOT NULL,
  `rate` smallint(6) DEFAULT NULL,
  `logo` varchar(64) CHARACTER SET utf8 DEFAULT NULL,
  `created` int(11) NOT NULL,
  `last_activity` int(11) NOT NULL,
  `user_rating` smallint(2) NOT NULL,
  `num_reviews` int(11) NOT NULL,
  `rating_hold` int(11) NOT NULL,
  `tot_rating` int(11) NOT NULL,
  `suspend_status` enum('0','1') NOT NULL DEFAULT '0',
  `ban_status` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=84 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `refid`, `user_name`, `name`, `role_id`, `password`, `email`, `profile_desc`, `user_status`, `activation_key`, `country_symbol`, `state`, `city`, `project_notify`, `bid_notify`, `message_notify`, `rate`, `logo`, `created`, `last_activity`, `user_rating`, `num_reviews`, `rating_hold`, `tot_rating`, `suspend_status`, `ban_status`) VALUES
(81, '0', 'buyer', 'Buyer', 1, 'e10adc3949ba59abbe56e057f20f883e', 'buyer@gmail.com', '', 1, '', 'US', 'New york', 'New York', '', NULL, '', NULL, NULL, 1326688501, 1327044742, 0, 0, 0, 0, '0', '0'),
(82, '0', 'seller', 'Seller', 2, 'e10adc3949ba59abbe56e057f20f883e', 'seller@gmail.com', '', 1, '', 'US', 'New York', 'New York', '', NULL, '', 0, NULL, 1326688522, 1327056703, 0, 0, 0, 0, '0', '0'),
(83, '0', 'buyer1', 'Buyer', 1, 'e10adc3949ba59abbe56e057f20f883e', 'buyer1@gmail.com', '', 1, '', 'US', 'New York', 'New York', '', NULL, '', NULL, NULL, 1326757940, 1327055977, 0, 0, 0, 0, '0', '0');

-- --------------------------------------------------------

--
-- Table structure for table `user_balance`
--

CREATE TABLE IF NOT EXISTS `user_balance` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `amount` float unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=40 ;

--
-- Dumping data for table `user_balance`
--

INSERT INTO `user_balance` (`id`, `user_id`, `amount`) VALUES
(37, 81, 0),
(38, 82, 0),
(39, 83, 0);

-- --------------------------------------------------------

--
-- Table structure for table `user_categories`
--

CREATE TABLE IF NOT EXISTS `user_categories` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `user_categories` text CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `user_categories`
--


-- --------------------------------------------------------

--
-- Table structure for table `user_contacts`
--

CREATE TABLE IF NOT EXISTS `user_contacts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `msn` varchar(100) CHARACTER SET utf8 NOT NULL,
  `gtalk` varchar(100) CHARACTER SET utf8 NOT NULL,
  `yahoo` varchar(100) CHARACTER SET utf8 NOT NULL,
  `skype` varchar(100) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=28 ;

--
-- Dumping data for table `user_contacts`
--

INSERT INTO `user_contacts` (`id`, `user_id`, `msn`, `gtalk`, `yahoo`, `skype`) VALUES
(25, 82, '', '', '', ''),
(26, 81, '', '', '', ''),
(27, 83, '', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `user_list`
--

CREATE TABLE IF NOT EXISTS `user_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `creator_id` varchar(256) NOT NULL,
  `user_id` varchar(256) NOT NULL,
  `user_name` varchar(256) NOT NULL,
  `user_role` varchar(256) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=32 ;

--
-- Dumping data for table `user_list`
--


-- --------------------------------------------------------

--
-- Table structure for table `want_list`
--

CREATE TABLE IF NOT EXISTS `want_list` (
  `user_id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  PRIMARY KEY (`user_id`,`project_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `want_list`
--

INSERT INTO `want_list` (`user_id`, `project_id`) VALUES
(81, 129),
(81, 133),
(81, 135),
(81, 136),
(81, 148),
(81, 152),
(83, 128),
(83, 129),
(83, 130),
(83, 131),
(83, 132),
(83, 133),
(83, 134),
(83, 135),
(83, 136),
(83, 137),
(83, 138),
(83, 139),
(83, 140),
(83, 141),
(83, 142),
(83, 143),
(83, 144),
(83, 145),
(83, 146),
(83, 147),
(83, 148),
(83, 149),
(83, 150),
(83, 151),
(83, 152),
(83, 153),
(83, 154),
(83, 155);
