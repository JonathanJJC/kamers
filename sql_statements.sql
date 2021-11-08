-- Delete database if exists
DROP DATABASE IF EXISTS Hotel_der_Tuin;

-- Create database sql
CREATE DATABASE Hotel_der_Tuin;

-- Select Hotel_der_Tuin as the default database
USE Hotel_der_Tuin;

-- Create table sql
CREATE TABLE Klant(
	Klantid INT NOT NULL AUTO_INCREMENT,
	Naam VARCHAR(250) NOT NULL,
	Adres VARCHAR(250) NOT NULL,
	Plaats VARCHAR(250) NOT NULL,
	Postcode VARCHAR(10) NOT NULL,
	Telefoonnummer INT NOT NULL,
	-- KEYS
	PRIMARY KEY (Klantid)
);

CREATE TABLE Medewerker(
	Medewerkerid INT NOT NULL AUTO_INCREMENT,
	Naam VARCHAR(250) NOT NULL,
	Gebruiksnaam VARCHAR(250) NOT NULL UNIQUE,
	Wachtwoord VARCHAR(70) NOT NULL,
	-- KEYS
	PRIMARY KEY (Medewerkerid)
);

CREATE TABLE Kamers(
	Kamerid INT NOT NULL AUTO_INCREMENT,
	Kamernummer INT NOT NULL UNIQUE,
	PRIMARY KEY (Kamerid)
);

CREATE TABLE ReserveringOverzicht(
	Reserveringnummer INT NOT NULL AUTO_INCREMENT,
	Klantid INT,
	Kamerid INT,
	ReserveringVan DATE NOT NULL,
	ReserveringTot DATE NOT NULL,
	Naam VARCHAR(250) NOT NULL,
	Kamernummer INT NOT NULl,
	-- KEYS
	PRIMARY KEY (Reserveringnummer),
	FOREIGN KEY (Klantid) REFERENCES Klant(Klantid) ON DELETE CASCADE,
	FOREIGN KEY (Kamerid) REFERENCES Kamers(Kamerid) ON DELETE CASCADE
);

CREATE TABLE ReserveringTotaal(
	Reserveringnummer INT,
	Klantid INT,
	Kamerid INT,
	ReserveringVan DATE,
	ReserveringTot DATE,
	Naam VARCHAR(250) NOT NULL,
	Adres VARCHAR(250) NOT NULL,
	Plaats VARCHAR(250) NOT NULL,
	Postcode VARCHAR(10) NOT NULL,
	Telefoonnummer INT NOT NULL,
	-- KEYS
	FOREIGN KEY (Reserveringnummer) REFERENCES ReserveringOverzicht(Reserveringnummer) ON DELETE CASCADE,
	FOREIGN KEY (Klantid) REFERENCES Klant(Klantid) ON DELETE CASCADE,
	FOREIGN KEY (Kamerid) REFERENCES Kamers(Kamerid) ON DELETE CASCADE
);

-- Insert into sql
INSERT INTO kamers VALUES 
	(NULL, 101), 
	(NULL, 103),
	(NULL, 105), 
	(NULL, 107), 
	(NULL, 108), 
	(NULL, 109)
;

INSERT INTO klant VALUES 
	(1, "Frederick", "J van galenstraat", "Ämsterdam", "1082 GH", 0612485437 ), 
	(2, "Gideon", "Gerarda straat", "Lelystad", "1234 FG", 06324851927 ),
	(3, "Matilda", "J van galenstraat", "Almere", "1112 BJ", 06123854927 )
;

INSERT INTO reserveringOverzicht VALUES 
	(1, 1, 1, DATE '2015-12-17', DATE '2015-12-17', "Frederick", 101), 
	(2, 2, 2, DATE '2015-12-17', DATE '2015-12-17', "Gideon", 103), 
	(3, 3, 3, DATE '2015-12-17', DATE '2015-12-17', "Matilda", 105)
;

INSERT INTO ReserveringTotaal VALUES 
	(1, 1, 1, DATE '2015-12-17', DATE '2015-12-17', "Frederick", "J van galenstraat", "Ämsterdam", "1082 GH", 0612485437 ), 
	(2, 2, 2, DATE '2015-12-17', DATE '2015-12-17', "Gideon", "Gerarda straat", "Lelystad", "1234 FG", 06324851927 ),
	(3, 3, 3, DATE '2015-12-17', DATE '2015-12-17', "Matilda", "J van galenstraat", "Almere", "1112 BJ", 06123854927 )
;