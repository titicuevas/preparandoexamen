<?php

use App\Tablas\Articulo;

session_start();

require '../vendor/autoload.php';

try {
    $id = obtener_get('id');
    $articulo = Articulo::obtener($id);
    $categoria = obtener_get('categoria');
    if ($id === null) {
        return volver();
    }
    
    
    if ($articulo->getStock()<=0) {
        $_SESSION['error'] ='No hay existencia';
        return volver();
    }

    
    $carrito = unserialize(carrito());
    $carrito->insertar($id);
    $_SESSION['carrito'] = serialize($carrito);
} catch (ValueError $e) {
    // TODO: mostrar mensaje de error en un Alert
}

header("Location: /index.php?categoria=$categoria");
