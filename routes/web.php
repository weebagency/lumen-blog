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

$app->get('/', function() use ($app) {
    return response()->json([
        'version' => $app->version(),
        'time' => (new \DateTime())->format('d/m/Y h:i:s e'),
    ]);
});

$app->get('/users', function () use ($app) {
    $users = App\Models\User::all();
    return response()->json($users);
});

$app->get('/users/{name}', function ($name) use ($app) {
    $user = App\Models\UserRepository::getByName($name);
    return response()->json($user);
});

$app->get('/admin/migrations', function() use ($app) {
    return response()->json(app('db')->select("SELECT * FROM migrations"));
});
