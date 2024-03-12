<p align="center"><img src="./public/images/runnerlogo1.png" width="250" alt="Vroom Vroom Logo"></p>

## About Vroom Vroom

Vroom Vroom is a school project with the objective of creating a car themed application with Laravel.
It's a CRUD application where a user can rent cars or rent out there own cars.

The application should contain the following features of Laravel:

-   Controllers
-   Migrations
-   HTTP Tests (on all routes)
-   Middleware
-   Models (with relationships)
-   Routes (with route model binding)
-   Eloquent
-   Validation
-   Views (Blade)
-   Car theme

## Setup

Clone repo

```bash
git clone https://github.com/WilliamDavidson-02/vroom-vroom-car-rental.git
```

Install

```bash
npm install
```

and

```bash
composer install
```

Generate a env file and key

```bash
cp .env.example .env
```

```bash
php artisan key:generate
```

Create a local sql database

Migrate

```bash
php artisan migrate
```

Seed

```bash
php artisan db:seed
```

```bash
php artisan db:seed --class=CarSeeder
```

```bash
php artisan db:seed --class=BookingSeeder
```

```bash
php artisan db:seed --class=ReviewSeder
```

## Serve

To run start a vite and artisan server

```bash
npm run dev
```

```bash
php artisan serve
```
