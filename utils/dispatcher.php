<?php

class Dispatcher{

    /*

    */
    public static function route($controller_method,$route,$matcher){

        $url = explode('/',rtrim($route,'/'));
        $url = array_slice($url, 2, count($url));
        
        $url = implode("/", $url);

        list($controller,$method) = explode('@',$controller_method);

        if(strpos($matcher, '{$id}') !== false){

            $route_to_match = $matcher;
            if(!preg_match('#/(\d+)$#',$url,$matcher) && $route !== str_replace('{$id}',$matcher[1],$route_to_match)){
                return;
            }

            $id = $matcher[1];
            $controller = new $controller;
            $controller->{$method}($id);

        }else{

            if($route !== $route_to_match){
                return;
            }

            $controller = new $controller;
            $controller->{$method};
        }

        


        


    }
}

