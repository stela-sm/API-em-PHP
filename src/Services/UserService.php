<?php

namespace App\Services;

use App\Utils\Validator;
use App\Models\User;
use App\Utils\ErrorFilter;
use PDOException;
use App\Http\JWT;

class UserService
{
    public static function create(array $data){
        try{
            $fields = Validator::validate([
                'name' => $data['name']  ?? '',
                'email' => $data['email'] ?? '',
                'password' => $data['password'] ?? '',

            ]);

            $fields['password'] = password_hash($fields["password"], PASSWORD_DEFAULT);
            $user = User::save($fields);

            if(!$user) return ['error' => 'Error creating user'];


            return "User created successfully!";

        }
        catch (\PDOException $e){
            $error = ErrorFilter::filter($e->errorInfo);
            return $error;
        }
        
        catch(\Exception $e){
            return['error' => $e->getMessage()];

        }
    }

    public static function auth(array $data)
    {
        try {
            $fields = Validator::validate([
                'email'    => $data['email']    ?? '',
                'password' => $data['password'] ?? '',
            ]);

            $user = User::authentication($fields);

            if (!$user) return ['error' => 'Sorry, we could not authenticate you.'];
            
            return JWT::generate($user);

        } catch (PDOException $e) {
            if ($e->errorInfo[0] === '08006') return ['error' => 'Sorry, we could not connect to the database.'];
            return ['error' => $e->errorInfo[0]];
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    public static function fetch(mixed $authorization){
        try {   
            if(isset($authorization['error'])){
                return ['unauthorized' => $authorization['error']];
            };
            $userFromJwt = JWT::verify($authorization);
            if(!$userFromJwt) return ['error' => 'Sorry, we could not authenticate you.'];
            
            $user = User::find($userFromJwt['ID_USER']);

            if (!$user) return ['error' => 'Sorry, we could not find the user.'];
            
            return $user;

    } catch (PDOException $e) {
            if ($e->errorInfo[0] === '08006') return ['error' => 'Sorry, we could not connect to the database.'];
            return ['error' => $e->errorInfo[0]];
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
}

    public static function update(mixed $authorization, mixed $user){
        try{
            if(isset($authorization['error'])) return ['unauthorized' => $authorization['error']];
            $userFromJwt = JWT::verify($authorization);
            if(!$userFromJwt) return ['error' => 'Sorry, we could not authenticate you'];

            $fields = Validator::validate([
                'name' => $user['name'] ?? ''
            ]);
            $user = User::update($userFromJwt['ID_USER'],$fields);

            if(!$user) return ['error' => 'Sorry, we could not update the user.'];

            return "User updated succesfully!";

        } catch (PDOException $e) {
            if ($e->errorInfo[0] === '08006') return ['error' => 'Sorry, we could not connect to the database.'];
            return ['error' => $e->errorInfo[0]];
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    public static function delete(mixed $authorization, int|string $id){
        try{
            if(isset($authorization['error'])) return ['unauthorized' => $authorization['error']];
            $userFromJwt = JWT::verify($authorization);
            if(!$userFromJwt) return ['error' => 'Sorry, we could not authenticate you'];
            $user = User::delete($id);
            if(!$user) return ['error' => 'Sorry, we could not delete the user.'];
            return "User deleted succesfully!";
            } catch (PDOException $e) {
                if ($e->errorInfo[0] === '08006') return ['error' =>' Sorry, we could not connect to the database.'];
                return ['error' => $e->errorInfo[0]];
                } catch (\Exception $e) {
                    return ['error' => $e->getMessage()];
                    }
    }
}

?>