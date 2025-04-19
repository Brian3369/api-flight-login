<?php 
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
require 'vendor/autoload.php';

class Validate_Tokem {
    private $db;

    function __construct() {
        $db = new Conexion();
        $this->db = $db->getConnection();
    }

    public function getToken(){
        try {
            $header = apache_request_headers();
            if (empty($header['Authorization'])) {
                Flight::halt(403, json_encode([
                    'message' => 'Unauthorized request',
                    'isSuccess' => false
                ]));
            }
            $decodeToken = JWT::decode($header['Authorization'], new Key('example_key', 'HS256'));
            return $decodeToken;
        } catch (Exception $e) {
            Flight::halt(403, json_encode([
                'message' => $e->getMessage(),
                'isSuccess' => false
            ]));
        }
    }
    
    public function validarToken(){
            $info = $this->getToken();
            $stmt = $this->db->prepare('select * from usuario where id = :id');
            $stmt->execute(['id' => $info->data]);
            $datos = $stmt->fetchColumn();
            return $datos;
    }
}