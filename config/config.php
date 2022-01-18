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

    'reversible' => true,


    /*
    |--------------------------------------------------------------------------
    | Images Directory
    |--------------------------------------------------------------------------
    |
    | The directory hosting you project's images. The optimizer will 
    | optimize any images it finds in this directory. This must be relative 
    | to the storage directory.
    |
    */

    'images_dir' => 'app/public',
];