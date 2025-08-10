<?php

namespace App\Models;

use App\Models\Database;

class User extends Database{


        public static function save(array $data){
            $pdo = self::getConnection();
            $stmt = $pdo->prepare(
                "INSERT INTO users (name, email, password) VALUES (?, ?, ?)"
            );
            $stmt->execute([
            $data['name'],
            $data['email'],
            $data['password']
            ]);

            return $pdo->lastInsertId() > 0 ? true :false;
        }

        public static function authentication(array $data){

        $pdo = self::getConnection();
        $stmt = $pdo->prepare(
            "SELECT* FROM users WHERE email = ?"
        );
        $stmt->execute([
            $data['email']
        ]);

        if ($stmt->rowCount() < 1) return false;
        $user = $stmt->fetch(\PDO::FETCH_ASSOC);
        if(password_verify($data['password'], $user['password']) == false) return false;

        return $user;
    }

    public static function find(int|string $id){
        $pdo = self::getConnection();
        $stmt = $pdo->prepare(
            "SELECT* FROM users WHERE ID_USER = ?"
            );
            $stmt->execute([$id]);  
            return $stmt->fetch(\PDO::FETCH_ASSOC);
            
            }   
    
    public static function update(int|string $id, array $data){
        $pdo = self::getConnection();
        $stmt = $pdo->prepare('Update users SET name = ? WHERE ID_USER = ?');
        $stmt->execute([$data['name'], $id]);
    
        return $stmt->rowCount() > 0 ? true: false;
    }
    public static function delete(int|string $id){
        $pdo = self::getConnection();
        $stmt = $pdo->prepare('DELETE FROM users WHERE ID_USER = ?');
        $stmt->execute([$id]);
        return $stmt->rowCount() > 0 ? true : false;
    
    }

}


?>