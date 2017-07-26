<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| The app routes.
|--------------------------------------------------------------------------
|
| Define the root definitions for all routes here.
|
*/

// APIs.
Route::group(
    [
        'middleware' => ['api'],
    ],
    __DIR__.'/routes/api.php'
);

// Web http.
Route::group(
    [
        'middleware' => ['web'],
    ],
    __DIR__.'/routes/web.php'
);

// Admin http.
Route::group(
    [
        'middleware' => ['web', 'auth', 'admin'],
    ],
    __DIR__.'/routes/admin.php'
),
