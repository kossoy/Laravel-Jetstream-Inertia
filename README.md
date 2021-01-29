# Using Laravel and Jetstream with Inertia stack

> [Jetstream with Inertia stack](https://jetstream.laravel.com/2.x/stacks/inertia.html)

## TL;DR

> [I want it FAST!!!1](Lazy.md) (still need [Prerequisites](#prerequisites))

## Prerequisites

- PHP 7.4
- Composer
- Node 12.18
- Facebook and Google developer app/project set up

## Create Laravel project

```bash
composer create-project --prefer-dist laravel/laravel NameOfTheProject
cd NameOfTheProject
```

## Config DB

- Copy `.env.example` to `.env`
- In `.env` replace with `DB_CONNECTION=sqlite` all `DB_`.* section
- Create db file with `touch` or any other tool

```bash
touch database/database.sqlite
# in Windows, use git bash
```

## Setup FB and Google auth data

- In `.env` append

```dotenv
FACEBOOK_APP_ID=<app id>
FACEBOOK_APP_SECRET=<app secret>
FACEBOOK_REDIRECT=http://localhost:8000/callback/facebook

GOOGLE_CLIENT_ID=<client id>.apps.googleusercontent.com
GOOGLE_CLIENT_SECRET=<client secret>
GOOGLE_REDIRECT_URI=http://localhost:8000/callback/google
```

- In `config/services.php`

```php
'facebook' => [
    'client_id' => env('FACEBOOK_APP_ID'),
    'client_secret' => env('FACEBOOK_APP_SECRET'),
    'redirect' => env('FACEBOOK_REDIRECT'),
],

'google' => [
    'client_id' => env('GOOGLE_CLIENT_ID'),
    'client_secret' => env('GOOGLE_CLIENT_SECRET'),
    'redirect' => env('GOOGLE_REDIRECT_URI')
],
```

## Setup *JetStream* with *Socialite*

```bash
 composer require laravel/jetstream
 php artisan jetstream:install inertia
 
 composer require laravel/socialite
```

### Edit `config/app.php`

- Add Socialite service provider

```php
 'providers' => [
    /*
     * Package Service Providers...
     */
    Laravel\Socialite\SocialiteServiceProvider::class,
]
 ```

- Add Socialite Facade to aliaces

```php
'Socialite' => Laravel\Socialite\Facades\Socialite::class,
```

### Add migration for social login fields

```bash
 php artisan make:migration add_social_login_field
 ```

### Edit migration file:

- In `database/migrations`

[TIMESTAMP_add_social_login_field.php](https://gist.github.com/kossoy/c7a937ac3335f4ab879b96bffeef1cbd#file-timestamp_add_social_login_field-php)

### Add fields to the User Model

- In `Models/User.php`

```php
protected $fillable = [
    'name',
    'email',
    'password',
    'social_id',       // Add this
    'social_type',     // two fields
];
```

### Create Google controller

 ```bash
 php artisan make:controller GoogleSocialiteController
 ```

- Google controller class
  file [Http/Controllers/GoogleSocialiteController.php](https://gist.github.com/kossoy/c7a937ac3335f4ab879b96bffeef1cbd#file-googlesocialitecontroller-php)

### Create Facebook controller

 ```bash
 php artisan make:controller FacebookSocialiteController
 ```

- Facebook controller class
  file [Http/Controllers/FacebookSocialiteController.php](https://gist.github.com/kossoy/c7a937ac3335f4ab879b96bffeef1cbd#file-facebooksocialitecontroller-php)

### Create Routes

- In `routes/web.php`

```php
use App\Http\Controllers\FacebookSocialiteController;
use App\Http\Controllers\GoogleSocialiteController;

// And in the end append login and callback routes
// Google login
Route::get('auth/google', [GoogleSocialiteController::class, 'redirectToGoogle']);
Route::get('callback/google', [GoogleSocialiteController::class, 'handleCallback']);

// Facebook login
Route::get('auth/facebook', [FacebookSocialiteController::class, 'redirectToFB']);
Route::get('callback/facebook', [FacebookSocialiteController::class, 'handleCallback']);
```

### Add FB and Google login buttons

- In `resources/js/Pages/Auth/Login.vue`

after

```vue
...
Login
</jet-button>
```

add

```vue 
<a type="button" href="auth/google"
   class="ml-2 inline-flex items-center px-4 py-2 bg-red-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-900 focus:outline-none focus:border-red-900 focus:shadow-outline-gray transition ease-in-out duration-150">
    Google
</a>

<a type="button" href="auth/facebook"
   class="ml-2 inline-flex items-center px-4 py-2 bg-blue-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:shadow-outline-gray transition ease-in-out duration-150">
    Facebook
</a>
```

## Execute migrations

```bash
 php artisan migrate
```

## Run the dev servers (UI build and php server)

```bash
 npm install
 npm run dev
 php artisan serve
```

# PROFIT!!!1

> If you don't have git-bash
>
> ```bat
> # Windows cmd:
> type > database/database.sqlite
> # PowerShell:
> Out-File -FilePath database/database.sqlite
> 
> ```

