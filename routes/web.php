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

$router->get('/', function () use ($router) {
    return 'Reward API using ' . $router->app->version();
});

$router->group(['prefix' => 'api/v1'], function ($app) {
    $app->post('reward', 'RewardController@createReward');
    $app->put('reward/{id}', 'RewardController@updateReward');
    $app->delete('reward/{id}', 'RewardController@deleteReward');
    $app->get('rewards', 'RewardController@index');
    $app->get('reward/{id}', 'RewardController@getReward');
    $app->post('assign-reward', 'RewardController@assignReward');
    $app->get('reward/transactions/user/{id}', 'RewardController@rewardTransactionsByUserId');
});
