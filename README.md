# NVD CRUD Generator

CRUD generator for Laravel 5.x

## Installation

1. Download:
    - Run `composer require nvd/crud-generator:dev-master` from the project directory
2. Register the Service Provider in `config/app.php`:
    
    ```
    'providers' => [
        ...
        ...
        Nvd\Crud\Providers\NvdCrudServiceProvider::class,
    ],
    ```
    
3. Publish configuration file and view templates:
    - Run `php artisan vendor:publish` from the project directory
4. Done!
    
    **Note:** NVD CRUD generator uses *Route Model Bindings*. So, for Laravel versions prior to v5.2, you will need to define your [route model bindings](https://laravel.com/docs/5.1/routing#route-model-binding) in `RouteServiceProvider`'s `boot()` method:
    
    ```
    $router->model('phone', 'App\Phone'); // for a table named 'phones'
    ```

## Usage

### Generating CRUD for a Specific Table

Run `php artisan nvd:crud table_name`

Now you can access http://your-site.com/table-name to access the CRUD app. (Here `table-name` refers to the *singular, slugged* version of the table name. e.g.

- Url for the table *phones* will be http://your-site.com/phone
- Url for the table *user_profiles* will be http://your-site.com/user-profile
- Url for the table *people* will be http://your-site.com/person