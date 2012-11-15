# TuneMaps
TuneMaps is a music recommender system that uses geographical data to predict musical tastes and trends.

## Requirements
This project requires a working web server with PHP and MySQL.

## Installation
This project is built using the symfony framework. Installation requires several step:

### Placement
The project files should be placed in a directory on your webserver. The project's /web folder is considered the "root" and is where the application starts and runs. Say you have the following structure after checking out the GIT repository:
```
[your webserver folder]/htdocs/tunemaps/[repository checkout]
```
Then your URL will be:
```
[server URL]/tunemaps/web/...
```

### Configuration
You first need to configure the project so it knows your database information. For security reasons this has to be done from the localhost:
```
localhost/tunemaps/web/config.php
```

Follow the steps there and enter information such as your database login and password.

### Database creation
From the command line run the following commands from the tunemaps directory to create the database and schema:
```
php app/console doctrine:database:create
php app/console doctrine:schema:update --force
```

### Done
You can now use the application by visiting
	[server URL]/tunemaps/web/

