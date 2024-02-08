-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 08, 2024 at 09:29 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tourist`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id_admin` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id_admin`, `username`, `password`) VALUES
(1, 'admin', '$2y$10$S8NHgCLJuQN8xqHs/Ic1KeM8Rt2big2Wj6xMP7Jd6YBzydmikwPZC');

-- --------------------------------------------------------

--
-- Table structure for table `attractions`
--

CREATE TABLE `attractions` (
  `id_attraction` int(11) NOT NULL,
  `id_organization` int(11) NOT NULL,
  `id_city` int(11) NOT NULL,
  `attraction_name` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `address` varchar(255) NOT NULL,
  `type` enum('Museum','Theater','Statue','Park','Fountain','River','Lake','Square','Cathedral','Tower','Ruin','Palace','Triumphal arch','Beach','Cave','Cliff','Mountain','Hill','Waterfall','Forest','Church','Memorial','Pier','Art gallery','Temple','Theme park','Amusement park','National park','Wildlife reserve','Zoo','Bridge','Catacomb','Basilica','Government building','Landmark','Amphitheatre','Castle','Fortification','Restaurant') NOT NULL,
  `popularity` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attractions`
--

INSERT INTO `attractions` (`id_attraction`, `id_organization`, `id_city`, `attraction_name`, `description`, `address`, `type`, `popularity`) VALUES
(3, 2, 1, 'Château de Versaillesent', 'The Château de Versailles, often referred to simply as Versailles, is a symbol of the absolute monarchy of the Ancien Régime in France. The palace was originally built as a hunting lodge by Louis XIII in the early 17th century but was transformed into a magnificent royal residence by his son, Louis XIV, who moved the French court from Paris to Versailles in 1682.', '48.804806, 2.120333', 'Cathedral', 0),
(4, 2, 1, 'Eiffel Tower', 'The Eiffel Tower, an iconic emblem of Paris and a marvel of iron engineering, stands majestically along the Champ de Mars on the banks of the Seine River. Completed in 1889 as the entrance arch for the 1889 World\'s Fair, which celebrated the centennial of the French Revolution, the tower was initially met with a mix of admiration and controversy.', '48.858222, 2.2945 ', 'Tower', 5),
(5, 2, 1, 'Louvre Museum', 'The Louvre Museum, located in the heart of Paris along the right bank of the Seine River, is not only one of the world\'s largest and most visited art museums but also a historic monument in itself. Its rich history, architectural grandeur, and unparalleled art collection make it a cultural treasure trove.', '48.861111, 2.335833 ', 'Museum', 15),
(50, 2, 1, 'Notre-Dame Cathedral', 'Notre Dame in Paris is one of the most iconic Cathedrals in Europe.', '48.853, 2.3498 ', 'Cathedral', 0),
(51, 2, 1, 'Arc de Triomphe', 'The Arc de Triomphe de l\'Étoile is one of the most famous monuments in Paris, France, standing at the western end of the Champs-Élysées at the centre of Place Charles de Gaulle.', '48.873778, 2.295028 ', 'Triumphal arch', 0),
(52, 2, 1, 'Catacombs of Paris', 'Underground ossuaries in Paris, France, which hold the remains of more than six million people.', '48.833889, 2.332222 ', 'Catacomb', 0),
(53, 2, 1, 'Palais Garnier', 'The Palais Garnier has been called \"probably the most famous opera house in the world, a symbol of Paris like Notre Dame Cathedral, the Louvre, or the Sacré Coeur Basilica\".', '48.871944, 2.331667 ', 'Amusement park', 0),
(54, 2, 1, 'Sacré-Cœur', 'Roman Catholic church and minor basilica in Paris dedicated to the Sacred Heart of Jesus. It was formally approved as a national historic monument by the National Commission of Patrimony and Architecture on December 8, 2022.', '48.88665, 2.34295 ', 'Basilica', 0),
(55, 3, 9, 'Hungarian Parliament Building', 'Translates to &quot;House of the Country&quot; or &quot;House of the Nation&quot;), also known as the Parliament of Budapest after its location,[5] is the seat of the National Assembly of Hungary.', '47.506944, 19.045556 ', 'Government building', 0),
(56, 3, 9, 'Heroes\' Square', 'Hősök tere  is one of the major squares in Budapest, Hungary, noted for its iconic Millennium Monument with statues featuring the Seven chieftains of the Magyars and other important Hungarian national leaders, as well as the Memorial Stone of Heroes, often erroneously referred as the Tomb of the Unknown Soldier. The square lies at the outbound end of Andrássy Avenue next to City Park (Városliget).', '47.515, 19.077778 ', 'Square', 0),
(57, 3, 9, 'St. Stephen\'s Basilica', 'The site was the location of the Hetz-Theater, noted for hosting animal fights. János Zitterbarth of the newly formed district built a temporary church there. In the late 1810s, about a thousand people formed the Lipótváros Parish and began fundraising and making plans for the future church.', '47.500833, 19.053889 ', 'Basilica', 0),
(58, 3, 9, 'Fisherman\'s Bastion', 'The Halászbástya or Fisherman&#039;s Bastion is one of the best known monuments in Budapest, located near the Buda Castle, in the 1st district of Budapest. It is one of the most important tourist attractions due to the unique panorama of Budapest from the Neo-Romanesque lookout terraces.', '47.5027, 19.0344 ', 'Fortification', 0),
(59, 3, 9, 'Széchenyi Chain Bridge', 'The Széchenyi Chain Bridge is a chain bridge that spans the River Danube between Buda and Pest, the western and eastern sides of Budapest, the capital of Hungary.', '47.498889, 19.043611 ', 'Bridge', 0),
(60, 3, 9, 'Buda Castle', 'Buda Castle (Hungarian: Budavári Palota, German: Burgpalast) is the historical castle and palace complex of the Hungarian Kings in Budapest. It was first completed in 1265, although the massive Baroque palace today occupying most of the site was built between 1749 and 1769.', '47.496111, 19.039722 ', 'Castle', 1),
(61, 6, 5, 'Schloss Charlottenburg', 'Schloss Charlottenburg (Charlottenburg Palace) is a Baroque palace in Berlin, located in Charlottenburg, a district of the Charlottenburg-Wilmersdorf borough.', '52.5209, 13.2957 ', 'Palace', 1),
(62, 6, 5, 'Reichstag', 'The Reichstag  a historic legislative government building on Platz der Republik in Berlin, is the seat of the German Bundestag. \r\nThe Neo-Renaissance building was built between 1884 and 1894 in the Tiergarten district on the left bank of the River Spree to plans by the architect Paul Wallot.', '52.518611, 13.376111 ', 'Government building', 0),
(63, 6, 5, 'Brandenburg Gate', 'The Brandenburg Gate is an 18th-century neoclassical monument in Berlin, built on the orders of the King of Prussia Frederick William II after restoring the Orangist power by suppressing the Dutch popular unrest.', '52.5163, 13.3777 ', 'Landmark', 0),
(64, 9, 4, 'Colosseum', 'The Colosseum (/ˌkɒləˈsiːəm/ KOL-ə-SEE-əm; Italian: Colosseo [kolosˈsɛːo]) is an elliptical amphitheatre in the centre of the city of Rome, Italy, just east of the Roman Forum. It is the largest ancient amphitheatre ever built, and is still the largest standing amphitheatre in the world, despite its age.', '41.890278, 12.492222 ', 'Amphitheatre', 0),
(65, 9, 4, 'Trevi Fountain', 'The Trevi Fountain is an 18th-century fountain in the Trevi district in Rome, Italy, designed by Italian architect Nicola Salvi and completed by Giuseppe Pannini in 1762 and several others.', '41.900833, 12.483056 ', 'Fountain', 0),
(66, 9, 4, 'Pantheon', 'The Pantheon is a former Roman temple and, since AD 609, a Catholic church (Basilica Santa Maria ad Martyres or Basilica of St. Mary and the Martyrs) in Rome, Italy. The building is round in plan, except for the portico with large granite Corinthian columns (eight in the first rank and two groups of four behind) under a pediment.', '41.8986, 12.4768 ', 'Church', 0),
(67, 28, 27, 'Hortobágy National Park', 'Hortobágy  is an 800 km2 national park in eastern Hungary, rich with folklore and cultural history. The park, a part of the Alföld (Great Plain), was designated as a national park in 1973 (the first in Hungary), and elected among the World Heritage Sites in 1999. The Hortobágy is Hungary\'s largest protected area, and the largest semi-natural grassland in Europe.', '47.6, 21.1 ', 'National park', 0),
(68, 28, 27, 'Reformed Great Church of Debrecen', 'The Reformed Great Church or Great Reformed Church in Debrecen (Hungarian: debreceni református nagytemplom) is located in the city of Debrecen. It stands in the city centre, between Kossuth square and Kálvin square. It is the symbol of the Protestant Church in Hungary, and it is because of this church that Debrecen is sometimes referred to as \"the Calvinist Rome\". With a ground space of 1500 m² it is the largest Protestant church in Hungary. It also has the largest bell of all Hungarian Protestant churches. The Great Church was built between 1805 and 1824 in the neoclassical style.', '47.531944, 21.623889 ', 'Forest', 0),
(69, 7, 6, 'Prado Museum', 'asd', '40.413889, -3.692222', 'Museum', 0),
(70, 7, 6, 'Royal Palace of Madrid', '~ˇ^˘&deg;&deg;˛``˙&acute;˝&uml;\r\n\\|&Auml;&euro;&Iacute;\r\n&#039;&quot;+!%/=()&Ouml;&Uuml;                                       ;&gt;*&lt;\r\n@&amp;{\r\n @ { &amp;df', '40.418056, -3.714167', 'Palace', 0);

