<?php
// Here you can initialize variables that will be available to your tests
// See here for details: http://codeception.com/docs/10-WebServices#REST
$testEnv = '.env.testing';

$app = require __DIR__.'/../../bootstrap/app.php';

use Illuminate\Support\Facades\Artisan;

$app->instance('request', new \Illuminate\Http\Request);

Artisan::call('migrate:refresh', ['--env' => 'testing']);
Artisan::call('db:seed');
