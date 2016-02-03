# NVD CRUD Generator

CRUD generator for Laravel 5.x
- [Screenshots](#screenshots)
- [Features](#features)
- [Installation](#installation)
- [Usage](#usage)
- [Customizing NVD CRUD Generator](#customizing-nvd-crud-generator)
- [Known Problems and Their Solutions](#known-problems-and-their-solutions)

## Screenshots

![index](https://github.com/engrnvd/laravel-crud-generator-docs/blob/master/images/index.png)
![command](https://github.com/engrnvd/laravel-crud-generator-docs/blob/master/images/command.png)
![edit](https://github.com/engrnvd/laravel-crud-generator-docs/blob/master/images/edit.png)

## Features
- Single page CRUD operations
- Inline, AJAX based table editing
- Form Input types are determined from the table field types
- Easy to set up (No need to install a separate package to handle Html and Forms)
- Easy to customize (Through configurations and custom templates)
- Intelligent route generation
    - Checks overwrite
    - Compatible with Laravel 5.2 ('web' route group)
- Auto route model bindings
- No need to specify field names and types
- Fully functional Controllers and Models
- Support for enum types
- Intelligent search / filtering by any field
- Sort by any field

## Installation

1. Download:
    - Run `composer require nvd/crud-generator` from the project directory
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

## Usage

### Generating CRUD for a Specific Table

Run `php artisan nvd:crud table_name`

Now you can access http://your-site.com/table-name to access the CRUD app. (Here `table-name` refers to the *singular, slugged* version of the table name. e.g.

- Url for the table *phones* will be http://your-site.com/phone
- Url for the table *user_profiles* will be http://your-site.com/user-profile
- Url for the table *people* will be http://your-site.com/person

## Customizing NVD CRUD Generator

### Configuration File

NVD CRUD generator publishes a configuration file `config/crud.php`. You can modify the default settings of the package here. The file is completely documented and hence self explanatory.

### Templates

In case you don't like the default code that is generated for you, the templates for the generated model, controller and the views are located in the `/resources/views/vendor/crud/templates` directory. You are free to edit them as you like.

## Known Problems and Their Solutions

### Layout and Styling

- The default view templates use `vendor.crud.common.app` template as the layout. If you are using a different layout, specify it in the `config/crud.php`
- The default view templates use classes from [Bootstrap](http://getbootstrap.com) and [Font Awesome](https://fortawesome.github.io/Font-Awesome/). You can edit the templates and not use them or you can simply include them in your layout like the one below:

    ```
    <!DOCTYPE html>
    <html>
    <head>
        <title>Laravel Sandbox</title>
        <link rel="stylesheet" href="path/to/bootstrap.min.css">
        <link rel="stylesheet" href="path/to/font-awesome.min.css">
    </head>
    <body>
    <div class="container">
        <div class="content">
            @yield("content")
        </div>
    </div>
    </body>
    </html>
    ```

### Routes Declaration

- For Laravel 5.2, the generator generates routes inside the closure of the route group that applies the 'web' middleware group in the `/app/Http/route.php` out of the box, as follows:

    ```
    Route::group(['middleware' => ['web']], function () {
        Route::resource('user-profile','UserProfileController');
        //
    });
    ```
    
    If, for some reasons, the routes are generated outside the closure, you should move them inside to avoid any exception while creating, editing or deleting a resource. 
    
- You can also tell the generator explicitly where to declare the routes by adding a comment: 'nvd-crud routes go here' and the generator will place the route declaration just after the comment.

    ```
    Route::group(['middleware' => ['web']], function () {
        // nvd-crud routes go here
    });
    ```
    
### Exception: Class "Input" not found

- Although it happens rarely, sometimes the facade "Input" is not declared in `config/app.php`. Declare the alias and the exception will be gone:

```
    'aliases' => [
        //
        'Input'     => Illuminate\Support\Facades\Input::class,
        //
    ],
```

**Note:** Use of Input facade was removed in the latest version. So this error is expected not to appear anyway.

### Primary Keys Other Than 'id' 

- At the moment NVD CRUD generator accepts only 'id' column as the primary key. Having a primary key other than 'id' will be supported in upcoming versions. But right now, you have to follow the convention.

### Laravel Eloquent Conventions

- For smooth operations, Laravel conventions should be followed when creating database tables. If you are facing errors / exceptions mentioning primary keys, table names or timestamps (`created_at`, `updated_at`), please refer to [Laravel docs about conventions](https://laravel.com/docs/5.2/eloquent#eloquent-model-conventions).
