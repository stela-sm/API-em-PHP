<?php
namespace App\Core;

use App\Http\Request;
use App\Http\Response;

class Core{
    public static function dispatch(array $routes){

        $url = '/';
        if (isset($_GET['url']) && $_GET['url'] !== '') {
            $url .= rtrim($_GET['url'], '/');
        }
        $prefixController = 'App\\Controllers\\';
        $routeFound = false;

      
        foreach ($routes as $route) {
            $pattern = preg_replace('/{([\w]+)}/', '([\w-]+)', $route['path']);
            if ($route['path'] !== '/') {
            $pattern = '#^' . rtrim($pattern, '/') . '$#';
                } else {
                $pattern = '#^' . $pattern . '$#';
                };


            if (preg_match($pattern, $url, $matches)) {
                array_shift($matches);

                $routeFound = true;

                if ($route['method'] !== Request::method()) {
                    Response::json([
                        'error' => true,
                        'success' => false,
                        'message' => 'Sorry, method not allowed'
                    ], 405);
                    return;
                }

                [$controller, $action] = explode('@', $route['action']);
                $controller = $prefixController . $controller;

                $extendController = new $controller();
                if (method_exists($extendController, $action)) {
                    $extendController->$action(new Request, new Response, $matches);
                } else {
                    Response::json([
                        'error' => true,
                        'success' => false,
                        'message' => 'Method not found in controller'
                    ], 500);
                }

                return;
            }
        }

        if (!$routeFound) {
            $controller = $prefixController . 'NotFoundController';
            $extendController = new $controller();
            $extendController->index(new Request, new Response);
        }
    }}


