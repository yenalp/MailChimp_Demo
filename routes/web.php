<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$app->get('/', function () use ($app) {
    return $app->version();
});

$app->group(
    [
        'prefix' => 'api/v1',
        'middleware' => ['apiV2Result', 'apiContext', 'apiHeaderCheck', 'format'],
    ],
    function() use ($app) {
        $app->group(['middleware' => ['apiV2Auth', 'accessLevel:5']], function() use ($app) {

          $app->group(['prefix' => 'mailchimp'], function() use ($app) {

            $app->group(['prefix' => 'list'], function() use ($app) {
                $app->get('/', 'MailChimpController@lists');
                $app->post('/create', 'MailChimpController@createList');

                $app->group(['prefix' => '{id}/member'], function() use ($app) {
                    $app->post('/create', 'MailChimpController@createListMember');
                    $app->patch('/update/{memberId}', 'MailChimpController@updateListMember');
                  });
            });

          });

        });

        // Public Routes
        $app->group(['prefix' => 'user'], function() use ($app) {
            $app->post('/login', 'UserController@login');
        });
  });
