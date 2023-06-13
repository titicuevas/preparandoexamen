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

  <title>Edit de articulos</title>


</head>

<body>
  <?php
  require '../../vendor/autoload.php';

  $id = obtener_get('id');

  $pdo = conectar();
  $sent = $pdo->prepare("SELECT * FRom articulos a JOIN categorias c ON a.categoria_id = c.id WHERE a.id = :id");
  $sent->execute([':id' => $id]);

  $articulo = $sent->fetch();
  $codigo = obtener_post('codigo');
  $descripcion = obtener_post('descripcion');
  $precio = obtener_post('precio');
  $stock = obtener_post('stock');
  $categoria = obtener_post('categoria');
  $categoria_id = $articulo['categoria_id'];

  $sent = $pdo->query("SELECT * FROM categorias WHERE id != $categoria_id");


  //Validar datos:

  if (!empty($descripcion)) {
    \App\Tablas\Articulo::modificar($id, $codigo, $descripcion, $precio, $stock, $categoria_id);
    $_SESSION['exito'] = 'El articulo se modifico correctamente';
    return volver_admin();
  }
  ?>



  <!-- Formulario de edición -->
  <form action="" method="post">
    <input type="hidden" name="id" value="<?php echo $id; ?>">
    <label for="codigo">Codigo:</label>
    <input type="text" placeholder="Introduzca un codigo nuevo" name="codigo" value="<?= hh($articulo['codigo']); ?>"><br>
    <label for="descripcion">Descripcion:</label>
    <input type="text" placeholder="Introduzca una nueva descripcion" name="descripcion" value="<?= $articulo['descripcion']; ?>"><br>
    <label for="stock">Stock:</label>

    <input type="text" placeholder="Introduzca un nuevo precio" name="stock" value="<?= $articulo['stock']; ?>"><br>

    <label for="precio">Precio:</label>
    <input type="text" placeholder="Introduzca un nuevo precio" name="precio" value="<?= $articulo['precio']; ?>"><br>
    <label for="categoria">Categoria:</label>

    <select name="categorias">
      <option selected value="<?= $articulo['categoria_id'] ?>"><?= $articulo['nombre'] ?></option>
      <?php foreach ($sent as $categoria) { ?>
        <option value="<?= $categoria['id'] ?>"><?= $categoria['nombre'] ?></option>
      <?php } ?>
    </select>
    <br>
    <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-full">
      Editar
    </button>
  </form>


</body>
<script src="/js/flowbite/flowbite.js"></script>

</html>