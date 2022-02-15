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
        $router->get('query', [\Guoyuangang\Laravel\QueryController::class, 'index']);
    }
}
