<?php

namespace App\Services;

use PDO;
use PDOException;
use Psr\Http\Message\ResponseInterface as Response;

$dotenv = \Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load(); 

class ConnectService
{

    public function connectDataBase()
    {
        // $response = new Response;
        try {
            $mysql_host = getenv('MYSQL_HOST');
            $mysql_user = getenv('MYSQL_USER');
            $mysql_password = getenv('MYSQL_PASSWORD');
            $mysql_database = getenv('MYSQL_DATABASE');
            $conn = new PDO(
                "mysql:host=$mysql_host;dbname=$mysql_database",
                $mysql_user,
                $mysql_password
            );
        } catch (PDOException) {
            return "Could not connect";
        }

        return $conn;
    }
}
