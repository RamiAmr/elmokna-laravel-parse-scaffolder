# laravel Parse Scaffolder

Laravel Parse Scaffolder Gives you the ability to generate CRUD blade files, Controller and Model for a Parse database.
This package supports basic html and default metronic template.

###Installation

Begin by installing this package through Composer.

```
composer require approcks/laravelParseScaffolder
```

or add it through composer.json file and run composer install

```
"require": {
    //...
    "approcks/laravelParseScaffolder": "*"
    //...
  },
```

then, add your new provider to the providers array of config/app.php:

```
'providers' => [
    // ...
    approcks\laravelParseScaffolder\ParseScaffoldingServiceProvider::class,
    // ...
  ],
```

Finally, add your database credentials int the .env file with the following keys

```
DB_HOST=Your parse IP or domain
DB_PORT=Your parse port
DB_USERNAME=Your parse app_id
DB_PASSWORD=Your parse master_key
```

##Usage

In Your terminal run:

``
php artisan make:crud your-parse-table-name {--template=none}
``

By default if ``--template`` option was not specified or ``--template==none``, basic html elements will be generated.

To generate html elements with metronic support, set ``--template=metronic``

``
php artisan make:crud your-parse-table-name --template=metronic
``

##Todo
* Add Support for Bootstrap 4.
* Add Support for more parse data types (Relations,Array,ACL,Object...).
* Refactor Code.