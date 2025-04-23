<?php
require  '../vendor/autoload.php';
use App\Models\Producto;
$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__, 1));
$dotenv->load();

Flight::route('GET /producto_api', function(){
    try {
        $page = Flight::request()->query['pg'] ?? 1; // Valor por defecto 1 si no se envía 'pg'
        $producto = new Producto();
        $producto->get_all($page);
    } catch (\Exception $e) {
        Flight::json([
            'message' => 'Error: '.$e->getMessage(),
            'isSuccess' => false
        ]);
    }
});

Flight::route('GET /producto_api/@id', function($id) {
    try {
        $producto = new Producto(); 
        $producto->get_by_id($id);
    } catch (\Exception $e) {
        Flight::json([
            'message' => 'Error: '.$e->getMessage(),
            'isSuccess' => false
        ]);
    }
});

Flight::route('POST /producto_api', function(){
    try {
        $producto = new Producto();
        $producto->create();
    } catch (\Exception $e) {
        Flight::json([
            'message' => 'Error: '.$e->getMessage(),
            'isSuccess' => false
        ]);
    }
});

Flight::route('PUT /producto_api', function(){
    try {
        $producto = new Producto();
        $producto->update();
    } catch (\Exception $e) {
        Flight::json([
            'message' => 'Error: '.$e->getMessage(),
            'isSuccess' => false
        ]);
    }
});

Flight::route('DELETE /producto_api', function(){
    try {
        $producto = new Producto();
        $producto->delete();
    } catch (\Exception $e) {
        Flight::json([
            'message' => 'Error: '.$e->getMessage(),
            'isSuccess' => false
        ]);
    }
});

Flight::start();