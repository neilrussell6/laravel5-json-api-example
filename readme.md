Laravel 5 JSON API
==================

Laravel 5 JSON API implementation.

Response format:

* [JSON API](http://jsonapi.org/format/)

Uses:

* [Laravel 5](https://laravel.com/docs/5.3)
* [Codeception](http://codeception.com/)

## Installation

```bash
$ composer install
$ php artisan key:generate
$ cp .env.example .env
```

Fill in `.env` file

## Data

To create required database tables, update `.env` file with local database configuration and then run:
```bash
php artisan migrate
```

To populate the database with demo data run:
```bash
php artisan db:seed
```

To reseed, first recreate the tables by running:
```bash
php artisan migrate:refresh
```

## Dev

```bash
$ php artisan serve
```

## Testing

#### environment config / setup

```bash
$ cp .env.testing.example .env.testing
$ cp tests/api.suite.yml.example tests/api.suite.yml
$ vendor/codeception/codeception/codecept build
```

Fill in .env.testing file

#### Sqlite

To setup Codeception tests to run using a Sqlite database:

Update `.env.testing` with the following details:
```
DB_CONNECTION=sqlite
DB_DATABASE=storage/task_manager_api.sqlite
```

Create Sqlite database in `storage` directory and make it executable:
*replacing `my_project_db` with your project's SQLite database name.*
*Will be Git ignored.*
```bash
touch storage/my_project_db.sqlite
sudo chmod -R 777 storage/my_project_db.sqlite
```

> **TODO:** find a better way to do this. 

Temporarily change .env with the following details:
*replacing `my_project_db` with your project's SQLite database name.*
```bash
DB_CONNECTION=sqlite
DB_DATABASE=storage/my_project_db.sqlite
```

Run migrate on SQLite database:
```bash
php artisan migrate:refresh
```

Undo changes to `.env`. 

#### run tests

```bash
$ vendor/codeception/codeception/codecept run
```

or if you create a bash alias for Codeception like:
```bash
alias cc="vendor/codeception/codeception/codecept"
```

then you can run the commands like this:
```bash
$ cc build
$ cc run
```
