<?php

$config = [

    /*
     * Views that will be generated. If you wish to add your own view,
     * make sure to create a template first in the
     * '/resources/views/crud-templates/views' directory.
     * */
    'views' => [
        'index',
        'edit',
        'show',
        'create',
    ],

    /*
     * Directory containing the templates
     * If you want to use your custom templates, specify them here
     * */
    'templates' => 'vendor.crud.single-page-templates',

    /*
     * Defining the route file. (Customization for Laravel 5.4.*)
     * You can also use your custom route path here.
     * */
    'routePath' => base_path().'/routes/web.php',

];

    /*
     * Layout template used when generating views
     * */
    $config['layout'] = $config['templates'].'.common.app';

return $config;