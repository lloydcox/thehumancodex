## About Installation

You can run docker commands using make file. goto folder directory and use make commands.

    [make createcontainer]

## Docker Commands

First setup docker container without detached mode

    [docker-compose build && docker-compose up]

First setup docker containers with detached mode

    [docker-compose build && docker-compose up -d]

then install dependencies in npm and vendor files

    - install npm files
        [docker-compose run --rm npm install]

install vendor files

    - open php container
        [docker-compose exec php sh]

    - install vendor files run inside php container
        [composer update or composer install]

then make a .env file and generate a new key inside php container

    [php artisan key:generate]

then migrate all database tables inside php container

    [php artisan migrate]

then install passport for login authentications run inside php container

    [php artisan passport:install]

get full backups

    [docker-compose run --rm artisan backup:run]

If any issue with connect to database please refer this link

    [https://medium.com/tech-learn-share/docker-mysql-access-denied-for-user-172-17-0-1-using-password-yes-c5eadad582d3]"# thehumancodex" 
