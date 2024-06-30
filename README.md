## Installation guide

There are two installation guides:
- [Undockerized Laravel](#php-laravel-10-application-without-docker)
- [Dockerized Laravel](#dockerized-php-laravel-10-application)

# PHP Laravel 10 Application (Without Docker)
Before you start, make sure you have [Git](https://git-scm.com/downloads), [Xampp](https://www.apachefriends.org/es/index.html) to install php & [Composer](https://getcomposer.org/) installed on your system.

Before starting, you must you must have oracle instant client installed.

Clone the project to the folder of your choice using the following command using git or the windows command prompt:

```bash
git clone https://github.com/BastiancitosJr/new-laravel-api.git

cd new-laravel-api
```

1. **Install PHP dependencies with Composer:**

```bash
composer install
```
This command will install the necessary PHP dependencies for the project.

In the event that you have some type of error that requires updating composer, execute:

```bash
composer update
```

If you encounter an error in this step, you must enable the zip, oci8_12c and sodium extensions in your php.ini file of your php installation.

2. **Configure environment variables:**

```bash
copy .env.example .env
```

This command will copy the .env.example file to .env. Here you must configure the database with your own credentials

3. **Generate JWT secret**

```bash
php artisan jwt:secret
```

Will ask you to overwrite the current password, accept. This command will include dependencies that will successfully execute JWT.

4. **Populate the database:**

```bash
php artisan migrate --seed
```

This command will create and populate the tables in the database with test data. Make sure the database is operational and matches the configuration previously made in the .env file.

5. **Start the Laravel server:**

```bash
php artisan serve --host=0.0.0.0 --port=8000
```

This command will start the Laravel server and is accessible via IPv4 port 8000.

# Dockerized PHP Laravel 10 Application

This repository contains a Dockerized PHP Laravel application with support for Oracle database.

## Prerequisites

Ensure you have the following installed on your system:

- Docker
- Docker Compose

## Getting Started

Follow these steps to get the application up and running:

### 1. Clone the Repository

```bash
git clone https://github.com/BastiancitosJr/new-laravel-api.git

cd new-laravel-api
```

### 2. Set Up Environment Variables

Copy the .env.example file to create a .env file:

```bash
cp .env.example .env
```

### 3. Build and Start the Docker Containers

Use Docker Compose to build and start the containers:

```bash
docker-compose up --d
```

### 4. Run Migrations

Run the database migrations to set up your database schema:

```bash
docker-compose exec aquachile-api php artisan migrate --seed
```

### 5. Access the Application

Your application should now be running and accessible. Try the endpoint:

http://127.0.0.1:8000/api/auth/login

## Additional Commands

### Rebuilding Containers

If you make changes to the Dockerfile or the docker-compose.yml file, you may need to rebuild the containers:

```bash
docker-compose up --build
```

### Stopping Containers

To stop the running containers, use:

```bash
docker-compose down
```

### Running Artisan Commands

You can run any Artisan command using the docker-compose exec command. For example:

```bash
docker-compose exec aquachile-api php artisan migrate:refresh --seed
```
