SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `rad-access-interface`
--
CREATE DATABASE IF NOT EXISTS `rad-access-interface` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `rad-access-interface`;

-- --------------------------------------------------------

--
-- Table structure for table `bookmarks`
--

CREATE TABLE IF NOT EXISTS `bookmarks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(90) DEFAULT NULL,
  `url` varchar(256) DEFAULT NULL,
  `timestamp` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=14 ;

-- --------------------------------------------------------

--
-- Table structure for table `case_hashtags`
--

CREATE TABLE IF NOT EXISTS `case_hashtags` (
  `case_id` int(11) NOT NULL,
  `hashtag_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`case_id`,`hashtag_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `case_hashtags`
--

INSERT INTO `case_hashtags` (`case_id`, `hashtag_id`, `created_at`, `updated_at`) VALUES
(1, 31, '2013-12-05 02:47:55', '2013-12-05 02:47:55'),
(1, 32, '2013-12-05 03:33:54', '2013-12-05 03:33:54'),
(1, 33, '2013-12-05 03:45:32', '2013-12-05 03:45:32'),
(1, 34, '2013-12-05 03:45:43', '2013-12-05 03:45:43'),
(1, 35, '2013-12-05 03:45:43', '2013-12-05 03:45:43'),
(1, 36, '2013-12-05 03:45:43', '2013-12-05 03:45:43'),
(1, 37, '2013-12-05 03:46:08', '2013-12-05 03:46:08'),
(2, 31, '2013-12-05 02:47:59', '2013-12-05 02:47:59');

-- --------------------------------------------------------

--
-- Table structure for table `hashtags`
--

CREATE TABLE IF NOT EXISTS `hashtags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tag` varchar(45) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tag` (`tag`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=38 ;

--
-- Dumping data for table `hashtags`
--

INSERT INTO `hashtags` (`id`, `tag`, `created_at`, `updated_at`) VALUES
(29, 'test', '2013-12-05 02:39:34', '2013-12-05 02:39:34'),
(30, 'abc', '2013-12-05 02:40:50', '2013-12-05 02:40:50'),
(31, 'hello', '2013-12-05 02:43:43', '2013-12-05 02:43:43'),
(32, 'mmmm', '2013-12-05 03:33:54', '2013-12-05 03:33:54'),
(33, 'test1', '2013-12-05 03:45:32', '2013-12-05 03:45:32'),
(34, 'mike1', '2013-12-05 03:45:43', '2013-12-05 03:45:43'),
(35, 'mike2', '2013-12-05 03:45:43', '2013-12-05 03:45:43'),
(36, 'mike3', '2013-12-05 03:45:43', '2013-12-05 03:45:43'),
(37, 'test2', '2013-12-05 03:46:08', '2013-12-05 03:46:08');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
