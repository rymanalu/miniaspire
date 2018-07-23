# Mini Aspire

## Installation

1. Clone this repo
2. `composer install`
3. `cp .env.example .env` 
4. `php artisan key:generate`
5. Set your database configuration in `.env`
6. `php artisan passport:keys --force`
7. `php artisan migrate --seed`
8. `php artisan passport:client --personal`

## Postman Collection

[Here](/blob/master/miniaspire.postman_collection.json)
