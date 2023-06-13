<?php session_start() ?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="/css/output.css" rel="stylesheet">
  <script>
    function cambiar(el, id) {
      el.preventDefault();
      const oculto = document.getElementById('oculto');
      oculto.setAttribute('value', id);
    }
  </script>

  <title>Crear Producto</title>


</head>

<body>
  <?php
  require '../../vendor/autoload.php';
  $pdo = conectar();

  $sent = $pdo->query("SELECT * FROM categorias");
  

 

  $codigo = obtener_post('codigo');
  $descripcion = obtener_post('descripcion');
  $precio = obtener_post('precio');
  $stock = obtener_post('stock');
  $categoria_id = obtener_post('categoria');


  if (!empty($descripcion)) {
    \App\Tablas\Articulo::crear($codigo, $descripcion, $precio, $stock, $categoria_id);
    $_SESSION['exito'] = 'El articulo se ha creado correctamente';
    return volver_admin();
  }
  ?>


  <form action="create.php" method="post">
    <label for="codigo">Codigo:</label>
    <input type="text" placeholder="Introduzca el codigo" name="codigo" required>
    <br>

    <label for="descripcion">Descripcion:</label>
    <input type="text" placeholder="Introduzca la descripcion" name="descripcion" required>
    <br>

    <label for="stock">Stock:</label>
    <input type="text" placeholder="Introduzca el stock" name="stock" required>
    <br>

    <label for="precio">Precio:</label>
    <input type="text" placeholder="Introduzca un precio" name="precio" required>

    <br>


    <label for="categoria">Categoria</label>
    <select name="categoria">
      <?php foreach ($sent as $categoria) { ?>
        <option name="categoria" value="<?= $categoria['id'] ?>"><?= $categoria['nombre'] ?></option>
      <?php } ?>
    </select>
    <br>

    <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-full">
      Crear
    </button>
  </form>



</body>
<script src="/js/flowbite/flowbite.js"></script>

</html>