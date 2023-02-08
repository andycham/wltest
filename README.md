# WL Test

WL Test is a PHP console application that scrapes the following website
https://wltest.dns-systems.net/ 
and returns a JSON array of all the product options on the page, sorted by the annual price in descending order.

## Cloning the Repository
```
git clone https://github.com/andycham/wltest
```
Change to the application root folder (wltest):
```
cd wltest
```

## Installing Dependencies

Ensure that PHP 8.2.2 and Composer are installed.
All commands are run from the wltest folder.
App has monolog/monolog, vlucas/phpdotenv and phpunit dependencies.
To install the dependencies, run the following command from the wltest folder:
```
composer install
```

## Running the app

To run the app, run the following command (in a Windows command prompt) from the wltest folder:
```
./run
```
Alternatively, you can run
```
php src/main.php
```
To run the app in a docker container, run the following command, replacing c:/dev/wltest with the path on your system:
```
docker run --rm -v c:/dev/wltest:/opt php:8.2-cli php opt/src/main.php
```
The app should output the following:
```
[{"title":"Optimum: 2 GB Data - 12 Months","description":"2GB data per monthincluding 40 SMS(5p \/ minute and 4p \/ SMS thereafter)","price":15.99,"discount":0,"monthlyPrice":15.99,"annualPrice":191.88},{"title":"Optimum: 24GB Data - 1 Year","description":"Up to 12GB of data per year including 480 SMS(5p \/ MB data and 4p \/ SMS thereafter)","price":174,"discount":17.9,"monthlyPrice":14.5,"annualPrice":174},{"title":"Standard: 1GB Data - 12 Months","description":"Up to 1 GB data per monthincluding 35 SMS(5p \/ MB data and 4p \/ SMS thereafter)","price":9.99,"discount":0,"monthlyPrice":9.99,"annualPrice":119.88},{"title":"Standard: 12GB Data - 1 Year","description":"Up to 12GB of data per year including 420 SMS(5p \/ MB data and 4p \/ SMS thereafter)","price":108,"discount":11.9,"monthlyPrice":9,"annualPrice":108},{"title":"Basic: 500MB Data - 12 Months","description":"Up to 500MB of data per monthincluding 20 SMS(5p \/ MB data and 4p \/ SMS thereafter)","price":5.99,"discount":0,"monthlyPrice":5.99,"annualPrice":71.88},{"title":"Basic: 6GB Data - 1 Year","description":"Up to 6GB of data per yearincluding 240 SMS(5p \/ MB data and 4p \/ SMS thereafter)","price":66,"discount":5.86,"monthlyPrice":5.5,"annualPrice":66}]
```

## Running the tests

To run the tests, run the following command (in a Windows command prompt) from the wltest folder:
```
./test
```
Alternatively, you can run
```
./vendor/bin/phpunit --verbose tests
```
To run the tests in a docker container, run the following command, replacing the 'c:/dev/wltest' with the path to the wltest folder on your system.
```
docker run --rm -v c:/dev/wltest:/opt php:8.2-cli php opt/vendor/bin/phpunit opt/tests
```
Test output should be:
```
PHPUnit 9.5.28 by Sebastian Bergmann and contributors.

Runtime:       PHP 8.2.2

.............                                                     13 / 13 (100%)

Time: 00:00.079, Memory: 6.00 MB

OK (13 tests, 16 assertions)
```
