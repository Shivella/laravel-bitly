Laravel Bitly Package
=====================

A laravel package for generating Bitly urls

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
php artisan vendor:publish
```

Add this in you **.env** file

```
BITLY_ACCESS_TOKEN=your_secret_bitly_access_token
```

Usage
-----

``` php
$url = app('bitly')->getUrl('https://www.google.com/'); // http://bit.ly/nHcn3
````
