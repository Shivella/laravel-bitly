### Laravel Bitly Package ###

A laravel package for generating Bitly urls


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
