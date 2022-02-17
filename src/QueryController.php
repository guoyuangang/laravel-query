<?php

namespace Guoyuangang\Laravel;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;

class QueryController extends Controller
{
    public function index()
    {
        $connection = Request::get('connection');

        if(empty(Request::get('database'))){
            $result = DB::select("SHOW DATABASES;");
            print_r(array_column($result,'Database'));exit;
        }

        Config::set("database.connections.$connection.url",str_replace('scmbase',Request::get('database'),Config::get("database.connections.$connection.url")));
        DB::setDefaultConnection($connection);

        if(empty(Request::get('table'))){
            $result = DB::select("SHOW TABLES;");
            print_r(array_column($result,array_key_first(get_object_vars(current($result)))));exit;
        }

        $query = DB::table(Request::get('database').'.'.Request::get('table'));
        foreach (Request::all() as $column=>$value)
        {
            if(in_array($column,[
                'connection',
                'database',
                'table',
                'select',
                'groupBy',
                'orderBy',
                'limit',
            ])) continue;
            if(substr($column,-3)=='_in'){
                $column = substr($column,0,-3);
                $query->whereIn($column,explode(',',$value));
                continue;
            }
            $operator = '=';
            if(substr($column,-5)=='_time'){
                $column = substr($column,0,-5);
                $value = strtotime($value);
            }
            if(substr($column,-6)=='_start'){
                $operator = '>=';
                $column = substr($column,0,-6);
            }
            if(substr($column,-4)=='_end'){
                $operator = '<=';
                $column = substr($column,0,-4);
            }
            if(substr($column,-5)=='_like'){
                $operator = 'like';
                $column = substr($column,0,-5);
                $value = "%$value%";
            }
            $query->where($column,$operator,$value);
        }
        if(Request::get('groupBy')){
            $query->groupBy(...explode(',',Request::get('groupBy')));
        }
        if(Request::get('orderBy')){
            $query->orderBy(...explode(',',Request::get('orderBy')));
        }
        $limit = Request::get('limit') ?: 100;
        $query->limit($limit);
        if(Request::get('select')){
            $selectFields = explode(',',Request::get('select'));
            if(count($selectFields)==1){
                $data = $query->pluck(Request::get('select'))->toArray();
            }else{
                $data = $query->get($selectFields)->toArray();
            }
        }else{
            $data = $query->get()->toArray();
        }
        print_r($data);exit;
    }
}
