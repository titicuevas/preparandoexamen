<?php session_start() ?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="/css/output.css" rel="stylesheet">
    <title>Portal</title>
</head>
<a href=""></a><button type="submit"></button>
<body>
    <?php
    require '../vendor/autoload.php';

    $carrito = unserialize(carrito());

    $pdo = conectar();


    $categorias = $pdo->query("SELECT * FROM categorias");






    if (isset($_GET['articulo'])) {
        $busqueda = $_GET['articulo'];
        $sent = $pdo->query("SELECT * FROM articulos WHERE descripcion ILIKE '%$busqueda%'");
        echo $_GET['articulo'];



    }elseif (isset($_GET['precio'])) {
        $busqueda = $_GET['precio'];
        $sent = $pdo->query("SELECT * FROM articulos WHERE precio ILIKE '%$busqueda%'");
        echo $_GET['articulo'];
    } else {

        $where = '';
        $categoria = obtener_get('categoria'); 
        $categoria != null ? $where = "WHERE categoria_id = $categoria" : null;
        $sent = $pdo->query("SELECT a.id,
                                    a.descripcion,
                                    a.stock,
                                    a.precio,
                                    a.categoria_id,
                                    c.nombre
                                FROM articulos a JOIN categorias c ON a.categoria_id = c.id  
                                $where");
        echo $_GET['categoria'];
    }
    ?>
    <div class="container mx-auto">
        <?php require '../src/_menu.php' ?>
        <?php require '../src/_alerts.php' ?>


        <form action="" method="get">
            <label for="busqueda">Buscar Articulo</label>
            <input type="text" id="busqueda" name="articulo" required>
            <button type="submit">Buscar</button>
        </form>


        <form id="cate" action="" method="get">

            <label for="categoria">Seleccione la categoria</label>
            <select name="categoria" id="">
                <option name="categoria" value="" >Todos</option>
                <?php foreach ($categorias as $cat) : ?>
                    <option name="categoria" value="<?= hh($cat['id'])?>" <?= $categoria != $cat['id'] ? null : 'selected'?>>
                        <?= hh($cat['nombre']) ?>
                    </option>
                <?php endforeach ?>

            </select>
            <button type="submit">Buscar</button>
        </form>





        <div class="flex">
            <main class="flex-1 grid grid-cols-3 gap-4 justify-center justify-items-center">
                <?php foreach ($sent as $fila) : ?>
                    <div class="p-6 max-w-xs min-w-full bg-white rounded-lg border border-gray-200 shadow-md dark:bg-gray-800 dark:border-gray-700">
                        <a href="#">
                            <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white"><?= hh($fila['descripcion']) ?></h5>
                        </a>
                        
                        <h5>Stock:</h5>
                        <p class="mb-3 font-normal text-gray-700 dark:text-gray-400"><?= hh($fila['stock']) ?></p>
                        <h5>Precio:</h5>
                        <p class="mb-3 font-normal text-gray-700 dark:text-gray-400"><?= hh($fila['precio']) ?></p>
                        <h5>Categoria:</h5>
                        <p class="mb-3 font-normal text-gray-700 dark:text-gray-400"><?= hh($fila['nombre']) ?></p>

                        <?php if ($fila['stock'] > 0) : ?>


                            <a href="/insertar_en_carrito.php?id=<?= $fila['id'] ?>&categoria=<?= $categoria ?>" class="inline-flex items-center py-2 px-3.5 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                Añadir al carrito




                                <svg aria-hidden="true" class="ml-3 -mr-1 w-4 h-4" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </a>

                        <?php else : ?>
                            <button disabled="disabled" class="inline-flex items-center py-2 px-3.5 text-sm font-medium text-center text-white bg-gray-500 rounded-lg hover:bg-gray-500 focus:ring-4 focus:outline-none focus:ring-gray-500 dark:bg-gray-500 dark:hover:bg--700 dark:focus:ring-blue-800">Sin existencia</button>


                        <?php endif ?>
                    </div>
                <?php endforeach ?>
            </main>

            <?php if (!$carrito->vacio()) : ?>
                <aside class="flex flex-col items-center w-1/4" aria-label="Sidebar">
                    <div class="overflow-y-auto py-4 px-3 bg-gray-50 rounded dark:bg-gray-800">
                        <table class="mx-auto text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <th scope="col" class="py-3 px-6">Descripción</th>
                                <th scope="col" class="py-3 px-6">Cantidad</th>
                                <th scope="col" class="py-3 px-6">Precio</th>
                                <th scope="col" class="py-3 px-6">Categoria</th>
                            </thead>
                            <tbody>
                                <?php foreach ($carrito->getLineas() as $id => $linea) : ?>
                                    <?php
                                    $articulo = $linea->getArticulo();
                                    $cantidad = $linea->getCantidad();




                                    ?>
                                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                        <td class="py-4 px-6"><?= $articulo->getDescripcion() ?></td>
                                        <td class="py-4 px-6 text-center"><?= $cantidad ?></td>
                                        <td class="py-4 px-6"><?= $articulo->getPrecio() ?></td>
                                        <td class="py-4 px-6"><?= $articulo->getCategoria() ?></td>


                                    </tr>
                                <?php endforeach ?>
                            </tbody>
                        </table>
                    </div>
                    <div>
                        <a href="/vaciar_carrito.php" class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900">Vaciar carrito</a>
                        <a href="/comprar.php" class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-900">Comprar</a>
                    </div>
                </aside>
            <?php endif ?>
        </div>
    </div>
    <script src="/js/flowbite/flowbite.js"></script>
</body>

</html>