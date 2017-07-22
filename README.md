# Project Prisma backend

This is the repository for the [Project Prisma](http://www.frederikvincx.com/project-prisma-helping-people-with-dementia) backend.  
It's currently used by the [Prisma Ionic app](https://github.com/oSoc17/prisma-frontend).

## About

Project Prisma is part of [open Summer of Code 2017](http://2017.summerofcode.be/). A student team coached by Frederik Vincx [(@fritsbits)](https://github.com/fritsbits) is working on it now:

- Michiel Leyman [(@MichielLeyman)](https://github.com/MichielLeyman) - backend & project management
- Simon Westerlinck [(@siimonco)](https://github.com/siimonco) - backend
- Jean-Pacifique Mboynincungu [(@oxnoctisxo)](https://github.com/oxnoctisxo) - frontend & system analysis
- Thor Galle [(@th0rgall)](https://github.com/th0rgall) - frontend & user testing

You can find a task overview (both frontend and backend) on [on our Kanban](https://waffle.io/oSoc17/prisma-backend).

## Installation

Requires PHP version `>=5.6.4`.
Make sure [Composer](https://getcomposer.org/) is installed.

After cloning, install project dependencies:  
```bash
composer install
```
Serve the application:
```bash
php artisan serve
```
