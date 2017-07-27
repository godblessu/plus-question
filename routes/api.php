<?php

use Illuminate\Support\Facades\Route;
use SlimKit\PlusQuestion\API2\Controllers as API2;
use Illuminate\Contracts\Routing\Registrar as RouteRegisterContract;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['prefix' => 'api/v2'], function (RouteRegisterContract $api) {

    // Question topics.
    // @Route /api/v2/question-topics
    $api->group(['prefix' => 'question-topics'], function (RouteRegisterContract $api) {

        // Question topics
        // @Get /api/v2/quest-topics
        $api->get('/', API2\TopicController::class.'@index');
    });

    // @Auth api.
    // @Route /api/v2
    $api->group(['middleware' => 'auth:api'], function (RouteRegisterContract $api) {

        // User
        // @Route /api/v2/user
        $api->group(['prefix' => 'user'], function (RouteRegisterContract $api) {

            // Starred question topics.
            // @Route /api/v2/user/question-topics
            $api->group(['prefix' => 'question-topics'], function (RouteRegisterContract $api) {

                // Get follow question topics of the authenticated user.
                // @Get /api/v2/user/question-topics
                $api->get('/', API2\TopicUserController::class.'@index');

                // Follow a question topics.
                // @Put /api/v2/user/question-topics/:topic
                $api->put('/{topic}', API2\TopicUserController::class.'@store');
            });
        });
    });
});
