<?php

namespace Guoyuangang\Laravel;

use Illuminate\Support\ServiceProvider;

class QueryServiceProvider extends ServiceProvider
{
    /**
     * Boot the service provider.
     *
     * @return void
     */
    public function boot(): void
    {
        $router = $this->app['router'];
        $router->aliasMiddleware('connection', \Guoyuangang\Laravel\ConnectionMiddleware::class);
        $router->get('query', [\Guoyuangang\Laravel\QueryController::class, 'index'])->middleware('connection');
    }
}
