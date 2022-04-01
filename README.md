# Videocard webshop back-end API

This is a RESTFUL laravel 8 api.

## Technicals
This api makes use of 200, 201, 400, 401, 403, 405, 422 and 500 status codes. <br/>
The api has GET/POST/PUT/DELETE points. <br/>
The orderoverview for admins makes use of pagination.

#### Authentication
Uses the tymon library for handling jwt tokens. <br/>
The application has custom functionality for handling refresh tokens.

<br/><br/>
The seeded users are:

Admin:
Email: mark@test.com
Password: Welkom!

Normal user:
Email: neburpoots@test.com
Password: Welkom!

## Setup
1. clone repository
2. run composer install
3. create database
4. change env file to the created database
5. run: php artisan migrate:fresh --seed to seed the database
6. run application with: php aritisan serve

## Front-end application
Git: https://github.com/neburpoots/Vue-3-webshop-front-end
