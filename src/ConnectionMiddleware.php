<?php

namespace Guoyuangang\Laravel;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class ConnectionMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if($request->get('connection')){
            DB::setDefaultConnection($request->get('connection'));
            return $next($request);
        }else{
            $result = Config::get('database.connections');
            print_r(array_keys($result));exit;
        }
    }
}
