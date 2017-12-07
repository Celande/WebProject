# Web Project - Buy a Goat

During our 4th year at ESIEA - Engineering School - we had to create a full stack web application based on a framework and using the MVC.

As a framework, we choosed Slim Framework, since it was indicated by our professor as a good tool, even for beginners.

For the MVC, we used:
* Eloquent as ORM
* Twig to manage the Views
* What Slim framework allow us for the Controllers


The goal of this project is to create a website on which one can get informations about goats and sell or buy a goat.

*The code used for the inline website is available on the release_version branch"

## Install
### Installed Applications

LAMP:
* php
* MySQL
* Apache2

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
            "App\\Controllers\\": "src/Controllers/",
            "App\\Handlers\\": "src/Handlers/"
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

        PRIMARY KEY (id)
      );


For the breed table:

      CREATE TABLE IF NOT EXISTS breed (
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
      breed_id int(11) NOT NULL,
      gender enum('male', 'female') NOT NULL,
      localisation varchar(250) NOT NULL,
      identification varchar(250) DEFAULT NULL,
      description text NOT NULL,
      created_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,

      PRIMARY KEY (id),
      FOREIGN KEY (breed_id) REFERENCES web_project.breed(
        id
      )
      FOREIGN KEY (img_id) REFERENCES web_project.image(
        id
      )
    );

## Environment
### Settings
You can find in the `src/settings.php` file the settings used for this project.

We added from the base file what we needed to make the DB works and the *logger* variable thanks to *Monolog*.

The *displayErrorDetails* parameter is set to *false* when the website is deployed. It must be set to *true* to see errors.

The different parameters for the DB are set in this file.
### Containers
You can find in the `src/dependencies.php` file some more settings for the project.

We added from the base file the *logger*, *img*, *img_breed* and *img_goat* to indicate the path to the image folder. There is also *db* for the DB, *view* used for *Twig*, the Controllers and the Handlers.

### Error Handlers
We choosed to use error handlers for the *404* and the *405* pages so that we could personalize our view by keeping the navigation bar on those page.
## MVC

### Models

The Models used are **Image**, **Breed** and **Goat**, as created in the DB, in the *Models* namespace.

The **Order** Model was added to be used by Eloquent.

## Views

We used *Twig* to create our views because we can, from a layout, dynamically change our pages in the easiest way possible.

## Controllers

The Controllers used are **CommonController**, **BreedControlelr**, **GoatController** and **ImageController** in the *Controllers* namespace.

The CommonController has been created as the parent of other controllers. That is why it contains the constructor and default behavior methods.

The BreedController, the GoatController and the ImageController are used over the Models that have the same name. They are added to `dependencies.php` as containers so they can be used in the Routes.

You can add goats but also breeds and images through the *Add Goat* form.

## Credits

Célande ADRIEN

Arthur FAGOT
