<?php
namespace App\Models\Conexion;
use Flight;

class Conexion{
    public function getConnection() {
        $conexion_string = $_ENV['DB_CONNECTION_STRING'];
        Flight::register('db', 'PDO', [$conexion_string]);
        return Flight::db();
    }
}
