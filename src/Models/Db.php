<?php

namespace App\Models;

use \PDO;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load(); 

class Db
{
    // private $host = 'localhost';
    // private $user = 'root';
    // private $pass = '123';
    // private $dbname = 'wordpress';


    public function connect()
    {
        $mysql_host = getenv('MYSQL_HOST');
        $mysql_user = getenv('MYSQL_USER');
        $mysql_password = getenv('MYSQL_PASSWORD');
        $mysql_database = getenv('MYSQL_DATABASE');
        $conn_str = "mysql:host=$this->$mysql_host;dbname=$this->$mysql_database";
        $conn = new PDO($conn_str, $this->$mysql_user, $this->$mysql_password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        return $conn;
    }
}
