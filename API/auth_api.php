<?php
require  '../vendor/autoload.php';
use App\Controllers\Usuarios_Token;
$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__, 1));
// $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();



Flight::route('POST /auth_api', function(){
    try {
        $token = new Usuarios_Token();
        $token->create_token();
    } catch (\Exception $e) {
        Flight::json([
            'message' => 'Error: '.$e->getMessage(),
            'isSuccess' => false
        ]);
    }
});

Flight::start();