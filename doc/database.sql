
/* https://fr.wikibooks.org/wiki/MySQL/Parcourir_les_bases_de_donn%C3%A9es */
DROP DATABASE web_project;

CREATE DATABASE IF NOT EXISTS web_project;

USE web_project;

CREATE TABLE IF NOT EXISTS race (
  id int(11) NOT NULL AUTO_INCREMENT,
  name varchar(250) NOT NULL,
  height float(7,2) NOT NULL,
  weight float(7,2) NOT NULL,
  color varchar(250) NOT NULL,
  origin varchar(250) NOT NULL,
  hair_growth float(7,2) DEFAULT NULL,
  milk_by_lactation float(7,2) DEFAULT NULL,
  duration_of_lactation float(7,2) DEFAULT NULL,
  exploitation varchar(250) NOT NULL,
  /*exploitation enum('milk', 'cheese', 'hair', meat', 'pet'),*/
  /*average_lifespan*/
  PRIMARY KEY (id),
  UNIQUE KEY (name)
);

CREATE TABLE IF NOT EXISTS goat (
  id int(11) NOT NULL AUTO_INCREMENT,
  name varchar(250) NOT NULL,
  price float(7,2) NOT NULL,
  birthdate date NOT NULL,
  race_id int(11) NOT NULL,
  gender enum('male', 'female') NOT NULL,
  localisation varchar(250) NOT NULL,
  identification varchar(250) DEFAULT NULL,
  description text NOT NULL,
  created_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  FOREIGN KEY (race_id) REFERENCES web_project.race(
    id
  )
);

/*INSERT INTO race (name, height, weight, color, origin, hair_growth, milk_by_lactation, duration_of_lactation, exploitation) VALUES*/
INSERT INTO race (name, height, weight, color, origin, milk_by_lactation, duration_of_lactation, exploitation) VALUES
('Saanen', 85, 72.5, 'white', 'Switzerland', 800, 9.3, 'milk');
INSERT INTO race (name, height, weight, color, origin, exploitation) VALUES
('Pygmy goat', 50, 30, 'caramel pattern, agouti pattern, and black pattern', 'Central and West Africa', 'pet');
INSERT INTO race (name, height, weight, color, origin, hair_growth, exploitation) VALUES
('Angora', 65, 45, 'white', 'Central Asia', 2.5, 'hair');

INSERT INTO goat (name, price, birthdate, race_id, gender, localisation, identification, description) VALUES
('Pupuce', 100, '2017-08-30', 1, 'female', 'Lyon - France', 'FR 001 001 00001', 'Young goat loving corn.'),
('George', 200, '2014-02-14', 2, 'male', 'Langre - France', 'FR 002 002 00002', 'Manly male, stubborn, kicker and go-ahead type.'),
('Laurel', 300.5, '2013-03-15', 3, 'female', 'Chaumont - France', 'FR 003 003 00003', 'Pretty little nanny, sleeping on feathers only.');
