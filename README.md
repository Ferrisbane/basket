# Basket
A small PHP basket package that packs a punch.

Yes docblocks will be added

- [Examples](#examples)
- [Installation](#installation)
- [Setup](#setup)
- [Usage](#usage)

## Examples

Examples to come


## Installation
This package requires PHP 5.6+ (has not been tested on lower versions).

The package works on Windows and Linux webservers (not been tested on Mac)

To install through composer you can either use `composer require ferrisbane/basket` (while inside your project folder) or include the package in your `composer.json`.

```php
"ferrisbane/basket": "0.1.*"
```

Then run either `composer install` or `composer update` to download the package.

To use the package with Laravel 5 add the ShortDB service provider to the list of service providers in `config/app.php`.

```php
'providers' => [
    ...

    Ferrisbane\Basket\Laravel5ServiceProvider::class

    ...
];
```

Then use `php artisan vendor:publish` to publish the config.

If you have changed the namespace of your project you can define it inside `config/basket.php`

`'namespace' => 'App',`


## Setup

Docs to come