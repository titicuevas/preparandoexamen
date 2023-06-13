<?php



// if (!comprobar_csrf()) {
//     return volver_admin();
// }

if (!isset($id)) {
    return volver_admin();
}

// TODO: Validar id
// Comprobar si el departamento tiene empleados

$pdo = conectar();
$sent = $pdo->prepare("UPDATE FROM articulos WHERE id = :id");
$sent->execute([':id' => $id]);

$_SESSION['exito'] = 'El artículo se ha Actualizado correctamente.';

volver_admin();
