Laravel Bitly Package
=====================

A laravel package for generating Bitly short URLs.

For more information see [Bitly](https://bitly.com/)

[![Build Status](https://travis-ci.org/Shivella/laravel-bitly.svg?branch=master)](https://travis-ci.org/Shivella/laravel-bitly) [![Latest Stable Version](https://poser.pugx.org/shivella/laravel-bitly/v/stable)](https://packagist.org/packages/shivella/laravel-bitly) [![License](https://poser.pugx.org/shivella/laravel-bitly/license)](https://packagist.org/packages/shivella/laravel-bitly) [![Total Downloads](https://poser.pugx.org/shivella/laravel-bitly/downloads)](https://packagist.org/packages/shivella/laravel-bitly) [![Coverage Status](https://coveralls.io/repos/github/Shivella/laravel-bitly/badge.svg)](https://coveralls.io/github/Shivella/laravel-bitly)

## Requirements ##

Laravel 5.1 or later


Installation
------------
Installation is a quick 3 step process:

1. Download laravel-bitly using composer
2. Enable the package in app.php
3. Configure your Bitly credentials
4. (Optional) Configure the package facade

### Step 1: Download laravel-bitly using composer

Add shivella/laravel-bitly by running the command:

```
composer require shivella/laravel-bitly
```

### Step 2: Enable the package in app.php

Register the Service in: **config/app.php**

``` php
Shivella\Bitly\BitlyServiceProvider::class,
````

### Step 3: Configure Bitly credentials

```
php artisan vendor:publish --provider="Shivella\Bitly\BitlyServiceProvider"
```

Add this in you **.env** file

```
BITLY_ACCESS_TOKEN=your_secret_bitly_access_token
```

### Step 4 (Optional): Configure the package facade

Register the Bitly Facade in: **config/app.php**

```php
<?php

return [
    'aliases' => [

        'App' => Illuminate\Support\Facades\App::class,
        'Artisan' => Illuminate\Support\Facades\Artisan::class,
        'Auth' => Illuminate\Support\Facades\Auth::class,
        // ...
        'Bitly' => Shivella\Bitly\Facade\Bitly::class,
    ],
    // ...
];
````

Usage
-----

```php
<?php

$url = app('bitly')->getUrl('https://www.google.com/'); // http://bit.ly/nHcn3
````

Or if you want to use facade, add this in your class after namespace declaration:

```php
<?php

use Bitly;
```

Then you can use it directly by calling `Bitly::` like:

```php
<?php

$url = Bitly::getUrl('https://www.google.com/'); // http://bit.ly/nHcn3
````

### Testing

In your unit tests you may use `BitlyClientFake` class instead of regular client.
It will create a fake short URLs using hashing without calling an external REST API, which will speed up your unit tests.
Fake might be setup via DI at your `\Tests\TestCase::createApplication()` implementation:

```php
<?php

namespace Tests;

use Illuminate\Contracts\Console\Kernel;
use Shivella\Bitly\Testing\BitlyClientFake;

trait CreatesApplication
{
    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__.'/../bootstrap/app.php';

        $app->make(Kernel::class)->bootstrap();

        // swap Bitly client by a fake
        $app->singleton('bitly', function () {
            return new BitlyClientFake();
        });

        return $app;
    }
}
```

As an alternative you may use `\Shivella\Bitly\Facade\Bitly::fake()` method to swap regular client by a fake.
