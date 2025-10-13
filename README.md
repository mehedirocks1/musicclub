composer create-project laravel/laravel example-app "11.*"
laravel new club11
composer require laravel/breeze --dev
php artisan breeze:install livewire
php artisan migrate
npm install
npm run build
composer require filament/filament -W
php artisan filament:install --panels








# 0) New project (skip if already created)
composer create-project laravel/laravel poj-music "12.*"
cd poj-music

cp .env.example .env
php artisan key:generate

# 1) Filament 4.x
composer require filament/filament -W
php artisan filament:install --panels
php artisan make:filament-user   # create first admin

# 2) Spatie Permission
composer require spatie/laravel-permission
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
php artisan migrate

# 3) Filament Shield (auto permissions for Filament)
composer require bezhansalleh/filament-shield -W
php artisan shield:install
php artisan shield:generate

# 4) Nwidart Modules
composer require nwidart/laravel-modules
php artisan vendor:publish --provider="Nwidart\Modules\LaravelModulesServiceProvider" --tag="config"

# 5) Create Members module
php artisan module:make Members
