# Basic boilerplate
Used for setting up basic websites! Built on top of our core composer package: komma/kms.

## Table of contents
- [Basic boilerplate](#basic-boilerplate)
  * [Initializing](#initializing)
  * [Upgrading](#upgrading)
    + [Minor / patch upgrades:](#minor---patch-upgrades-)
    + [Major upgrades:](#major-upgrades-)
  * [Migrations and seeds](#migrations-and-seeds)
  * [Running seeds for basic.komma.nl](#running-seeds-for-basickommanl)
  * [Development notes](#development-notes)
    + [Environments](#environments)
    + [Running tests](#running-tests)
      - [Unit Testing](#unit-testing)
      - [End to end (browser testing)](#end-to-end--browser-testing-)
    + [Kommandline.js](#kommandlinejs)
  * [Releasing](#releasing)

## Initializing
* Make sure you've installed the following pre-requisites on you computer:
    * PHP >= 7.2.0
    * OpenSSL PHP Extension
    * PDO PHP Extension
    * Mbstring PHP Extension
    * Tokenizer PHP Extension
    * XML PHP Extension
    * Ctype PHP Extension
    * JSON PHP Extension
    * BCMath PHP Extension
    * MySQL 5.5 or higher
    * [node package manager](https://nodejs.org/en/download/) 
    * [Composer](https://getcomposer.org/download/)
* This boilerplate uses the private komma/kms composer package. Make sure that on github you have to correct permissions to at least clone / install the repository. And that you do have an access token for it.
This access token will be asked from you when you install the boilerplate for the first time. Please refer to https://help.github.com/en/packages/publishing-and-managing-packages/about-github-packages#about-tokens for
the latest detailed instructions,

* Run the following commands in the root of the project:
  * ```composer install``` To install the laravel framework and related packages (see composer.json). After installation composer will automatically generate a .env file if one isn't present, will make artisan generate new app key and make artisan run the migrate script.
  * ```npm install``` To install the development / and some frontend packages.
  * ```npm run development``` To transpile sass, javascripts, copy assets for the site end.
  * ```npm run kms-development``` To transpile sass, javascripts, copy assets, for the kms end.
  * ```php artisan vendor:publish --tag=kms-core-config``` To copy config files from the kms (core) package to the boilerplate config directory.
* When your system is in production, setup a cron job to make sure housekeeping, mail processing, etc is executed:
  ```* * * * * php /path-to-your-project/artisan schedule:run >> /dev/null 2>&1```
* Make sure you have a database (see .env file) and that its encoding is set to: ```UTF-8 Unicode (utf8mb4)```
* Open the .env.testing and .env files and make sure the settings are correct, or configure them as needed. The first one is used for phpunit, the second one when you run ```php artisan serve```
* Run the migrations and seeds. Look further in the manual to the Migrations and seeds chapter to discover how.
    
## Upgrading
Each upgrade generally follows a certain pattern depending on if you need to do a major upgrade or minor / patch upgrade.

### Minor / patch upgrades:
* Note down the kms version your boilerplate uses: ```composer show | grep kms```
* When you do a ```composer update```, you will receive the latest kms version with the exception of a major version. Make sure you commit composer.lock.
Example: consider a version constraint of "^3.2.1" for the kms package in composer.json. This means that composer will install the kms versions >= 3.2.1 and < 4.0.0.
* Do a ```npm update``` to update node packages and your package-lock.json. Also commit the package-lock.
* Head to [Helder.komma.nl](http://helder.komma.nl) and read and execute both the upgrade guides on that site for the kms package for the current versions and previous ones. Follow them in order from the version you have noted, trough the installed one.
* Test the app and you are done!

### Major upgrades:
* Note down the kms version your boilerplate uses: ```composer show | grep kms```
* Note down the boilerplate version. It is noted in config/app.php. Or on the kms dashboard / welcome page when you open the boilerplate in the browser.
* Make sure your project has a remote repo setup, pointing to the appropriate boilerplate.
    * ```git remote add boilerplate-readonly https://github.com/KommaGit/Boilerplate-Basic.git```
    * ```git remote set-url boilerplate-readonly --push "Thou shall not push"```
    * ```git remote -v```
    * If you have another remote setup pointing to the same boilerplate, you better remove it. Since previous line set it up as read only. Preventing you from pushing changes back to the boilerplate. Changes flow from kms to boilerplate to end user project. Not the other way around.
* Pull in the latest boilerplate version using git: ```git pull boilerplate-readonly```
* Do a ```composer update``` to update php packages and your composer.lock. You must commit the updated lock file to ensure everyone uses the same package versions as you will do after the upgrade.
* Do a ```npm update``` to update node packages and your package-lock.json. Also commit the package-lock.
* Do a ```npm run dev``` or ```npm run prod``` depending on if you are going to develop further or just upgrading.
* Head to [Helder.komma.nl](http://helder.komma.nl) and:
    * read and execute both the upgrade guides on that site for the kms package and the boilerplate for the current versions your boilerplate uses. This wil take you to the next version.
    * look up both kms and boilerplate versions and repeat the first step. Just as long as there is a newer version.
* Test the app and you are done!

## Migrations and seeds
Run the migrations and seeds to setup your database.
```
php artisan migrate:fresh
php artisan kms:seed
php artisan db:seed
```
Run the migrations and seeds to setup your test database.
```
php artisan migrate:fresh --env=testing
php artisan kms:seed --env=testing
php artisan db:seed --env=testing
```

Remember: The kms seed is the seed from the core package.

## Running seeds for basic.komma.nl
basic.komma.nl is a showcase site that our sales team uses to pitch. They require a
different seed then you need when developing. For them use:
```
php artisan db:seed --class=DatabaseSeederDemo
```

## Development notes
### Environments
the APP_ENV variable in .env must contain a specific value. One of these all in lower case: local, testing, development, production.
* local:
The environment that one should use when developing on its own computer.
* testing:
The environment that test software automatically uses. See for example phpunit.xml and
* development:
The environment that one must used on komma.nl staging servers.
* production:
The environment that should be used when the system is live and being used for customers.

### Running tests
Make sure you have a testing database (see .env.testing) and that its encoding is set to: ```UTF-8 Unicode (utf8mb4)```
#### Unit Testing
Before testing you must make sure that your webserver is using the test database, defined in env.testing. To do so using the
built in webserver you must run: ```php artisan serve --env=testing```

Use the following command to run the unit tests. These will run the unit tests in the tests/Unit folder and tests/Feature folder.
```
vendor/bin/phpunit
```
You can append ```--group=<groupname>``` to specify a group to test. And ```--filter=<testname>``` to run a specific test.
When testing, make sure your database is "fresh". That is, freshly migrated and seeded for best results. If tests fail, refresh your database first.

#### End to end (browser testing)
Run ```cypress open``` in the project root to start the cypres e2e tool. Click on ```run all specs``` to run al tests (also called specs). Or click
individual tests to test those parts only. These test files are located in the folder tests/Cypress/integration. 

When testing, make sure your database is "fresh". That is, freshly migrated and seeded for best results. If tests fail, refresh your database first.

### Kommandline.js
The boilderplates ships with a helper tool called kommandline.js. Start it in the root with ```node kommandline.js```. It can quickly setup a (test) database,
and kill abandoned artisan processes.

## Releasing
* Delete your vendor directory in the root of your project.
* ```composer install --no-dev --optimize-autoloader```
* ```npm run production```
* Publish your project
* Setup a cron job (on the release server) to make sure housekeeping, mail processing, etc is executed: ```* * * * * php /path-to-your-project/artisan schedule:run >> /dev/null 2>&1```


