<?php
require 'Validate_Tokem.php';

class Usuarios{
    private $db;
    private $token;

    function __construct() {
        $db = new Conexion();
        $this->db = $db->getConnection();
        $this->token = new Validate_Tokem();
    }

    public function get_all() {
        $stmt = $this->db->prepare('select * from usuario');
        $stmt->execute();
        $datos = $stmt->fetchAll();
        $usuarios = [];
    
        foreach($datos as $row){
            $usuarios[] = [
                'id' => $row['id'],
                'nombre' => $row['nombre'],
                'password' => $row['password'],
                'email' => $row['email'],
                'apellido' => $row['apellido'],
            ];
        }
    
        Flight::json([
            'datos' => $usuarios,
            'message' => 'Lista de usuarios',
            'isSuccess' => true
        ]);
    }

    public function get_by_id($id) {
        $stmt = $this->db->prepare('select * from usuario where id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $datos = $stmt->fetch();
        if ($datos) {
            $usuario = [
                'id' => $datos['id'],
                'nombre' => $datos['nombre'],
                'password' => $datos['password'],
                'email' => $datos['email'],
                'apellido' => $datos['apellido'],
            ];
            $datos_usuario = [$usuario];
            Flight::json([
                'datos' => $datos_usuario,
                'message' => 'Usuario encontrado',
                'isSuccess' => true
            ]);
        } else {
            Flight::json([
                'message' => 'Usuarios no encontrado',
                'isSuccess' => false
            ]);
        }
    }

    public function create() {
        if(!$this->token->validarToken()){
            Flight::halt(403, json_encode([
                'message' => 'Token no valido',
                'isSuccess' => false
            ]));
        }
        $nombre = Flight::request()->data->nombre;
        $apellido = Flight::request()->data->apellido;
        $email = Flight::request()->data->email;
        $password = Flight::request()->data->password;
        $stmt = $this->db->prepare('insert into usuario (nombre, apellido, email, password) values (:nombre, :apellido, :email, :password)');
        if($stmt->execute([
            'nombre' => $nombre,
            'apellido' => $apellido,
            'email' => $email,
            'password' => $password
        ])){
            Flight::json([
                'data' => [
                    'id' => $this->db->lastInsertId(),
                    'nombre' => $nombre,
                    'apellido' => $apellido,
                    'email' => $email,
                    'password' => $password
                ],
                'message' => 'Usuario creado correctamente',
                'isSuccess' => true
            ]);
        }else{
            Flight::json(['isSuccess' => false]);
        }
    }

    public function update() {
        if(!$this->token->validarToken()){
            Flight::halt(403, json_encode([
                'message' => 'Token no valido',
                'isSuccess' => false
            ]));
        }
        $id = Flight::request()->data->id;
        $nombre = Flight::request()->data->nombre;
        $apellido = Flight::request()->data->apellido;
        $email = Flight::request()->data->email;
        $password = Flight::request()->data->password;
        $stmt = $this->db->prepare('update usuario set nombre = :nombre, apellido = :apellido, email = :email, password = :password where id = :id');
        if($stmt->execute([
            'id' => $id,
            'nombre' => $nombre,
            'apellido' => $apellido,
            'email' => $email,
            'password' => $password
        ])){
            Flight::json([
                'data' => [
                    'id' => $id,
                    'nombre' => $nombre,
                    'apellido' => $apellido,
                    'email' => $email,
                    'password' => $password
                ],
                'message' => 'Usuario actualizado correctamente',
                'isSuccess' => true
            ]);
        }else{
            Flight::json(['isSuccess' => false]);
        }
    }

    public function delete() {
        if(!$this->token->validarToken()){
            Flight::halt(403, json_encode([
                'message' => 'Token no valido',
                'isSuccess' => false
            ]));
        }
        $id = Flight::request()->data->id;
        $stmt = $this->db->prepare('delete from usuario where id = :id');
        if($stmt->execute(['id' => $id])){
            Flight::json([
                'data' => [
                    'id' => $id
                ],
                'message' => 'Usuario eliminado correctamente',
                'isSuccess' => true
            ]);
        }else{
            Flight::json(['isSuccess' => false]);
        }
    }
}