<?php

namespace App\Models;

use PDO;

class Database
{
    public static function getConnection(){
        $pdo = new PDO("mysql:host=localhost;port=80;dbname=php_api", "root", "031206");
        return $pdo;
    }

}



?>