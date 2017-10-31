CREATE DATABASE web_project;

USE web_project;

CREATE TABLE IF NOT EXISTS `race` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(250) NOT NULL,
  `height` int(11) NOT NULL,
  `weight` int(11) NOT NULL,
  `color` varchar(250) NOT NULL,
  `origin` varchar(250) NOT NULL,
  `hair_growth` int(11) DEFAULT NULL,
  `milk_by_lactation` int(11) DEFAULT NULL,
  `duration_of_lactation` int(11) DEFAULT NULL,
  `exploitation` varchar(250) NOT NULL,
  PRIMARY KEY (`id`)/*,
  UNIQUE KEY `name` (`name`)*/
);

CREATE TABLE IF NOT EXISTS `goat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(250) NOT NULL,
  `price` int(11) NOT NULL,
  `birthdate` date(11) NOT NULL,
  `race_id` int(11) NOT NULL,
  `gender` varchar(250) NOT NULL,
  `localisation` varchar(250) NOT NULL,
  `identification` int(11) DEFAULT NULL,
  `description` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`race_id`) REFERENCES `web_project`.`race`(
    `id`
  )
);

INSERT INTO `race` (`name`, `height`, `weight`, `color`, `origin`, `hair_growth`, `milk_by_lactation`, `duration_of_lactation`, `exploitation`) VALUES
(`name`, `height`, `weight`, `color`, `origin`, `hair_growth`, `milk_by_lactation`, `duration_of_lactation`, `exploitation`),
(`name`, `height`, `weight`, `color`, `origin`, `hair_growth`, `milk_by_lactation`, `duration_of_lactation`, `exploitation`),
(`name`, `height`, `weight`, `color`, `origin`, `hair_growth`, `milk_by_lactation`, `duration_of_lactation`, `exploitation`);

INSERT INTO `goat` (`name`, `price`, `birthdate`, `race_id`, `gender`, `localisation`, `identification`, `description`) VALUES
(`name`, `price`, `birthdate`, `race_id`, `gender`, `localisation`, `identification`, `description`),
(`name`, `price`, `birthdate`, `race_id`, `gender`, `localisation`, `identification`, `description`),
(`name`, `price`, `birthdate`, `race_id`, `gender`, `localisation`, `identification`, `description`); 
