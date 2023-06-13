<?php
namespace App\Tablas;

use PDO;

class Usuario extends Modelo
{
    protected static string $tabla = 'usuarios';

    public $id;
    public $usuario;
    public $validado;

    public function __construct(array $campos)
    {
        $this->id = $campos['id'];
        $this->usuario = $campos['usuario'];
        $this->validado = $campos['validado'];
    }

    public function es_admin(): bool
    {
        return $this->usuario == 'admin';
    }

    public static function esta_logueado(): bool
    {
        return isset($_SESSION['login']);
    }

    public static function logueado(): ?static
    {
        return isset($_SESSION['login']) ? unserialize($_SESSION['login']) : null;
    }

    public static function comprobar($login, $password, ?PDO $pdo = null)
    {
        $pdo = $pdo ?? conectar();

        $sent = $pdo->prepare('SELECT *
                                 FROM usuarios
                                WHERE usuario = :login');
        $sent->execute([':login' => $login]);
        $fila = $sent->fetch(PDO::FETCH_ASSOC);

        if ($fila === false) {
            return false;
        }

        return password_verify($password, $fila['password'])
            ? new static($fila)
            : false;
    }

    public static function existe($login, ?PDO $pdo = null): bool
    {
        return $login == '' ? false :
            !empty(static::todos(
                ['usuario = :usuario'],
                [':usuario' => $login],
                $pdo
            ));
    }

    public static function registrar($login, $password, ?PDO $pdo = null)
    {
        $sent = $pdo->prepare('INSERT INTO usuarios (usuario, password, validado)
                               VALUES (:login, :password, false)');
        $sent->execute([
            ':login' => $login,
            ':password' => password_hash($password, PASSWORD_DEFAULT),
        ]);
    }

    // En la clase Usuario

public function haCompradoArticulo($articuloId): bool
{
    $pdo = conectar();

    $sent = $pdo->prepare('
        SELECT COUNT(*) 
        FROM facturas 
        JOIN articulos_facturas ON (facturas.id = articulos_facturas.factura_id) 
        WHERE facturas.usuario_id = :usuario_id 
        AND articulos_facturas.articulo_id = :articulo_id
    ');

    $sent->execute([':usuario_id' => $this->id, ':articulo_id' => $articuloId]);
    $count = $sent->fetchColumn();

    //Si el número de filas ($count) es > que 0, significa que el usuario ha comprado el artículo y devuelve true

    if ($count > 0){
        return true;
    }
    return false;
}

}
