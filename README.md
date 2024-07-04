# Symfony 6 docker containers

A Proof-of-concept of a running Symfony 6 application inside containers

```bash|docker
cd symfony-6-docker

docker-compose up -d
```



## Compose

### Database (MariaDB)

### PhpMyAdmin

- [Link to phpmyadmin in port 8080](http://localhost:8080 "localhost:8080")...

### PHP (PHP-FPM)

Composer is included

```docker
docker-compose run php-fpm composer 
```

To run fixtures

```docker
docker-compose run php-fpm bin/console doctrine:fixtures:load

```

To run bash

```docker
docker-compose exec  php-fpm bash
or
docker-compose run  php-fpm bash
```

### Webserver (Nginx)

## Link to project

- [Link to Symfony project in port 80](http://localhost "localhost")...