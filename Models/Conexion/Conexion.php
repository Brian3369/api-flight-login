<?php
class Conexion{
    public function getConnection() {
        Flight::register('db', 'PDO', ['mysql:host=localhost;dbname=login', 'root', 'root']);
        return Flight::db();
    }
}
