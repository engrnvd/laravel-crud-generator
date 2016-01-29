<?php

return [

    /*
     * Layout template used when generating views
     * */
    'layout' => 'vendor.crud.common.app',

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

];
