SET FOREIGN_KEY_CHECKS=0;
DROP TABLE IF EXISTS Interested;
DROP TABLE IF EXISTS Airport;
DROP TABLE IF EXISTS User;
DROP TABLE IF EXISTS Flight; 

CREATE TABLE Airport (
  name      CHAR(3)    NOT NULL PRIMARY KEY,
  longitude FLOAT(8,5) NOT NULL,
  latitude  FLOAT(8,5) NOT NULL
) ENGINE = InnoDB;
INSERT INTO Airport VALUES ( 'TPE', 121.22388, 25.07639 ),
                           ( 'HDN', 139.78408, 35.54885 ),
                           ( 'FRA', 8.55915  , 50.03775 );


CREATE TABLE Flight
(
  id             INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  flight_number  VARCHAR(255) NOT NULL,
  departure      CHAR(3)      NOT NULL,
  destination    CHAR(3)      NOT NULL,
  departure_date DATETIME     NOT NULL,
  arrival_date   DATETIME     NOT NULL,
  price          INT UNSIGNED NOT NULL,
  FOREIGN KEY (departure)   REFERENCES Airport(name) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (destination) REFERENCES Airport(name) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB;
INSERT INTO Flight VALUES ( 1, 'SA311', 'TPE', 'HDN', '2014-03-02 12:00', '2014-03-02 16:00', 10000 ),
                          ( 2, 'SA312', 'TPE', 'HDN', '2014-03-02 13:00', '2014-03-02 17:00', 10001 ),
                          ( 3, 'SA313', 'TPE', 'HDN', '2014-03-02 14:00', '2014-03-02 18:00', 10002 ),
                          ( 4, 'SA314', 'TPE', 'HDN', '2014-03-02 15:00', '2014-03-02 19:00', 10003 ),
                          ( 5, 'SA315', 'TPE', 'HDN', '2014-03-02 16:00', '2014-03-02 20:00', 10004 );

CREATE TABLE User
(
  id       INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  account  VARCHAR(255) NOT NULL,
  password VARCHAR(255) NOT NULL,
  is_admin BOOLEAN      NOT NULL
) ENGINE = InnoDB;
INSERT INTO User VALUES ( 1, 'schwannden', md5('0016205'), true ),
                        ( 2, 'ywchen'    , md5('0111042'), true ),
                        ( 3, 'guest'     , md5('guest')  , false);


CREATE TABLE Interested
(
  user_id   INT UNSIGNED NOT NULL,
  flight_id INT UNSIGNED NOT NULL,
  FOREIGN KEY (user_id)   REFERENCES User(id)   ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (flight_id) REFERENCES Flight(id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB;
INSERT INTO Interested VALUES (1, 1),
                              (1, 2),
                              (2, 3),
                              (2, 4);

SET FOREIGN_KEY_CHECKS=1;
