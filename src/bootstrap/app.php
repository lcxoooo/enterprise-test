<?php

require_once __DIR__.'/../vendor/autoload.php';

try {
    (new Dotenv\Dotenv(__DIR__.'/../'))->load();
} catch (Dotenv\Exception\InvalidPathException $e) {
    //
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

/*
|--------------------------------------------------------------------------
| Load configure
|--------------------------------------------------------------------------
|
| 加载项目的配置文件
|
*/

$configs = [
    'auth',
    'cors',
    'jwt',
    'database',
    'mail',
    'repository',
    'pubsub',
    'api',
    'micro_service',
    'sms'
];

array_walk($configs, function ($v) use ($app) {
    $app->configure($v);
});

/*
|--------------------------------------------------------------------------
| Class Aliases
|--------------------------------------------------------------------------
|
| This array of class aliases will be registered when this application
| is started. However, feel free to register as many as you wish
|
*/

$aliases = [

];

$app->withFacades(true, $aliases);

/*
|--------------------------------------------------------------------------
| Load the Eloquent library for the application.
|--------------------------------------------------------------------------
|
| This array of class aliases will be registered when this application
| is started. However, feel free to register as many as you wish
|
*/

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
|--------------------------------------------------------------------------
| Register Middleware
|--------------------------------------------------------------------------
|
| Next, we will register the middleware with the application. These can
| be global middleware that run before and after each request into a
| route or middleware that'll be assigned to some specific routes.
|
*/

// $app->middleware([
//    App\Http\Middleware\ExampleMiddleware::class
// ]);

$app->routeMiddleware([
    // 'auth' => App\Http\Middleware\Authenticate::class,
    'cors' => Barryvdh\Cors\HandleCors::class
]);

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


$providers = [
    App\Providers\AppServiceProvider::class,
    //    App\Providers\AuthServiceProvider::class,
    App\Providers\EventServiceProvider::class,

    // Redis
    Illuminate\Redis\RedisServiceProvider::class,

    // Mail
    Illuminate\Mail\MailServiceProvider::class,

    // PubSub
    Takatost\LumenPubSub\PubSubServiceProvider::class,

    //Dingo-Api
    Dingo\Api\Provider\LumenServiceProvider::class,

    //Cors
    Barryvdh\Cors\LumenServiceProvider::class,

    //Repository
    App\Providers\RepositoryServiceProvider::class,

];

array_walk($providers, function ($v) use ($app) {
    $app->register($v);
});

app(\Dingo\Api\Transformer\Factory::class)->setAdapter(function () {
    $fractal = new \League\Fractal\Manager;
    $fractal->setSerializer(new \App\Libraries\Support\ArraySerializer());
    return new \Dingo\Api\Transformer\Adapter\Fractal($fractal, 'include', ',', false);
});

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

$app->group(['namespace' => 'App\Http\Controllers'], function ($app) {
    require __DIR__ . '/../routes/api.php';
});

return $app;
