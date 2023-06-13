<?php

namespace App\Tablas;

use PDO;

class Articulo extends Modelo
{
    protected static string $tabla = 'articulos';

    public $id;
    private $codigo;
    private $descripcion;
    private $precio;
    private $stock;
    private $categoria_id;

    public function __construct(array $campos)
    {
        $this->id = $campos['id'];
        $this->codigo = $campos['codigo'];
        $this->descripcion = $campos['descripcion'];
        $this->precio = $campos['precio'];
        $this->stock = $campos['stock'];
        $this->categoria_id = $campos['categoria_id'];
    }

    public static function existe(int $id, ?PDO $pdo = null): bool
    {
        return static::obtener($id, $pdo) !== null;
    }

    public function getCodigo()
    {
        return $this->codigo;
    }

    public function getDescripcion()
    {
        return $this->descripcion;
    }

    public function getPrecio()
    {
        return $this->precio;
    }

    public function getStock()
    {
        return $this->stock;
    }

    public function getCategoria(){
        return $this->categoria_id;
    }

    public function getIds(){
        return $this->id;
    }

    public static function modificar($id, $codigo, $descripcion, $precio, $stock, $categoria_id)
    {
        $pdo = conectar();
        $sent = $pdo->prepare("UPDATE articulos
                                SET codigo= :codigo,
                                descripcion= :descripcion,
                                precio= :precio,
                                stock= :stock,
                                categoria_id= :categoria_id
                                WHERE id = :id");
        $sent->execute([
            ':id' => $id,
            ':codigo' => $codigo,
            ':descripcion'=> $descripcion,
            ':precio'=> $precio,
            ':stock'=> $stock,
            ':categoria_id'=>$categoria_id
        ]);
    }

    public static function crear($codigo, $descripcion, $precio, $stock, $categoria_id)
    {
        $pdo = conectar();
        $sent = $pdo->prepare("INSERT INTO articulos (codigo, descripcion, precio, stock, categoria_id)
                                VALUES (:codigo, 
                                        :descripcion,
                                        :precio,
                                        :stock,
                                        :categoria_id)");
        $sent->execute([
            ':codigo' => $codigo,
            ':descripcion'=> $descripcion,
            ':precio'=> $precio,
            ':stock'=> $stock,
            ':categoria_id'=>$categoria_id
        ]);
    }

}
