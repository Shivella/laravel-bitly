### Laravel Bitly Package ###

A laravel package for generating Bitly urls

For more information see [Bitly](https://bitly.com/)

[![Build Status](https://travis-ci.org/Shivella/laravel-bitly.svg?branch=master)](https://travis-ci.org/Shivella/laravel-bitly) [![Latest Stable Version](https://poser.pugx.org/shivella/laravel-bitly/v/stable)](https://packagist.org/packages/shivella/laravel-bitly) [![License](https://poser.pugx.org/shivella/laravel-bitly/license)](https://packagist.org/packages/shivella/laravel-bitly) [![Total Downloads](https://poser.pugx.org/shivella/laravel-bitly/downloads)](https://packagist.org/packages/shivella/laravel-bitly) [![Coverage Status](https://coveralls.io/repos/github/Shivella/laravel-bitly/badge.svg)](https://coveralls.io/github/Shivella/laravel-bitly)

## Requirements ##

Laravel 5.1 or later

## Installation ##

Require this package with composer:

```
composer require shivella/laravel-bitly
```

## Setup ##

Register the Service in: **config/app.php**

```
Shivella\Bitly\BitlyServiceProvider::class,
```

Publish vendor config

```
php artisan vendor:publish
```

Add this in you **.env** file

```
BITLY_ACCESS_TOKEN=your_secret_bitly_access_token
```
