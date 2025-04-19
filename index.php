<?php
require 'vendor/autoload.php';
require 'Models/Conexion/Conexion.php';
require 'Models/Usuarios.php';
require 'Controllers/Usuarios_Token.php';

Flight::route('POST /auth', function(){
    try {
        $token = new Usuarios_Token();
        $token->create_token();
    } catch (Exception $e) {
        Flight::json([
            'message' => 'Error: '.$e->getMessage(),
            'isSuccess' => false
        ]);
    }
});

Flight::route('GET /usuarios', function(){
    try {
        $users = new Usuarios();
        $users->get_all();
    } catch (Exception $e) {
        Flight::json([
            'message' => 'Error: '.$e->getMessage(),
            'isSuccess' => false
        ]);
    }
});

Flight::route('GET /usuarios/@id', function($id) {
    try {
        $users = new Usuarios(); 
        $users->get_by_id($id);
    } catch (Exception $e) {
        Flight::json([
            'message' => 'Error: '.$e->getMessage(),
            'isSuccess' => false
        ]);
    }
});

Flight::route('POST /usuarios', function(){
    try {
        $users = new Usuarios();
        $users->create();
    } catch (Exception $e) {
        Flight::json([
            'message' => 'Error: '.$e->getMessage(),
            'isSuccess' => false
        ]);
    }
});

Flight::route('PUT /usuarios', function(){
    try {
        $users = new Usuarios();
        $users->update();
    } catch (Exception $e) {
        Flight::json([
            'message' => 'Error: '.$e->getMessage(),
            'isSuccess' => false
        ]);
    }
});

Flight::route('DELETE /usuarios', function(){
    try {
        $users = new Usuarios();
        $users->delete();
    } catch (Exception $e) {
        Flight::json([
            'message' => 'Error: '.$e->getMessage(),
            'isSuccess' => false
        ]);
    }
});

Flight::start();