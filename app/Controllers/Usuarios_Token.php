<?php
namespace App\Controllers;
use Flight;
use Firebase\JWT\JWT;
use App\Models\Conexion\Conexion;

class Usuarios_Token {
    private $db;

    function __construct() {
        $db = new Conexion();
        $this->db = $db->getConnection();
    }

    public function create_token() {
        $email = Flight::request()->data->email;
        $password = Flight::request()->data->password;
        $stmt = $this->db->prepare('select * from usuario where email = :email and password = :password');
    
        if($stmt->execute([
            'email' => $email,
            'password' => $password
        ])){
            $user = $stmt->fetch();
            $now = strtotime('now');
            $key = $_ENV['JWT_SECRET_KEY'];
            $payload = [
                'exp' => $now + 3600,
                'data' => $user['id'],
            ];
        
            $jwt = JWT::encode($payload, $key, 'HS256');
            $array = ['token' => $jwt];
        }else{
            Flight::json(['isSuccess' => false]);
        }
    
        Flight::json($array);
    }
}