-- --------------------------------------------------------

--
-- Table structure for table `attractions_images`
--

CREATE TABLE `attractions_images` (
  `id_attraction` int(11) NOT NULL,
  `id_image` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attractions_images`
--

INSERT INTO `attractions_images` (`id_attraction`, `id_image`) VALUES
(3, 8),
(4, 1),
(4, 2),
(4, 3),
(4, 4),
(5, 5),
(5, 6),
(5, 7),
(50, 116),
(50, 117),
(50, 118),
(51, 119),
(51, 120),
(51, 121),
(51, 122),
(51, 123),
(52, 124),
(52, 125),
(52, 126),
(53, 127),
(53, 128),
(53, 129),
(54, 130),
(54, 131),
(54, 132),
(54, 133),
(55, 134),
(55, 135),
(55, 136),
(55, 137),
(55, 138),
(55, 139),
(56, 140),
(56, 141),
(56, 142),
(56, 143),
(56, 144),
(56, 145),
(56, 146),
(57, 147),
(57, 148),
(57, 149),
(57, 150),
(57, 151),
(57, 152),
(58, 153),
(58, 154),
(58, 155),
(58, 156),
(58, 157),
(58, 158),
(59, 159),
(59, 160),
(59, 161),
(59, 162),
(59, 163),
(59, 164),
(60, 165),
(60, 166),
(61, 167),
(61, 168),
(61, 169),
(61, 170),
(61, 171),
(61, 172),
(62, 173),
(62, 174),
(62, 175),
(62, 176),
(62, 177),
(62, 178),
(63, 179),
(63, 180),
(63, 181),
(63, 182),
(63, 183),
(63, 184),
(64, 185),
(64, 186),
(64, 187),
(64, 188),
(64, 189),
(65, 190),
(65, 191),
(65, 192),
(65, 193),
(65, 194),
(65, 195),
(65, 196),
(66, 197),
(66, 198),
(66, 199),
(66, 200),
(67, 201),
(67, 202),
(67, 203),
(67, 204),
(68, 205),
(69, 206),
(69, 207),
(70, 208),
(70, 209),
(70, 210);

-- --------------------------------------------------------

--
-- Table structure for table `bad_words`
--

CREATE TABLE `bad_words` (
  `id_bad_word` int(11) NOT NULL,
  `id_comment` int(11) NOT NULL,
  `word` varchar(30) NOT NULL,
  `number` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bad_words`
--

INSERT INTO `bad_words` (`id_bad_word`, `id_comment`, `word`, `number`) VALUES
(1, 1, 'kill', 1),
(2, 1, 'destroy', 1),
(3, 2, 'kill', 1),
(4, 2, 'destroy', 1),
(5, 13, 'kill', 2),
(6, 21, 'kill', 1),
(7, 21, 'destroy', 1),
(8, 22, 'kill', 1),
(9, 22, 'destroy', 1),
(10, 22, 'noob', 1),
(11, 22, 'poop', 2),
(12, 22, 'butt', 1),
(13, 22, 'asshole', 1),
(14, 23, 'kill', 2),
(15, 23, 'vts', 2),
(16, 23, 'shit', 2),
(17, 23, 'noob', 2),
(18, 23, 'butt', 2),
(19, 23, 'asshole', 2),
(20, 24, 'kill', 1),
(21, 24, 'vts', 1),
(22, 24, 'shit', 1),
(23, 24, 'noob', 1),
(24, 24, 'butt', 1),
(25, 24, 'asshole', 1),
(26, 25, 'kill', 1),
(27, 25, 'vts', 1),
(28, 25, 'shit', 1),
(29, 25, 'noob', 1),
(30, 25, 'butt', 1),
(31, 25, 'asshole', 1),
(32, 31, 'kill', 1),
(33, 32, 'drop', 1),
(34, 33, 'drop', 1),
(35, 33, 'kill', 1),
(36, 34, 'kill', 2),
(37, 34, 'shit', 1),
(38, 34, 'noob', 1),
(39, 35, 'kill', 1);

-- --------------------------------------------------------

--
-- Table structure for table `cities`
--

CREATE TABLE `cities` (
  `id_city` int(11) NOT NULL,
  `city_name` varchar(100) NOT NULL,
  `country` enum('France','Sweden','Serbia','Hungary','United Kingdom','Italy','Germany','Spain','Greece','Netherlands','Ireland','Bulgaria','Slovakia','Albania','Andorra','Armenia','Austria','Azerbaijan','Belarus','Belgium','Bosnia and Herzegovina','Croatia','Cyprus','Czechia','Denmark','Estonia','Finland','Georgia','Iceland','Kazakhstan','Kosovo','Latvia','Liechtenstein','Lithuania','Luxembourg','Malta','Moldova','Monaco','Montenegro','North Macedonia','Norway','Poland','Portugal','Russia','San Marino','Slovenia','Switzerland','Turkey','Ukraine','Vatican City','Mexico','United States','Canada','South Korea','Japan','China','Mongolia','Cuba') NOT NULL,
  `city_image` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cities`
--

INSERT INTO `cities` (`id_city`, `city_name`, `country`, `city_image`) VALUES
(1, 'Paris', 'France', 'db_images/city_images/paris.jpg'),
(2, 'Belgrade', 'Serbia', 'db_images/city_images/belgrade.jpg'),
(3, 'London', 'United Kingdom', 'db_images/city_images/london.jpg'),
(4, 'Rome', 'Italy', 'db_images/city_images/city_image-65b75eaa946475.07619178-76.jpg'),
(5, 'Berlin', 'Germany', 'db_images/city_images/berlin.jpg'),
(6, 'Madrid', 'Spain', 'db_images/city_images/madrid.jpg'),
(7, 'Athens', 'Greece', 'db_images/city_images/athens.jpg'),
(8, 'Amsterdam', 'Netherlands', 'db_images/city_images/amsterdam.jpg'),
(9, 'Budapest', 'Hungary', 'db_images/city_images/budapest.jpg'),
(10, 'Dublin', 'Greece', 'db_images/city_images/dublin.jpg'),
(11, 'Stockholm', 'Sweden', 'db_images/city_images/stockholm.jpg'),
(12, 'Subotica', 'Serbia', 'db_images/city_images/subotica.jpg'),
(13, 'Lyon', 'France', 'db_images/city_images/lyon.jpg\r\n'),
(22, 'Barcelona', 'Spain', 'db_images/city_images/city_image-65b7dd9b310e42.47752384-54.jpg'),
(26, 'Tokyo', 'Japan', 'db_images/city_images/city_image-65b9383634ab19.46837887-Tokyo-53.jpg'),
(27, 'Debrecen', 'Hungary', 'db_images/city_images/city_image-65b966e257f255.77738596-Debrecen-26.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id_comment` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_attraction` int(11) NOT NULL,
  `comment` varchar(200) NOT NULL,
  `filtered_comment` varchar(200) NOT NULL,
  `total_bad_words` int(11) NOT NULL,
  `total_words` int(11) NOT NULL,
  `bad_level` int(11) NOT NULL,
  `date_time` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id_comment`, `id_user`, `id_attraction`, `comment`, `filtered_comment`, `total_bad_words`, `total_words`, `bad_level`, `date_time`) VALUES
(1, 3, 3, 'i really love this kill killer destroy attraction', 'i really love this k**l k**ler d*****y attraction', 2, 8, 3, '2024-01-29 14:56:20'),
(2, 3, 3, 'i really love this kill killer destroy attraction', 'i really love this k**l k**ler d*****y attraction', 2, 8, 3, '2024-01-29 14:57:25'),
(3, 3, 3, 'asd', 'asd', 0, 1, 0, '2024-01-29 15:26:30'),
(4, 3, 3, 'noob', 'noob', 0, 1, 0, '2024-01-29 15:28:24'),
(5, 3, 3, 'sdsd', 'sdsd', 0, 1, 0, '2024-01-29 15:29:30'),
(6, 3, 3, 'csscscs', 'csscscs', 0, 1, 0, '2024-01-29 15:32:10'),
(7, 3, 4, 'asdasdas', 'asdasdas', 0, 1, 0, '2024-01-29 15:34:28'),
(8, 3, 3, 'newc', 'newc', 0, 1, 0, '2024-01-29 15:36:45'),
(9, 3, 3, 'dfsdfsdfsdf', 'dfsdfsdfsdf', 0, 1, 0, '2024-01-29 15:38:50'),
(10, 3, 3, 'asdaasdasdasd', 'asdaasdasdasd', 0, 1, 0, '2024-01-29 15:41:41'),
(11, 3, 3, 'asdasdasdasd', 'asdasdasdasd', 0, 1, 0, '2024-01-29 15:41:56'),
(12, 3, 3, 'very coll works', 'very coll works', 0, 3, 0, '2024-01-29 15:43:12'),
(14, 3, 3, 'new comment\r\n', 'new comment', 0, 2, 0, '2024-01-29 21:52:03'),
(15, 3, 3, 'WWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAADDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFF', 'wwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaddddddddddddddddddddddddddddddddddddddddddddddddddffffffffffffffffffffffffffffffffffff', 0, 1, 0, '2024-01-29 21:55:07'),
(16, 3, 3, 'Bonkers me love me some good ol  palasses in ye olde parii', 'bonkers me love me some good ol palasses in ye olde parii', 0, 12, 0, '2024-01-29 22:03:38'),
(17, 3, 5, 'heehe', 'heehe', 0, 1, 0, '2024-01-29 22:13:08'),
(18, 3, 4, 'asd', 'asd', 0, 1, 0, '2024-01-30 10:16:43'),
(19, 3, 4, 'asdasdasd', 'asdasdasd', 0, 1, 0, '2014-04-02 10:17:40'),
(26, 3, 63, 'lovin it\r\n', 'lovin it', 0, 2, 0, '2024-01-31 00:03:02'),
(27, 3, 63, 'asdasdasd', 'asdasdasd', 0, 1, 0, '2024-01-31 12:29:17'),
(28, 3, 60, 'mukdoje xDD', 'mukdoje xdd', 0, 2, 0, '2024-02-01 17:42:15'),
(29, 3, 60, 'I LOVE THIS', 'i love this', 0, 3, 0, '2024-02-01 17:45:45'),
(30, 3, 60, 'werking\r\nwerking\r\n', 'werking werking', 0, 2, 0, '2024-02-01 17:52:03'),
(31, 3, 60, 'this is my longest comment in this year kill', 'this is my longest comment in this year k**l', 1, 9, 2, '2024-02-01 17:55:17'),
(32, 3, 60, '\r\n\r\nthis is my longest comment in this drop k**l\r\n', 'this is my longest comment in this d**p k l', 1, 10, 2, '2024-02-01 17:56:54'),
(33, 3, 60, '\r\n\r\nthis is my longest comment in the drop kill \r\n', 'this is my longest comment in the d**p k**l', 2, 9, 3, '2024-02-01 17:57:10'),
(35, 3, 60, 'trevi trevi iititia kadkk kldl ia kill asdkk a', 'trevi trevi iititia kadkk kldl ia k**l asdkk a', 1, 9, 2, '2024-02-01 18:09:43');

-- --------------------------------------------------------

--
-- Table structure for table `favourite_attractions`
--

CREATE TABLE `favourite_attractions` (
  `id_favourite` int(11) NOT NULL,
  `id_attraction` int(11) NOT NULL,
  `id_user` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `favourite_attractions`
--

INSERT INTO `favourite_attractions` (`id_favourite`, `id_attraction`, `id_user`) VALUES
(71, 51, 3),
(72, 63, 3),
(77, 60, 3),
(80, 61, 3);

-- --------------------------------------------------------

--
-- Table structure for table `images`
--

CREATE TABLE `images` (
  `id_image` int(11) NOT NULL,
  `path` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `images`
--

INSERT INTO `images` (`id_image`, `path`) VALUES
(1, 'db_images/eiffel1.jpg'),
(2, 'db_images/eiffel2.jpg'),
(3, 'db_images/eiffel3.jpg'),
(4, 'db_images/eiffel4.jpg'),
(5, 'db_images/louvre1.jpg'),
(6, 'db_images/louvre2.jpg'),
(7, 'db_images/louvre3.jpg'),
(8, 'db_images/versailles1.jpg'),
(116, 'db_images/img-65b94b0a0a4a10.59336094-140.jpg'),
(117, 'db_images/img-65b94b0a0b6046.83895476-404.jpg'),
(118, 'db_images/img-65b94b0a0c9381.96246397-632.jpg'),
(119, 'db_images/img-65b94bf08207c4.08381615-366.jpg'),
(120, 'db_images/img-65b94bf0833860.39875429-501.jpg'),
(121, 'db_images/img-65b94bf08496d0.47188007-819.jpg'),
(122, 'db_images/img-65b94bf085ead4.01552510-819.jpg'),
(123, 'db_images/img-65b94bf0877500.11536509-964.jpg'),
(124, 'db_images/img-65b94eb43562d6.56893723-263.jpg'),
(125, 'db_images/img-65b94eb4370083.16260652-774.jpg'),
(126, 'db_images/img-65b94eb4389091.60460482-154.jpg'),
(127, 'db_images/img-65b94f53722323.49312047-557.jpg'),
(128, 'db_images/img-65b94f537382d6.82741958-147.jpg'),
(129, 'db_images/img-65b94f53750382.00739893-802.jpg'),
(130, 'db_images/img-65b9503712dbc9.01118254-982.jpg'),
(131, 'db_images/img-65b9503713e1b6.35467945-408.jpg'),
(132, 'db_images/img-65b9503714f8b4.26476970-359.jpg'),
(133, 'db_images/img-65b950371615a8.79874148-162.jpg'),
(134, 'db_images/img-65b954e0d992a5.17875888-579.jpg'),
(135, 'db_images/img-65b954e0da13a0.91194447-641.jpg'),
(136, 'db_images/img-65b954e0dabcb9.36669000-406.jpg'),
(137, 'db_images/img-65b954e0db5e96.11635391-588.jpg'),
(138, 'db_images/img-65b954e0dc8ad6.13297477-838.jpg'),
(139, 'db_images/img-65b954e0dce873.22117027-531.jpg'),
(140, 'db_images/img-65b955657291a7.44821479-251.jpg'),
(141, 'db_images/img-65b9556573f2a5.03519138-472.jpg'),
(142, 'db_images/img-65b955657557d0.81046478-284.jpg'),
(143, 'db_images/img-65b9556576f681.49098441-250.jpg'),
(144, 'db_images/img-65b9556577f020.57808976-135.jpg'),
(145, 'db_images/img-65b9556578d394.44103051-167.jpg'),
(146, 'db_images/img-65b95565799cd5.16495334-434.jpg'),
(147, 'db_images/img-65b9561c742996.92943116-456.jpg'),
(148, 'db_images/img-65b9561c74b898.45522776-366.jpg'),
(149, 'db_images/img-65b9561c7563d7.92035210-170.jpg'),
(150, 'db_images/img-65b9561c75c8e9.23076581-366.jpg'),
(151, 'db_images/img-65b9561c7661c6.27357229-151.jpg'),
(152, 'db_images/img-65b9561c76be08.61856422-143.jpg'),
(153, 'db_images/img-65b956b7cea131.98340038-655.jpg'),
(154, 'db_images/img-65b956b7cf7e47.99474326-331.jpg'),
(155, 'db_images/img-65b956b7d03993.72195024-450.jpg'),
(156, 'db_images/img-65b956b7d0aa00.25801216-248.jpg'),
(157, 'db_images/img-65b956b7d143c6.91432247-267.jpg'),
(158, 'db_images/img-65b956b7d1ec61.91712360-932.jpg'),
(159, 'db_images/img-65b9572ad49760.89636786-709.jpg'),
(160, 'db_images/img-65b9572ad572c7.79193669-467.jpg'),
(161, 'db_images/img-65b9572ad6d6d7.16054436-524.jpg'),
(162, 'db_images/img-65b9572ad745c7.25593818-470.jpg'),
(163, 'db_images/img-65b9572ad7ebf5.03026307-713.jpg'),
(164, 'db_images/img-65b9572ad85a02.22092560-626.jpg'),
(165, 'db_images/img-65b957ec45cc34.00840206-990.jpg'),
(166, 'db_images/img-65b957ec46cea2.96387641-127.jpg'),
(167, 'db_images/img-65b9587b469858.75643210-286.jpg'),
(168, 'db_images/img-65b9587b474ad1.71878521-861.jpg'),
(169, 'db_images/img-65b9587b47ad20.48828629-267.jpg'),
(170, 'db_images/img-65b9587b4850d6.19463904-660.jpg'),
(171, 'db_images/img-65b9587b492f20.62078634-703.jpg'),
(172, 'db_images/img-65b9587b499061.73209631-414.jpg'),
(173, 'db_images/img-65b95950540b94.46460636-543.jpg'),
(174, 'db_images/img-65b95950552d51.32415941-281.jpg'),
(175, 'db_images/img-65b9595056f104.11063398-302.jpg'),
(176, 'db_images/img-65b9595058e191.09359517-223.jpg'),
(177, 'db_images/img-65b959505a09f6.07874343-136.jpg'),
(178, 'db_images/img-65b959505b1720.51948913-885.jpg'),
(179, 'db_images/img-65b959c3e349b7.84185010-645.jpg'),
(180, 'db_images/img-65b959c3e4ef13.17413454-830.jpg'),
(181, 'db_images/img-65b959c3e64ca1.27543309-993.jpg'),
(182, 'db_images/img-65b959c3e75508.08113385-751.jpg'),
(183, 'db_images/img-65b959c3e8f068.89434375-919.jpg'),
(184, 'db_images/img-65b959c3e9fa02.31571265-331.jpg'),
(185, 'db_images/img-65b95ab657fba1.44409990-700.jpg'),
(186, 'db_images/img-65b95ab658c2d2.99484980-709.jpg'),
(187, 'db_images/img-65b95ab659a236.75312987-324.jpg'),
(188, 'db_images/img-65b95ab65a4cf4.44704277-337.jpg'),
(189, 'db_images/img-65b95ab65b1e65.59409075-656.jpg'),
(190, 'db_images/img-65b95bb1880615.35692111-482.jpg'),
(191, 'db_images/img-65b95bb18967d0.09684271-240.jpg'),
(192, 'db_images/img-65b95bb18a6668.85384541-524.jpg'),
(193, 'db_images/img-65b95bb18befa4.00772298-663.jpg'),
(194, 'db_images/img-65b95bb18d16e8.15514045-613.jpg'),
(195, 'db_images/img-65b95bb18e8964.51699907-883.jpg'),
(196, 'db_images/img-65b95bb18f3ec9.35736311-148.jpg'),
(197, 'db_images/img-65b95c3cd76e89.09468154-654.jpg'),
(198, 'db_images/img-65b95c3cd83991.87195475-405.jpg'),
(199, 'db_images/img-65b95c3cd90515.65790068-692.jpg'),
(200, 'db_images/img-65b95c3cd975e7.21391550-525.jpg'),
(201, 'db_images/img-65b96cf29eb1b0.93125250-881.jpg'),
(202, 'db_images/img-65b96cf29fe311.63377458-485.jpg'),
(203, 'db_images/img-65b96cf2a14036.90564269-279.jpg'),
(204, 'db_images/img-65b96cf2a2c488.26864370-678.jpg'),
(205, 'db_images/img-65b96d68dfb543.15926632-323.jpg'),
(206, 'db_images/img-65bb6e26a568d9.64895987-947.jpg'),
(207, 'db_images/img-65bb6e26a6aa51.33925177-267.jpg'),
(208, 'db_images/img-65bdf96d84c028.44820008-616.jpg'),
(209, 'db_images/img-65bdf96d861f20.37060315-677.jpg'),
(210, 'db_images/img-65bdf96d8781d8.97787496-483.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `organizations`
--

CREATE TABLE `organizations` (
  `id_organization` int(11) NOT NULL,
  `id_city` int(11) NOT NULL,
  `email` varchar(120) NOT NULL,
  `password` varchar(60) NOT NULL,
  `org_name` varchar(60) NOT NULL,
  `is_banned` tinyint(11) NOT NULL DEFAULT 0,
  `date_time` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `organizations`
--

INSERT INTO `organizations` (`id_organization`, `id_city`, `email`, `password`, `org_name`, `is_banned`, `date_time`) VALUES
(2, 1, 'paris@org.com', '$2y$10$bMgOv0mWNSpE.q3s1jrpQu/Vm8/dLXWlzBPxSn1jxqVyPxc.rQFJC', 'ParisORG', 0, '2024-01-29 18:47:24'),
(3, 9, 'budapest@org.com', '$2y$10$bMgOv0mWNSpE.q3s1jrpQu/Vm8/dLXWlzBPxSn1jxqVyPxc.rQFJC', 'BudapestORG', 0, '2024-01-30 22:44:29'),
(4, 3, 'london@org.com', '$2y$10$bMgOv0mWNSpE.q3s1jrpQu/Vm8/dLXWlzBPxSn1jxqVyPxc.rQFJC', 'LondonORG', 0, '2024-01-28 09:41:00'),
(6, 5, 'berlin@org.com', '$2y$10$bMgOv0mWNSpE.q3s1jrpQu/Vm8/dLXWlzBPxSn1jxqVyPxc.rQFJC', 'BerlinORG', 0, '2024-01-29 18:48:59'),
(7, 6, 'madrid@org.com', '$2y$10$bMgOv0mWNSpE.q3s1jrpQu/Vm8/dLXWlzBPxSn1jxqVyPxc.rQFJC', 'MadridORG', 0, '2024-01-28 09:41:00'),
(9, 4, 'rome@org.com', '$2y$10$bMgOv0mWNSpE.q3s1jrpQu/Vm8/dLXWlzBPxSn1jxqVyPxc.rQFJC', 'RomeORG', 0, '2024-01-28 09:41:00'),
(11, 13, 'lyon@org.com', '$2y$10$bMgOv0mWNSpE.q3s1jrpQu/Vm8/dLXWlzBPxSn1jxqVyPxc.rQFJC', 'LyonORG', 0, '2024-01-28 11:07:33'),
(28, 27, 'debrecen@org.com', '$2y$10$2CaiRLKWPSxO8icw6Whfs.o2J40HorqUA1hrv4zXZc5T1.KujAY6C', 'DebORG', 0, '2024-01-30 22:16:28'),
(29, 7, 'asdorg@org.com', '$2y$10$Gj2WhtRm9CUlgg8ezukoke0Wxrejx8bxQbg.c7Y62vAz3BbsUJEm6', 'asdorg', 0, '2024-01-31 21:27:54');

-- --------------------------------------------------------

--
-- Table structure for table `tours`
--

CREATE TABLE `tours` (
  `id_tour` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `tour_name` varchar(50) NOT NULL,
  `tour_type` enum('By foot','By bicycle','By vehicle','By boat') NOT NULL,
  `datetime` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tours`
--

INSERT INTO `tours` (`id_tour`, `id_user`, `tour_name`, `tour_type`, `datetime`) VALUES
(6, 3, 'new tour1', 'By bicycle', '2024-01-26 21:48:14'),
(9, 3, 'me net tour', 'By vehicle', '2024-01-26 21:51:29'),
(15, 3, 'budapest', 'By boat', '2024-01-31 15:00:32'),
(18, 10, 'asd', 'By bicycle', '2024-01-31 16:28:29'),
(20, 10, 'est', 'By bicycle', '2024-01-31 16:46:09'),
(21, 10, 'rome', 'By foot', '2024-01-31 16:49:53'),
(22, 10, 'paris', 'By foot', '2024-01-31 18:02:48'),
(23, 3, 'deb', 'By foot', '2024-02-01 12:13:43'),
(25, 3, 'ttt1', 'By foot', '2024-02-03 09:38:31'),
(26, 3, 'tj', 'By vehicle', '2024-02-03 09:39:26');

-- --------------------------------------------------------

--
-- Table structure for table `tours_attractions`
--

CREATE TABLE `tours_attractions` (
  `id_tour` int(11) NOT NULL,
  `id_attraction` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tours_attractions`
--

INSERT INTO `tours_attractions` (`id_tour`, `id_attraction`) VALUES
(6, 3),
(6, 4),
(9, 3),
(9, 4),
(9, 5),
(15, 55),
(15, 56),
(15, 57),
(15, 58),
(15, 59),
(15, 60),
(18, 67),
(18, 68),
(20, 55),
(20, 57),
(20, 58),
(20, 59),
(20, 60),
(21, 64),
(21, 65),
(21, 66),
(22, 3),
(22, 4),
(22, 5),
(22, 50),
(22, 51),
(22, 54),
(23, 67),
(23, 68),
(25, 56),
(25, 58),
(25, 59),
(26, 3),
(26, 4),
(26, 5),
(26, 64),
(26, 65);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id_user` int(11) NOT NULL,
  `email` varchar(120) NOT NULL,
  `password` varchar(60) NOT NULL,
  `firstname` varchar(40) NOT NULL,
  `lastname` varchar(40) NOT NULL,
  `is_banned` tinyint(4) NOT NULL DEFAULT 0,
  `active` tinyint(4) NOT NULL DEFAULT 0,
  `news` tinyint(4) NOT NULL DEFAULT 0,
  `registration_token` varchar(50) NOT NULL,
  `registration_token_expiry` datetime DEFAULT NULL,
  `forgotten_password_token` varchar(50) DEFAULT NULL,
  `forgotten_password_expiry` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id_user`, `email`, `password`, `firstname`, `lastname`, `is_banned`, `active`, `news`, `registration_token`, `registration_token_expiry`, `forgotten_password_token`, `forgotten_password_expiry`) VALUES
(3, 'balinth3@gmail.com', '$2y$10$2T5Ak1P7XUGAqo8xIB6Lb.729MwpNFbLwRYzVSUSU8UrC52AJwENy', 'firstnam1', 'asd', 0, 1, 0, '', '2024-01-25 12:48:32', '', '2024-01-27 20:12:19'),
(10, 'zifolsan3@gmail.com', '$2y$10$uGBvZ0TgRn627mFl/2C7y.D8tj8PgNRS3z5w2QmgvpcRw3Lu2sbja', 'a2', 'a2', 0, 1, 1, '', '2024-01-31 13:18:05', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_email_failures`
--

CREATE TABLE `user_email_failures` (
  `id_user_email_failure` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `message` varchar(200) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_informations`
--

CREATE TABLE `user_informations` (
  `id_information` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `user_agent` varchar(255) NOT NULL,
  `ip_address` varchar(42) NOT NULL,
  `device_type` set('phone','tablet','computer') NOT NULL,
  `country` varchar(128) NOT NULL,
  `proxy` tinyint(4) NOT NULL,
  `date_time` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_informations`
--

INSERT INTO `user_informations` (`id_information`, `id_user`, `user_agent`, `ip_address`, `device_type`, `country`, `proxy`, `date_time`) VALUES
(1, 3, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:122.0) Gecko/20100101 Firefox/122.0', '127.0.0.1', 'computer', '', 0, '2024-02-04 11:09:16');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id_admin`);

--
-- Indexes for table `attractions`
--
ALTER TABLE `attractions`
  ADD PRIMARY KEY (`id_attraction`),
  ADD KEY `id_organization` (`id_organization`),
  ADD KEY `id_city` (`id_city`);

--
-- Indexes for table `attractions_images`
--
ALTER TABLE `attractions_images`
  ADD PRIMARY KEY (`id_attraction`,`id_image`),
  ADD KEY `id_image` (`id_image`);

--
-- Indexes for table `bad_words`
--
ALTER TABLE `bad_words`
  ADD PRIMARY KEY (`id_bad_word`);

--
-- Indexes for table `cities`
--
ALTER TABLE `cities`
  ADD PRIMARY KEY (`id_city`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id_comment`),
  ADD KEY `id_attraction` (`id_attraction`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `favourite_attractions`
--
ALTER TABLE `favourite_attractions`
  ADD PRIMARY KEY (`id_favourite`),
  ADD KEY `id_attraction` (`id_attraction`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `images`
--
ALTER TABLE `images`
  ADD PRIMARY KEY (`id_image`);

--
-- Indexes for table `organizations`
--
ALTER TABLE `organizations`
  ADD PRIMARY KEY (`id_organization`),
  ADD KEY `id_city` (`id_city`);

--
-- Indexes for table `tours`
--
ALTER TABLE `tours`
  ADD PRIMARY KEY (`id_tour`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `tours_attractions`
--
ALTER TABLE `tours_attractions`
  ADD PRIMARY KEY (`id_tour`,`id_attraction`),
  ADD KEY `id_attraction` (`id_attraction`),
  ADD KEY `id_tour` (`id_tour`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`);

--
-- Indexes for table `user_email_failures`
--
ALTER TABLE `user_email_failures`
  ADD PRIMARY KEY (`id_user_email_failure`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `user_informations`
--
ALTER TABLE `user_informations`
  ADD PRIMARY KEY (`id_information`),
  ADD KEY `id_user` (`id_user`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id_admin` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `attractions`
--
ALTER TABLE `attractions`
  MODIFY `id_attraction` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;

--
-- AUTO_INCREMENT for table `bad_words`
--
ALTER TABLE `bad_words`
  MODIFY `id_bad_word` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `cities`
--
ALTER TABLE `cities`
  MODIFY `id_city` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id_comment` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `favourite_attractions`
--
ALTER TABLE `favourite_attractions`
  MODIFY `id_favourite` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=82;

--
-- AUTO_INCREMENT for table `images`
--
ALTER TABLE `images`
  MODIFY `id_image` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=211;

--
-- AUTO_INCREMENT for table `organizations`
--
ALTER TABLE `organizations`
  MODIFY `id_organization` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `tours`
--
ALTER TABLE `tours`
  MODIFY `id_tour` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `user_email_failures`
--
ALTER TABLE `user_email_failures`
  MODIFY `id_user_email_failure` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_informations`
--
ALTER TABLE `user_informations`
  MODIFY `id_information` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `attractions`
--
ALTER TABLE `attractions`
  ADD CONSTRAINT `attractions_ibfk_1` FOREIGN KEY (`id_organization`) REFERENCES `organizations` (`id_organization`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `attractions_ibfk_2` FOREIGN KEY (`id_city`) REFERENCES `cities` (`id_city`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `attractions_images`
--
ALTER TABLE `attractions_images`
  ADD CONSTRAINT `attractions_images_ibfk_1` FOREIGN KEY (`id_attraction`) REFERENCES `attractions` (`id_attraction`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `attractions_images_ibfk_2` FOREIGN KEY (`id_image`) REFERENCES `images` (`id_image`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`id_attraction`) REFERENCES `attractions` (`id_attraction`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `favourite_attractions`
--
ALTER TABLE `favourite_attractions`
  ADD CONSTRAINT `favourite_attractions_ibfk_1` FOREIGN KEY (`id_attraction`) REFERENCES `attractions` (`id_attraction`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `favourite_attractions_ibfk_2` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `organizations`
--
ALTER TABLE `organizations`
  ADD CONSTRAINT `organizations_ibfk_1` FOREIGN KEY (`id_city`) REFERENCES `cities` (`id_city`);

--
-- Constraints for table `tours`
--
ALTER TABLE `tours`
  ADD CONSTRAINT `tours_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tours_attractions`
--
ALTER TABLE `tours_attractions`
  ADD CONSTRAINT `tours_attractions_ibfk_1` FOREIGN KEY (`id_attraction`) REFERENCES `attractions` (`id_attraction`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tours_attractions_ibfk_2` FOREIGN KEY (`id_tour`) REFERENCES `tours` (`id_tour`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_email_failures`
--
ALTER TABLE `user_email_failures`
  ADD CONSTRAINT `user_email_failures_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_informations`
--
ALTER TABLE `user_informations`
  ADD CONSTRAINT `user_informations_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
