# Q-Client

## Getting Started

These instructions will get you a copy of the project up and running on your local machine for development and testing purposes. See deployment for notes on how to deploy the project on a live system.
The project is made in Laravel 9. You need to have PHP >= 8.0 and Composer >= 2.0 installed on your local machine.
### Deployment

Clone the repository
```
git clone https://github.com/filigor94/q-test.git
```

Do composer install
```
composer install
```

If composer install fails, check if you have `extension=fileinfo` enabled in `php.ini`

Copy the `.env.example` file and name it `.env`

Generate the application key

```
php artisan key:generate
```

You are ready to start the local server

```
php artisan serve
```

## Accessing the web page

By default, you can access it at

```
127.0.0.1:8000
E-mail: ahsoka.tano@q.agency
Password: Kryze4President
```
