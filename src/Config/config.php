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

];

    /*
     * Layout template used when generating views
     * */
    $config['layout'] = $config['templates'].'.common.app';

return $config;