SET FOREIGN_KEY_CHECKS=0;
DROP TABLE IF EXISTS Airport;
DROP TABLE IF EXISTS Flight; 
DROP TABLE IF EXISTS User;
DROP TABLE IF EXISTS Favorite;
DROP TABLE IF EXISTS Country; 
DROP VIEW  IF EXISTS Airport_zone; 

-- Table structure for table `Country`
CREATE TABLE Country (
  code     CHAR(3)  NOT NULL PRIMARY KEY,
  name     CHAR(64) NOT NULL,
  timezone TIME     NOT NULL
) ENGINE=InnoDB;
-- Dumping data for table `Country`
-- ORDER BY:  `Code`
INSERT INTO `Country` VALUES ('AFG','Afghanistan',    '4:30' );
INSERT INTO `Country` VALUES ('BEL','Belgium',        '2:00' );
INSERT INTO `Country` VALUES ('BRA','Brazil',         '-3:00');
INSERT INTO `Country` VALUES ('CAN','Canada',         '-7:00');
INSERT INTO `Country` VALUES ('CHE','Switzerland',    '2:00' );
INSERT INTO `Country` VALUES ('DNK','Denmark',        '2:00' );
INSERT INTO `Country` VALUES ('ECU','Ecuador',        '-5:00');
INSERT INTO `Country` VALUES ('ESP','Spain',          '2:00' );
INSERT INTO `Country` VALUES ('FIN','Finland',        '3:00' );
INSERT INTO `Country` VALUES ('FRO','Faroe Islands',  '1:00' );
INSERT INTO `Country` VALUES ('GBR','United Kingdom', '1:00' );
INSERT INTO `Country` VALUES ('IND','India',          '5:30' );
INSERT INTO `Country` VALUES ('JPN','Japan',          '9:00' );
INSERT INTO `Country` VALUES ('MYS','Malaysia',       '8:00' );
INSERT INTO `Country` VALUES ('NOR','Norway',         '2:00' );
INSERT INTO `Country` VALUES ('NZL','New Zealand',    '12:00');
INSERT INTO `Country` VALUES ('THA','Thailand',       '7:00' );
INSERT INTO `Country` VALUES ('TWN','Taiwan',         '8:00' );
INSERT INTO `Country` VALUES ('UKR','Ukraine',        '3:00' );
INSERT INTO `Country` VALUES ('USA','United States',  '-5:00');

