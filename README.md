# WL Test

WL Test is a PHP console application that scrapes the following website
https://wltest.dns-systems.net/ 
and returns a JSON array of all the product options on the page, sorted by the annual price in descending order.

## Installing Dependencies

App uses monolog/monolog, vlucas/phpdotenv and phpunit dependencies.
After cloning the repository, to install the dependencies, run the following (in a Windows command prompt) from the root folder:
```dos
composer install
```

## Running the app

To run the app, run the following command (in a Windows command prompt) from the root folder:
```dos
./run
```
Alternatively, you can run
```dos
php src/main.php
```

## Running the tests

To run the tests, run the following command (in a Windows command prompt) from the root folder:
```dos
./test
```
Alternatively, you can run
```dos
./vendor/bin/phpunit --verbose tests
```
