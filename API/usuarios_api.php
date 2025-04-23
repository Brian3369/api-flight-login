<?php
require  '../vendor/autoload.php';
use App\Models\Usuarios;
$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__, 1));
$dotenv->load();

// Flight::route('GET /usuarios', function(){
//     try {
//         $users = new Usuarios();
//         $users->get_all();
//     } catch (\Exception $e) {
//         Flight::json([
//             'message' => 'Error: '.$e->getMessage(),
//             'isSuccess' => false
//         ]);
//     }
// });

Flight::route('GET /usuarios_api', function(){
    try {
        $page = Flight::request()->query['pg'] ?? 1; // Valor por defecto 1 si no se envía 'pg'
        $users = new Usuarios();
        $users->get_all($page);
    } catch (\Exception $e) {
        Flight::json([
            'message' => 'Error: '.$e->getMessage(),
            'isSuccess' => false
        ]);
    }
});

Flight::route('GET /usuarios_api/@id', function($id) {
    try {
        $users = new Usuarios(); 
        $users->get_by_id($id);
    } catch (\Exception $e) {
        Flight::json([
            'message' => 'Error: '.$e->getMessage(),
            'isSuccess' => false
        ]);
    }
});

Flight::route('POST /usuarios_api', function(){
    try {
        $users = new Usuarios();
        $users->create();
    } catch (\Exception $e) {
        Flight::json([
            'message' => 'Error: '.$e->getMessage(),
            'isSuccess' => false
        ]);
    }
});

Flight::route('PUT /usuarios_api', function(){
    try {
        $users = new Usuarios();
        $users->update();
    } catch (\Exception $e) {
        Flight::json([
            'message' => 'Error: '.$e->getMessage(),
            'isSuccess' => false
        ]);
    }
});

Flight::route('DELETE /usuarios_api', function(){
    try {
        $users = new Usuarios();
        $users->delete();
    } catch (\Exception $e) {
        Flight::json([
            'message' => 'Error: '.$e->getMessage(),
            'isSuccess' => false
        ]);
    }
});

Flight::start();