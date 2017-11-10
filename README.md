# Web Project - Buy a Goat

During our 4th year at ESIEA - Engineering School - we had to create a full stack web application based on a framework and using the MVC.

As a framework, we choosed Slim Framework, since it was indicated by our professor as a good tool, even for beginners.

For the MVC, we used:
* Eloquent to manage the database (DB) and the Models
* Twig to manage the Views


The goal of this project is to create a website on which one can get informations about goats and sell or buy a goat.

## Install
### Installed Applications (add sources and commands)

LAMP:
* php
* MySQL
* Apache2s

Framework:
* Composer
* Slim Framework
* Eloquent
* Monolog
* Twig

### Modifications

We ensured that the `composer.json` file has been modified according to the namespaces that we created.

    "autoload-dev": {
            "psr-4": {
                "Tests\\": "tests/",
                "App\\Models\\": "src/Models/",
                "App\\Controllers\\": "src/Controllers/"
            }
    }

## Database

The Database should be set before starting the application.

You can find in `src/create_table.php` the way that the tables are created. Here are the tables in SQL:

For the image table:

      CREATE TABLE IF NOT EXISTS image (
        id int(11) NOT NULL AUTO_INCREMENT,
        path varchar(250) NOT NULL,
        type enum('race', 'goat') NOT NULL,
        num int(11) NOT NULL,
        ext enum('jpg', 'jpeg', 'png') NOT NULL,

        PRIMARY KEY (id),
        UNIQUE KEY (name),
        FOREIGN KEY (img_id) REFERENCES web_project.image(
          id
        )
      );


For the race table:

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
        exploitation enum('milk', 'cheese', 'hair', meat', 'pet'),
        img_id int(11) NOT NULL,

        PRIMARY KEY (id),
        UNIQUE KEY (name),
        FOREIGN KEY (img_id) REFERENCES web_project.image(
          id
        )
      );

For the goat table:

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

## Containers and Settings
### Settings
### Containers
## MVC

### Models

The Models used are **Image**, **Race** and **Goat**, as created in the DB, in the *Models* namespace.

The **Order** Model was added to be used by Eloquent.

## Views

We used *Twig* to create our views because we can, from a layout, dynamically change our pages in the easiest way possible.

## Controllers

The Controllers used are **CommonController**, **RaceControlelr** and **GoatController**, in the *Controllers* namespace.

The CommonController has been created as the parent of other controllers. That is why it contains the constructor and default behavior methods.

The RaceController and the GoatController are used over the Models that have the same name. They are added to `dependencies.php` as containers so they can be used in the Routes.

## Credits

Célande ADRIEN
Arthur FAGOT