-- Table structure for table `Airport`
CREATE TABLE Airport (
  code      char(3)      NOT NULL PRIMARY KEY,
  name      VARCHAR(255) NOT NULL,
  country   char(3)      NOT NULL,
  longitude FLOAT(8,5)   NOT NULL,
  latitude  FLOAT(8,5)   NOT NULL,
  FOREIGN KEY (country)  REFERENCES Country(code) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB;
-- Dumping data for table `Airport`
-- ORDER BY:  `id`
INSERT INTO Airport VALUES ( 'OAI', 'Bagram Airport'                  , 'AFG', 34.946098, 69.264999),
                           ( 'BRU', 'Brussels National Airport'       , 'BEL', 50.901699, 4.494170 ),
                           ( 'BAU', 'Bauru Airport'                   , 'BRA', -22.157801, -49.068298),
                           ( 'YVR', 'Vancouver International Airport' , 'CAN', 49.187199, -123.184998),
                           ( 'BRN', 'Bern Airport'                    , 'CHE', 46.915298, 7.499170),
                           ( 'KRP', 'Karup Airport'                   , 'DNK', 56.299999, 9.116670),
                           ( 'PTZ', 'Pastaza Airport'                 , 'ECU', -1.516670, -78.033302),
                           ( 'EAS', 'San Sebastian Airport'           , 'ESP', 43.357800 , -1.790000),
                           ( 'JYV', 'Jyvaskla Airport'                , 'FIN', 62.399200, 25.681900),
                           ( 'FAE', 'Vagar Airport'                   , 'FRO', 62.063599, -7.277220),
                           ( 'LON', 'London Mean Airport'             , 'GBR', 51.500000, -0.166667),
                           ( 'DEL', 'Delhi Airport'                   , 'IND', 28.573601, 77.100800),
                           ( 'MMJ', 'Matsumoto Airport'               , 'JPN', 36.166698, 137.923004),
                           ( 'SZB', 'Sultan Abdul Aziz Shah Airport'  , 'MYS', 3.130280, 101.551003),
                           ( 'GEN', 'Gardermoen Airport'              , 'NOR', 60.203098, 11.085300),
                           ( 'WLG', 'Wellington International Airport', 'NZL', -41.323898, 174.800995),
                           ( 'UTP', 'U Taphao International Airport'  , 'THA', 12.677800, 101.009003),
                           ( 'TPE', 'Chiang Kai Airport'              , 'TWN', 121.22388, 25.07639),
                           ( 'KBP', 'Kiev, Borispol Airport'          , 'UKR', 50.200001, 30.900000),
                           ( 'DSM', 'Des Moines Muncipal Airport'     , 'USA', 41.533901, -93.656700);

-- Table structure for table `Flight`
CREATE TABLE Flight
(
  id             INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  flight_number  VARCHAR(255) NOT NULL,
  departure      CHAR(3)      NOT NULL,
  destination    CHAR(3)      NOT NULL,
  departure_date DATETIME     NOT NULL,
  arrival_date   DATETIME     NOT NULL,
  price          INT UNSIGNED NOT NULL,
  FOREIGN KEY (departure)     REFERENCES Airport(code) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (destination)   REFERENCES Airport(code) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB;
-- Dumping data for table `Flight`
-- ORDER BY:  `id`
INSERT INTO Flight VALUES
  ( 1, 'SA311', 'TPE', 'DSM', '2014-03-02 10:00', '2014-03-02 17:00', 2000 ),
  ( 2, 'SA312', 'TPE', 'MMJ', '2014-03-02 16:00', '2014-03-02 19:00', 500  ),
  ( 3, 'SA313', 'MMJ', 'DSM', '2014-03-02 22:00', '2014-03-03 04:00', 1500 ),
  ( 4, 'SA310', 'MMJ', 'PTZ', '2014-03-02 22:00', '2014-03-03 04:00', 1000 ),
  ( 5, 'SA309', 'PTZ', 'DSM', '2014-03-03 07:00', '2014-03-03 09:00', 500  ),
  ( 6, 'SB313', 'MMJ', 'DSM', '2014-03-02 20:00', '2014-03-03 02:00', 1500 ),
  ( 7, 'SB310', 'MMJ', 'PTZ', '2014-03-02 20:00', '2014-03-03 02:00', 1000 ),
  ( 8, 'SB309', 'PTZ', 'DSM', '2014-03-03 05:00', '2014-03-03 06:00', 500  );
  
  --    'SA311'
  -- TPE 20hr DSM
  -- 
  --    'SA312'              'SA313'
  -- TPE 2hr  MMJ | 3hr | MMJ 20hr DSM
  -- 
  --    'SA312'              'SA310'              'SA309'
  -- TPE 2hr  MMJ | 3hr | MMJ 18hr PTZ | 3hr | PTZ 2hr DSM
  -- 
  --    'SA312'              'SB313'
  -- TPE 2hr  MMJ | 1hr | MMJ 20hr DSM
  -- 
  --    'SA312'              'SB310'              'SA309'
  -- TPE 2hr  MMJ | 1hr | MMJ 18hr PTZ | 5hr | PTZ 2hr DSM
  -- 
  --    'SA312'              'SA310'              'SB309'
  -- TPE 2hr  MMJ | 3hr | MMJ 18hr PTZ | 1hr | PTZ 2hr DSM

-- Table structure for table `User`
CREATE TABLE User
(
  id       INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  account  VARCHAR(255) NOT NULL,
  password VARCHAR(255) NOT NULL,
  is_admin BOOLEAN      NOT NULL
) ENGINE = InnoDB;
-- Dumping data for table `Favorite`
-- ORDER BY:  `user_id`
INSERT INTO User VALUES ( 1, 'schwannden', md5('0016205'), true ),
                        ( 2, 'ywchen'    , md5('0111042'), true ),
                        ( 3, 'guest'     , md5('guest')  , false);

-- Table structure for table `Favorite`
CREATE TABLE Favorite
(
  user_id   INT UNSIGNED NOT NULL,
  flight_id INT UNSIGNED NOT NULL,
  PRIMARY KEY( user_id, flight_id ),
  FOREIGN KEY (user_id)   REFERENCES User(id)   ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (flight_id) REFERENCES Flight(id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB;
-- Dumping data for table `Favorite`
-- ORDER BY:  `user_id`
INSERT INTO Favorite VALUES (1, 1),
                            (1, 2),
                            (1, 3),
                            (1, 4),
                            (1, 5),
                            (2, 3),
                            (2, 4);
 
-- View structure for table `Favorite`
CREATE VIEW Airport_zone AS
SELECT Airport.code, Country.timezone
FROM Airport, Country
WHERE Airport.country = Country.code;

SET FOREIGN_KEY_CHECKS=1;
