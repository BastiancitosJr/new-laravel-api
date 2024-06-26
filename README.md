docker-compose up -d
docker-compose exec php artisan migrate --seed
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
docker-compose up --build
```

### 4. Run Migrations

Run the database migrations to set up your database schema:

```bash
docker-compose exec app php artisan migrate --seed
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
docker-compose exec app php artisan migrate:refresh
```
