# Fast track

1. ```bash
    git clone git@github.com:kossoy/Laravel-Jetstream-Inertia.git
    cd Laravel-Jetstream-Inertia
    composer install
    touch database/database.sqlite
    cp .env.example .env
    ```

1. edit `.env` with your social apps (FB and Google)
1. create app key `php artisan key:generate`
1. migrate `php artisan migrate`
2. go `npm i && npm run dev && php artisan serve`
