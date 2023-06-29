# MaxComply API

### Development

*Prerequisites:* `Docker`, `Docker Compose`.

To build development environment, including `PHP 8.1`, `MySql 5.7` and `Nginx` just run the following command:
```
docker-compose up -d
```

All running containers can be listed by using the command `docker-compose ps` from the project folder or
`docker ps` from any other location.

After all images are build, the *PHP* container can be accessd using:
```
docker-compose exec php bash
``` 

Now, inside the container bootstrap the Symfony application by running:
```
composer install
```

Create database structure:
```
bin/console doctrine:migrations:migrate
```

Make commands:

Reset Test DB:
```
make reset-test-db
```

Run tests:
```
make qa
```

Run fixers:
```
make fix
```

The project can be accessed at [http://localhost:8080]().

