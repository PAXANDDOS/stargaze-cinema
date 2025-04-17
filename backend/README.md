RESTful API built with Symfony framework for the "Stargaze Cinema" project.

## Starting the application with Docker

1. Run `cp .env.example .env` in the terminal to create environment file.
2. Run `docker-compose up -d --build` to create containers and start the application.
3. Run `docker exec -it php sh` to enter Dockers' PHP console.
4. Create database and fill it by running `composer database-scratch` or complete steps below:
    1. Run `bin/console doctrine:database:create` to create a database for the application.
    2. Run `bin/console doctrine:migrations:migrate` to add tables to the database.
    3. Run `bin/console doctrine:fixtures:load` to seed the database.
5. Now you are ready use the application:
    - Open `localhost:8000` in the browser to access the application.
    - Open `localhost:8080` in the browser to access Swagger API Documentation.
    - Open `localhost:8025` in the browser to access MailHog UI.
    - Open `localhost:9001` in the browser to access MinIO Console.
    - Open `localhost:15672` in the browser to access RabbitMQ Management.

## Testing the application with Codeception

1. Run `docker exec -it php sh` to enter Dockers' PHP console.
2. Create database and fill it by running `composer database-test-scratch` or complete steps below:
    1. Run `bin/console --env=test doctrine:database:create` to create a testing database for the application.
    2. Run `bin/console --env=test doctrine:migrations:migrate` to add tables to the database.
    3. Run `bin/console --env=test doctrine:fixtures:load` to seed the database.
3. Run `composer test-build` to build Actor classes.
4. Now you are ready to test:
    - Create Suite test with `composer test-generate-suite`
    - Create Cept test with `composer test-generate-cept`
    - Create Cest test with `composer test-generate-cest`
    - Run tests with `composer test`

## Building for production with Docker

1. Run `cp .env.example .env` in the terminal to create environment file.
2. Database must be running and configured in `.env` file.
3. Run `docker build -f docker/Dockerfile --target production -t stargaze-cinema .` to build the application image.
4. Run `docker run -p 80:80 stargaze-cinema` to run the application
5. Open `localhost:8000` in the browser to access the application.
