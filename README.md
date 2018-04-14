# laravel-referral

A Referral System With Laravel

[![StyleCI](https://styleci.io/repos/115917817/shield?branch=master)](https://styleci.io/repos/115917817)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/questocat/laravel-referral/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/questocat/laravel-referral/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/questocat/laravel-referral/badges/build.png?b=master)](https://scrutinizer-ci.com/g/questocat/laravel-referral/build-status/master)
[![Packagist](https://img.shields.io/packagist/l/doctrine/orm.svg)](https://packagist.org/packages/questocat/laravel-referral)

## Installation

Via [Composer](https://getcomposer.org) to add the package to your project's dependencies:

```bash
$ composer require questocat/laravel-referral "~1.0"
```

First add service providers into the config/app.php

```php
\Questocat\Referral\ReferralServiceProvider::class,
```

Publish the migrations

```bash
$ php artisan vendor:publish --provider="Questocat\Referral\ReferralServiceProvider" --tag="migrations"
```

Publish the config

```bash
$ php artisan vendor:publish --provider="Questocat\Referral\ReferralServiceProvider" --tag="config"
```

## Setup the model

Add UserReferral Trait to your User model.

```php
use Questocat\Referral\Traits\UserReferral

class User extends Model
{
    use UserReferral;
}
```

## Usage

Assigning CheckReferral Middleware To Routes.

```php
// Within App\Http\Kernel Class...

protected $routeMiddleware = [
    'referral' => \Questocat\Referral\Http\Middleware\CheckReferral::class,
];
```

Once the middleware has been defined in the HTTP kernel, you may use the middleware method to assign middleware to a route:

```php
Route::get('/', 'HomeController@index')->middleware('referral');
```

Now you can create the user:

```php
$user = new App\User();
$user->name = 'zhengchaopu';
$user->password = bcrypt('password');
$user->email = 'zhengchaopu@gmail.com';
$user->save();

// Or

$data = [
    'name' => 'zhengchaopu',
    'password' => bcrypt('password'),
    'email' => 'zhengchaopu@gmail.com',
];

App\User::create($data);
```

Get the referral link:

```php
$user = App\User::findOrFail(1);

{{ $user->getReferralLink() }}
```


## License

Licensed under the [MIT license](https://github.com/questocat/laravel-referral/blob/master/LICENSE).
