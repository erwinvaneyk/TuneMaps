# TuneMaps
TuneMaps is a music recommender system that uses geographical data to predict musical tastes and trends.

## Requirements
The web system requires a working Apache web server with PHP and MySQL.
The chart prediction algorithm requires both Java and MatLab to run.

## Web server installation
This project is built using the symfony framework. Installation requires several steps:

### Placement
The project files should be placed in a directory on your webserver. The project's /web folder is considered the "root" and is where the application starts and runs. Say you have the following structure after checking out the GIT repository:
```
../[your webserver folder]/TuneMaps/
```
Then your application's URL will be:
```
http://[server URL]/TuneMaps/web/app.php/...
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
You first need to configure the project so it knows your database information. Edit the following file:
```
app/config/parameters.yml
```
In particular, it is required that you fill out the database information
```
database_host: your-database-host
database_name: tunemaps
database_user: your-database-user
database_password: your-database-password
```

### Database creation
Using the command line run the following commands from the tunemaps directory to create the database and schema:
```
php app/console doctrine:database:create
php app/console doctrine:schema:update --force
```

### Asset installation
Next you need to install the assets (javascript files, stylesheets and images). To do this run the following commands in order:
```
php app/console assets:install web/
php app/console assetic:dump
php app/console assetic:dump --env=prod
```

### Registring users
The system requires users to login. Since the system is initially empty you will have to register a user. To do this go to the following URL:
```
http://[server URL]/TuneMaps/web/app.php/register
```

### Done
You can now use the application by logging in at the following URL
```
http://[server URL]/TuneMaps/web/app.php/
```


## Running chart predictions
All the chart prediction software is contained in the following folder in the github repository:
```
./chartprediction/
```

Running this software requires 2 steps:

### Crawling new data
You have to run the java crawler application to get new chart data from Last.FM. This program obtains the latest information first and continues to crawl historic data. You can turn it off at any point and the progress will have been saved in the CSV data files. You can run the program as following:
```
java -jar ./chartprediction/TuneMapsChartCrawler.jar
```

### Making predictions
Making predictions based on the crawled data requires MatLab. Make sure MatLab's current work directory is in the following folder (this resolve paths to data files):
```
./chartprediction/
```

Next, run the following command inside MatLab:
```
predict_all()
```

### Done
You're predictions are now done and will be used by the web application.