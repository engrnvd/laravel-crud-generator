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
     * Define the path to the routes file. This changed in recent
     * versions of Laravel. Set to 'Http/routes.php' for Laravel < 5.4
     * */
    'routesFile' => '../routes/web.php',
];

    /*
     * Layout template used when generating views
     * */
    $config['layout'] = $config['templates'].'.common.app';

return $config;
