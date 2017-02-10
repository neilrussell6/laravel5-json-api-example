## Laravel 5 JSON API

Laravel 5 JSON API implementation.

response format:

* [JSON API](http://jsonapi.org/format/)

uses:

* [Laravel 5](https://laravel.com/docs/5.3)
* [Codeception](http://codeception.com/)

## Installation

```bash
$ composer install
$ php artisan key:generate
$ cp .env.example .env
```

Fill in .env file

## Data

To create required database tables, update .env file with local database configuration and then run:
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

update .env.testing with teh following details
DB_CONNECTION=sqlite
DB_DATABASE=storage/task_manager_api.sqlite

create Sqlite database in storage (it will be ignored by git) and make it executable
```bash
touch storage/task_manager_api.sqlte
sudo chmod -R 777 storage/task_manager_api.sqlte
```

temporarily change .env with the following details
DB_CONNECTION=sqlite
DB_DATABASE=storage/task_manager_api.sqlite

run migrate on Sqlite database
```bash
php artisan migrate:refresh
```

undo changes to .env (there may be a better way to do this) 

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
