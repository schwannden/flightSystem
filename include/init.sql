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
INSERT INTO `Country` VALUES ('TWN', 'Taiwan',             '8:00' );
INSERT INTO `Country` VALUES ('JP' , 'Japan',              '9:00' );
INSERT INTO `Country` VALUES ('UK' , 'United Kingdom',     '0:00' );
INSERT INTO `Country` VALUES ('US' , 'United States',      '-5:00');
INSERT INTO `Country` VALUES ('CN' , 'China',              '8:00');
INSERT INTO `Country` VALUES ('DOH', 'Doha',               '4:00');
INSERT INTO `Country` VALUES ('SIN', 'Singapore',          '8:00');
INSERT INTO `Country` VALUES ('RUS', 'Russian Federation', '4:00');

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
INSERT INTO Airport VALUES ('TPE','Taipei Touyuan International Airport','TWN', 0, 0),
                           ('KHH','kaohsiung International Airport'     ,'TWN', 0, 0),
                           ('TCH','Taichung Airport'                    ,'TWN', 0, 0),
                           ('NGO','Chūbu Centrair International Airport','JP',  0, 0),
                           ('HND','Tokyo International Airport'         ,'JP',  0, 0),
                           ('NRT','Narita International Airport'        ,'JP',  0, 0),
                           ('MAN','Manchester Airport'                  ,'UK',  0, 0),
                           ('LHR','London Heathrow Airport'             ,'UK',  0, 0),
                           ('LTN','London Luton Airport'                ,'UK',  0, 0),
                           ('LCY','London City Airport'                 ,'UK',  0, 0),
                           ('HKG','Hong Kong International Airport'     ,'CN',  0, 0),
                           ('DOH','Doha International Airport'          ,'DOH', 0, 0),
                           ('SIN','Singapore International Airport'     ,'SIN', 0, 0),
                           ('LED','Aeroport Pulkovo'                    ,'RUS', 0, 0);
                           

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
  ( 1,'JP-123','TPE','HND','2014-05-01 10:00:00','2014-05-01 12:00:00','6000'),
  ( 2,'TM-123','TPE','MAN','2014-05-01 10:00:00','2014-05-01 19:00:00','20000'),
  ( 3,'TH-123','TPE','HKG','2014-05-01 10:00:00','2014-05-01 11:30:00','4000'),
  ( 4,'THK-123','TPE','HKG','2014-05-01 13:00:00','2014-05-01 20:22:00','13000'),
  ( 5,'JP-124','TPE','NGO','2014-04-13 09:00:00','2014-04-13 12:27:00','1200'),
  ( 6,'HG-128','TPE','HKG','2014-04-15 06:10:00','2014-04-15 07:41:00','2980'),
  ( 7,'HK-228','KHH','HKG','2014-04-16 14:20:00','2014-04-16 15:51:00','6000'),
  ( 8,'HN-225','KHH','HND','2014-04-10 12:17:00','2014-04-10 16:44:00','3998'),
  ( 9,'HK-328','TCH','HKG','2014-04-10 15:22:00','2014-04-10 16:53:00','3500'),
  (10,'SI-327','TCH','SIN','2014-04-27 10:00:00','2014-04-27 14:10:00','8000'),
  (11,'HN-325','TCH','HND','2014-04-16 14:20:00','2014-04-16 18:27:00','6999'),
  (12,'DH-429','NGO','DOH','2014-04-20 12:00:00','2014-04-20 19:00:00','20000'),
  (13,'LC-413','NGO','LCY','2014-04-20 10:00:00','2014-04-20 14:00:00','21355'),
  (14,'TP-421','NGO','TPE','2014-04-30 17:00:00','2014-04-30 19:27:00','5123'),
  (15,'HK-428','NGO','HKG','2014-04-21 11:07:00','2014-04-21 14:07:00','6543'),
  (16,'JPM-123','HND','MAN','2014-05-01 14:00:00','2014-05-01 23:00:00','15000'),
  (17,'JHK-123','HND','HKG','2014-05-01 15:08:00','2014-05-01 19:00:00','12000'),
  (18,'TP-521','HND','TPE','2014-04-24 10:00:00','2014-04-24 13:07:00','1630'),
  (19,'NR-526','HND','NRT','2014-04-15 10:15:00','2014-04-15 11:16:00','1980'),
  (20,'KH-722','SIN','KHH','2014-04-29 16:13:00','2014-04-29 20:23:00','12377'),
  (21,'JP-725','SIN','HND','2014-04-17 06:00:00','2014-04-17 13:00:00','4830'),
  (22,'HK-728','SIN','HKG','2014-04-18 07:25:00','2014-04-18 11:07:00','13333'),
  (23,'TP-721','SIN','TPE','2014-05-01 17:01:00','2014-05-01 21:32:00','16875'),
  (24,'HKD-123','HKG','DOH','2014-05-01 15:00:00','2014-05-01 23:00:00','10000'),
  (25,'SI-827','HKG','SIN','2014-04-13 17:00:00','2014-04-13 20:42:00','16999'),
  (26,'TP-821','HKG','TPE','2014-04-23 10:50:00','2014-04-23 12:21:00','6111'),
  (27,'TP-822','HKG','KHH','2014-05-01 12:00:00','2014-05-01 13:35:00','3999'),
  (28,'LE-814','HKG','LED','2014-04-20 13:00:00','2014-04-20 19:00:00','16875'),
  (29,'TC-823','HKG','TCH','2014-04-26 10:12:00','2014-04-26 11:47:00','4895'),
  (30,'DM-123','DOH','MAN','2014-05-02 02:00:00','2014-05-02 06:30:00','8000'),
  (31,'SI-927','DOH','SIN','2014-04-25 10:00:00','2014-04-25 20:15:00','19387'),
  (32,'HG-148','LED','HKG','2014-04-27 09:00:00','2014-04-27 22:07:00','14960'),
  (33,'NR-146','LED','NRT','2014-04-25 04:00:00','2014-04-25 19:32:00','17992');

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
