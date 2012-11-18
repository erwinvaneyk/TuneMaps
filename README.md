# TuneMaps
TuneMaps is a music recommender system that uses geographical data to predict musical tastes and trends.

## Requirements
This project requires a working Apache web server with PHP and MySQL.

## Installation
This project is built using the symfony framework. Installation requires several steps:

### Placement
The project files should be placed in a directory on your webserver. The project's /web folder is considered the "root" and is where the application starts and runs. Say you have the following structure after checking out the GIT repository:
```
../[your webserver folder]/TuneMaps/
```
Then your URL will be:
```
http://[server URL]/TuneMaps/web/...
```

### Default Parameters
Go to the following directory
```
app/config/
```
And copy+paste the "parameters.yml.dist" file. Rename it to "parameters.yml".

### Installing Vendor Scripts
The project requires basic symfony vendor scripts. To install these run the following command in the TuneMaps directory:
```
php composer.phar install
```

### Configuration
You first need to configure the project so it knows your database information. For security reasons this has to be done from the localhost:
```
http://localhost/tunemaps/web/config.php
```
Click on 'Configure your Symfony Application online' and follow the steps there.

### Database creation
Using the command line run the following commands from the tunemaps directory to create the database and schema:
```
php app/console doctrine:database:create
php app/console doctrine:schema:update --force
```

### Done
You can now use the application by visiting
```
http://[server URL]/tunemaps/web/
```
