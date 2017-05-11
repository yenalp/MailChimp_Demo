<?php
use Illuminate\Support\Facades\Log;

require_once __DIR__.'/../vendor/autoload.php';

// Fix for getting different env file.
$suffix = '';
if (php_sapi_name() == 'cli') {
    $input = new Symfony\Component\Console\Input\ArgvInput;

    if ($input->hasParameterOption('--env')) {
        $suffix = '.'.$input->getParameterOption('--env');
    }
} elseif (env('APP_ENV')) {
    $suffix = '.'.env('APP_ENV');
}

$env = '.env';
if ($suffix && file_exists(__DIR__.'/../'.$env.$suffix)) {
    $env .= $suffix;
}

try {
    if (isset($testEnv)) {
        (new Dotenv\Dotenv(__DIR__ . '/../', $testEnv))->load();
    } else if (isset($_SERVER['HTTP_HOST']) && explode(':',$_SERVER['HTTP_HOST'])[0] === 'testing.dev') {
        (new Dotenv\Dotenv(__DIR__ . '/../', '.env.testing'))->load();
    } else {
        (new Dotenv\Dotenv(__DIR__.'/../', $env))->load();
    }

} catch (Dotenv\Exception\InvalidPathException $e) {
    Log::error($e);
}

/*
|--------------------------------------------------------------------------
| Create The Application
|--------------------------------------------------------------------------
|
| Here we will load the environment and create the application instance
| that serves as the central piece of this framework. We'll use this
| application as an "IoC" container and router for this framework.
|
*/

$app = new Laravel\Lumen\Application(
    realpath(__DIR__.'/../')
);

$app->withFacades();

$app->withEloquent();

/*
|--------------------------------------------------------------------------
| Register Container Bindings
|--------------------------------------------------------------------------
|
| Now we will register a few bindings in the service container. We will
| register the exception handler and the console kernel. You may add
| your own bindings here if you like or you can make another file.
|
*/

$app->singleton(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    App\Exceptions\Handler::class
);

$app->singleton(
    Illuminate\Contracts\Console\Kernel::class,
    App\Console\Kernel::class
);

/*
|
| Log Entries
|
*/
$app->log_level = env('APP_LOG_LEVEL', 'debug');

/*
|--------------------------------------------------------------------------
| Register Middleware
|--------------------------------------------------------------------------
|
| Next, we will register the middleware with the application. These can
| be global middleware that run before and after each request into a
| route or middleware that'll be assigned to some specific routes.
|
*/

$app->routeMiddleware([
    // 'auth' => App\Http\Middleware\Authenticate::class,
    // 'apiAuth' => App\Http\Middleware\ApiKeyAuthenticate::class,
    'apiV2Result' => App\Http\Middleware\ApiResult::class,
    // 'modelBinder' => App\Http\Middleware\ModelBinder::class,
    'apiContext' => App\Http\Middleware\ApiContext::class,
    'apiV2Auth' => App\Http\Middleware\ApiAuthenticate::class,
    'accessLevel' => App\Http\Middleware\AccessControl\AccessLevelCheck::class,
    'apiHeaderCheck' => App\Http\Middleware\ApiHeaderCheck::class,
    // 'masqueradeCheck' => App\Http\Middleware\AccessControl\MasqueradeCheck::class,
    'format' => App\Http\Middleware\ApiRequestFormat::class,
    // 'refreshToken' => App\Http\Middleware\RefreshApiToken::class,
]);

// $app->routeMiddleware([
//     'auth' => App\Http\Middleware\Authenticate::class,
// ]);

/*
|--------------------------------------------------------------------------
| Register Service Providers
|--------------------------------------------------------------------------
|
| Here we will register all of the application's service providers which
| are used to bind services into the container. Service providers are
| totally optional, so you are not required to uncomment this line.
|
*/

$app->register(App\Providers\AppServiceProvider::class);
// $app->register(Mailchimp\MailchimpServiceProvider::class);
$app->register(App\Providers\CustomMailchimpServiceProvider::class);
// $app->register(App\Providers\AuthServiceProvider::class);
// $app->register(App\Providers\EventServiceProvider::class);

/*
|--------------------------------------------------------------------------
| Load The Application Routes
|--------------------------------------------------------------------------
|
| Next we will include the routes file so that they can all be added to
| the application. This will provide all of the URLs the application
| can respond to, as well as the controllers that may handle them.
|
*/

$app->configure('constants');
$app->configure('auth');
$app->configure('mailchimp');
$app->configure('helpers');


$app->group(['namespace' => 'App\Http\Controllers'], function ($app) {
    require __DIR__.'/../routes/web.php';
});

return $app;
