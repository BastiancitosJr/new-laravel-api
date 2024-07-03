# PHP Laravel 10 Application (Without Docker)

To consult the dockerized version go to [DOCKER.md](DOCKER.md)

## Prerequisites

Before you start, make sure you have [Git](https://git-scm.com/downloads), [Xampp](https://www.apachefriends.org/es/index.html) to install PHP (8.2 or higher), and [Composer](https://getcomposer.org/) installed on your system. You must also have the Oracle Instant Client installed.

## Installation Guide

1. **Clone the Project**

Clone the project to the folder of your choice using the following command:

```bash
git clone https://github.com/BastiancitosJr/new-laravel-api.git

cd new-laravel-api
```

2. **Install PHP Dependencies with Composer**

```bash
composer install
```

This command will install the necessary PHP dependencies for the project.

If you encounter any errors that require updating Composer, execute:

```bash
composer update
```

If you encounter an error in this step, you must enable the zip, oci8_12c, and sodium extensions in your php.ini file.

3. **Configure Environment Variables**

```bash
copy .env.example .env
```

This command will copy the .env.example file to .env. Here you must configure the database with your own credentials.

4. **Generate JWT Secret**

```bash
php artisan jwt:secret
```

This command will ask you to overwrite the current secret. Accept it. This command will include dependencies that will successfully execute JWT.

5. **Populate the Database**

```bash
php artisan migrate --seed
```

This command will create and populate the tables in the database with test data. Make sure the database is operational and matches the configuration previously made in the .env file.

6. **Start the Laravel Server**

```bash
php artisan serve --host=0.0.0.0 --port=8000
```

This command will start the Laravel server and it will be accessible via IPv4 port 8000.
