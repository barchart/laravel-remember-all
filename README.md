## Laravel Remember All Devices

### Note: This repo has been archived as Laravel supports logging out a single device since Laravel 6.0 via `logoutCurrentDevice`. More information in the PR here: https://github.com/laravel/framework/pull/29397

Laravel currently only supports the "remember me" feature for one device. When you log in to multiple devices, then log out of one, you will be logged out of all. This solves that by storing the tokens in a separate table.

There is a current proposal to put this into Laravel core, but we needed this now: https://github.com/laravel/ideas/issues/971

### Setup
Install via composer:
```
composer require barchart/laravel-remember-all
```

Migrate the new `remember_tokens` table:
```
php artisan migrate
```

Update your authentication guard:
```php
'guards' => [
    'web' => [
        'driver' => 'rememberall',
        'provider' => 'users',
        'expire' => 10080, // optional token expiration time, in minutes (7 days is the default)
    ],
],
```

#### Eloquent
For Eloquent, you also need to update your model. Just replace Laravel's default `User` model with the following:
```php
use Barchart\Laravel\RememberAll\User as Authenticatable;

class User extends Authenticatable
{

}
```

If you're not extending off of Laravel's base `User` model and instead extending directly off of Eloquent's `Model`, replace Laravel's default `Authenticatable` and `AuthenticatableContract` with the following:
```php
use Barchart\Laravel\RememberAll\EloquentAuthenticatable as Authenticatable;
use Barchart\Laravel\RememberAll\Contracts\Authenticatable as AuthenticatableContract;

class User extends Model implements AuthenticatableContract
{
    use Authenticatable;
}
```
