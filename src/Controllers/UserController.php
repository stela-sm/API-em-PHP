<?php

namespace App\Controllers;

use App\Http\Response;
use App\Http\Request;
use App\Services\UserService;

class UserController
{
    public function store(Request $request, Response $response){
        $body = $request::body();

        $userService = UserService::create($body);

        if (isset($userService['error'])){
            return $response::json([
                'error' => true,
                'sucess' => false,
                'message' => $userService['error'],
            ], 400);

        };
            $response::json([
            'error' => false,
            'sucess' => true,
            'data' => $userService,
        ], 201);
    
    }
    public function login(Request $request, Response $response) {
        $body = $request::body();
        $userService = UserService::auth($body);
        if (isset($userService['error'])){
            return $response::json([
                'error' => true,
                'sucess' => false,
                'message' => $userService['error'],
                ], 400);
        }
            $response::json([
                'error' => false,
                'sucess' => true,
                'data' => $userService,
            ], 201);



    }
    public function fetch(Request $request, Response $response) {

        $autorization  = $request::authorization();
        $userService = UserService::fetch($autorization);
        
        if (isset($userService['unauthorized'])) {
            return $response::json([
                'error' => true,
                'sucess' => false,
                'message' => $userService['unauthorized'],
            ], 401);
        }

        if (isset($userService['error'])) {
            return $response::json([
                'error' => true,
                'sucess' => false,
                'message' => $userService['error'],
            ], 400);
        }
        $response::json([
            'error' => false,
            'sucess' => true,
            'data' => $userService,
        ], 201);
    }
    public function update(Request $request, Response $response) {
        $authorization = $request::authorization();
        $body = $request::body();
        $userService = UserService::update($authorization, $body);
        if (isset($userService['error'])) {
            return $response::json([
                'error' => true,
                'sucess' => false,
                'message' => $userService['error'],
            ], 400);
        }
        $response::json([
            'error' => false,
            'sucess' => true,
            'data' => $userService,
        ], 201);


    }
    public function remove(Request $request, Response $response, array $id) {
        $authorization = $request::authorization();
        
        $userService = UserService::delete($authorization, $id[0]);
        if (isset($userService['error'])) {
            return $response::json([
                'error' => true,
                'sucess' => false,
                'message' => $userService['error'],
            ], 400);
        }
        $response::json([
            'error' => false,
            'sucess' => true,
            'data' => $userService,
        ], 201);
    }
}

?>