php app/console assets:install web/
php app/console assetic:dump
php app/console assetic:dump --env=prod
php app/console doctrine:schema:update --force