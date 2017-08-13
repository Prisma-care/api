# Prisma API [![Build Status](https://travis-ci.org/Prisma/api.svg?branch=master)](https://travis-ci.org/Prisma/api)

## About

Prisma is an app that strengthens the relationship between people with memory loss and the people close to them. It does this by providing a living, collaborative digital photo album that can be populated with content of interest to these people.

This repository hosts the central API for the project.

Be sure to check out our home page [prisma.care](http://prisma.care) for more information.

## History

Project Prisma was part of [Open Knowledge Belgium](https://www.openknowledge.be/)'s [open Summer of Code 2017](http://2017.summerofcode.be/). A student team coached by Frederik Vincx [(@fritsbits)](https://github.com/fritsbits) worked on it in July 2017:

* Michiel Leyman ([@MichielLeyman](https://github.com/MichielLeyman)) - backend & project management
* Simon Westerlinck ([@siimonco](https://github.com/siimonco)) - backend & database modelling
* Jean-Pacifique Mboynincungu ([@oxnoctisxo](https://github.com/oxnoctisxo)) - frontend system analysis
* Thor Galle ([@th0rgall](https://github.com/th0rgall)) - frontend & product owner

The app was conceived in a one-month collaborative design project in a care home in Zonhoven, Belgium, in January 2017. Together with personel and dementia design researchers the team honed in on a static prototype that was later refined during the Open Summer of Code. More info about it in [this blog post](http://www.frederikvincx.com/project-prisma-helping-people-with-dementia/).

## Contributing

Want to help out?
First, peruse the [Prisma wiki](https://github.com/Prisma/documentation/wiki) to learn about the roadmap, milestones and approach to developing software for people with dementia.

## Installation

Requires PHP version `>=5.6.4`.
Make sure [Composer](https://getcomposer.org/) is installed.

After cloning, install project dependencies:  
```bash
composer install
```

Generate an app key:
```bash
php artisan key:generate
```

We use MySQL, you can add settings for your local database configuration in a `.env` file ([see example](https://github.com/Prisma/api/blob/develop/.env.example)). All other configuration that is specific to your local environment should also be set in this file.

Run database migrations and seed:
```bash
php artisan migrate
php artisan db:seed
```

Serve the application:  
```bash
php artisan serve
```

Run API tests:  
```bash
./vendor/bin/phpunit --testsuite Feature
```  
This uses the `testing` environment, so make sure you have a `.env.testing` file for all configuration specific to running the tests (such as a separate database).
