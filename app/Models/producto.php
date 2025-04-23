<?php
namespace App\Models;
use Flight;
use App\Models\Conexion\Conexion;
use App\Controllers\Validate_Tokem;
use App\Models\interface\IRepositorio;

class Producto implements IRepositorio{
    private $db;
    private $token;

    function __construct() {
        $db = new Conexion();
        $this->db = $db->getConnection();
        $this->token = new Validate_Tokem();
    }

    public function get_all($page) {
        if(!isset($page)){
            $page = 1;
        }

        $stmt = $this->db->prepare('select * from producto');
        $stmt->execute();
        ///////////////////////////////////////////
        $total = $stmt->rowCount(); 
        $total_per_page = 10; 
        $pages = ceil($total / $total_per_page); 
        $offset = ($page - 1) * $total_per_page; 
        $stmt2 = $this->db->prepare("select * from producto limit $offset, $total_per_page"); 
        $stmt2->execute();
        ///////////////////////////////////////////
        $datos = $stmt2->fetchAll();

        $productos = [];
    
        foreach($datos as $row){
            $productos[] = [
                'id_producto' => $row['id_producto'],
                'nombre' => $row['nombre'],
                'descripcion' => $row['descripcion'],
                'precio' => $row['precio'],
                'stock' => $row['stock'],
                'fecha_creacion' => $row['fecha_creacion'],
                'fecha_actualizacion' => $row['fecha_actualizacion'],
                'creado_por_usuario' => $row['creado_por_usuario'],
            ];
        }
    
        Flight::json([
            'datos' => $productos,
            'page' => $page,
            'total_page' => $pages,
            'total_rows' => $total,
            'message' => 'Lista de productos',
            'isSuccess' => true
        ]);
    }

    public function get_by_id($id) {
        $stmt = $this->db->prepare('select * from producto where id_producto = :id_producto');
        $stmt->bindParam(':id_producto', $id);
        $stmt->execute();
        $datos = $stmt->fetch();
        if ($datos) {
            $producto = [
                'id_producto' => $datos['id_producto'],
                'nombre' => $datos['nombre'],
                'descripcion' => $datos['descripcion'],
                'precio' => $datos['precio'],
                'stock' => $datos['stock'],
                'fecha_creacion' => $datos['fecha_creacion'],
                'fecha_actualizacion' => $datos['fecha_actualizacion'],
                'creado_por_usuario' => $datos['creado_por_usuario']
            ];
            $datos_producto = [$producto];
            Flight::json([
                'datos' => $datos_producto,
                'message' => 'Producto encontrado',
                'isSuccess' => true
            ]);
        } else {
            Flight::json([
                'message' => 'Producto no encontrado',
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
        $descripcion = Flight::request()->data->descripcion;
        $precio = Flight::request()->data->precio;
        $stock = Flight::request()->data->stock;
        $creado_por = $this->token->getToken()->data;

        $stmt = $this->db->prepare('insert into producto (nombre, descripcion, precio, stock, fecha_creacion, fecha_actualizacion, creado_por_usuario) values ( :nombre, :descripcion, :precio, :stock, now(), now(), :creado_por_usuario)');
        if($stmt->execute([
            'nombre' => $nombre,
            'descripcion' => $descripcion,
            'precio' => $precio,
            'stock' => $stock,
            'creado_por_usuario' => $creado_por
        ])){
            Flight::json([
                'data' => [
                    'id_producto' => $this->db->lastInsertId(),
                    'nombre' => $nombre,
                    'descripcion' => $descripcion,
                    'precio' => $precio,
                    'stock' => $stock,
                    'fecha_creacion' => date('Y-m-d H:i:s'),
                    'fecha_actualizacion' => date('Y-m-d H:i:s'),
                    'creado_por_usuario' => $creado_por
                ],
                'message' => 'Producto creado correctamente',
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
        $id_producto = Flight::request()->data->id_producto;
        $nombre = Flight::request()->data->nombre;
        $descripcion = Flight::request()->data->descripcion;
        $precio = Flight::request()->data->precio;
        $stock = Flight::request()->data->stock;
        $fecha_actualizacion = date('Y-m-d H:i:s');

        $stmt = $this->db->prepare('update producto set nombre = :nombre, descripcion = :descripcion, precio = :precio, stock = :stock, fecha_actualizacion = :fecha_actualizacion where id_producto = :id_producto');
        if($stmt->execute([
            'id_producto' => $id_producto,
            'nombre' => $nombre,
            'descripcion' => $descripcion,
            'precio' => $precio,
            'stock' => $stock,
            'fecha_actualizacion' => $fecha_actualizacion
        ])){
            Flight::json([
                'data' => [
                    'id_producto' => $id_producto,
                    'nombre' => $nombre,
                    'descripcion' => $descripcion,
                    'precio' => $precio,
                    'stock' => $stock,
                    'fecha_actualizacion' => date('Y-m-d H:i:s')
                ],
                'message' => 'Producto actualizado correctamente',
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
        $id_producto = Flight::request()->data->id_producto;
        $stmt = $this->db->prepare('delete from producto where id_producto = :id_producto');
        if($stmt->execute(['id_producto' => $id_producto])){
            Flight::json([
                'data' => [
                    'id_producto' => $id_producto
                ],
                'message' => 'Producto eliminado correctamente',
                'isSuccess' => true
            ]);
        }else{
            Flight::json(['isSuccess' => false]);
        }
    }
}