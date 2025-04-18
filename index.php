<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require 'vendor/autoload.php';

function getToken(){
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

function validarToken(){
        $info = getToken();
        $db = Flight::db();
        $stmt = $db->prepare('select * from usuario where id = :id');
        $stmt->execute(['id' => $info->data]);
        $datos = $stmt->fetchColumn();
        return $datos;
}

Flight::register('db', 'PDO', ['mysql:host=localhost;dbname=login', 'root', 'root']);

Flight::route('GET /usuarios', function () {
    $db = Flight::db();
    $stmt = $db->prepare('select * from usuario');
    $stmt->execute();
    $datos =$stmt->fetchAll();
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
});

Flight::route('GET /usuarios/@id', function ($id) {

    $db = Flight::db();
    $stmt = $db->prepare('select * from usuario where id = :id');
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
        Flight::json(['message' => 'Usuario no encontrado', 'isSuccess' => false]);
    }
});

Flight::route('POST /auth', function () {
    $db = Flight::db();
    $email = Flight::request()->data->email;
    $password = Flight::request()->data->password;
    $stmt = $db->prepare('select * from usuario where email = :email and password = :password');

    if($stmt->execute([
        'email' => $email,
        'password' => $password
    ])){
        $user = $stmt->fetch();
        $now = strtotime('now');
        $key = 'example_key';
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
});

Flight::route('POST /usuarios', function () {
    if(!validarToken()){
        Flight::halt(403, json_encode([
            'message' => 'Token no valido',
            'isSuccess' => false
        ]));
    }
    $db = Flight::db();
    $nombre = Flight::request()->data->nombre;
    $apellido = Flight::request()->data->apellido;
    $email = Flight::request()->data->email;
    $password = Flight::request()->data->password;
    $stmt = $db->prepare('insert into usuario (nombre, apellido, email, password) values (:nombre, :apellido, :email, :password)');
    if($stmt->execute([
        'nombre' => $nombre,
        'apellido' => $apellido,
        'email' => $email,
        'password' => $password
    ])){
        Flight::json([
            'data' => [
                'id' => $db->lastInsertId(),
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
});

Flight::route('PUT /usuarios', function () {
    if(!validarToken()){
        Flight::halt(403, json_encode([
            'message' => 'Token no valido',
            'isSuccess' => false
        ]));
    }
    $db = Flight::db();
    $id = Flight::request()->data->id;
    $nombre = Flight::request()->data->nombre;
    $apellido = Flight::request()->data->apellido;
    $email = Flight::request()->data->email;
    $password = Flight::request()->data->password;
    $stmt = $db->prepare('update usuario set nombre = :nombre, apellido = :apellido, email = :email, password = :password where id = :id');
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
});

Flight::route('DELETE /usuarios', function () {
    if(!validarToken()){
        Flight::halt(403, json_encode([
            'message' => 'Token no valido',
            'isSuccess' => false
        ]));
    }
    $db = Flight::db();
    $id = Flight::request()->data->id;
    $stmt = $db->prepare('delete from usuario where id = :id');
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
});

Flight::start();