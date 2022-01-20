<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Reversible Optimization
    |--------------------------------------------------------------------------
    |
    | You may set this to true if you want optimizations to be reversible.
    | This enables you to, for example, reverse images back to their 
    | original sizes or clear all caches after making changes to your files.
    |
    */

    'reversible' => false,


    /*
    |--------------------------------------------------------------------------
    | Images Directory
    |--------------------------------------------------------------------------
    |
    | The directories hosting your project's images. The optimizer will 
    | optimize any images it finds in these directories. They must be relative 
    | to the storage directory.
    |
    */

    'images_dirs' => ['app/public'],
];