<?php
/**
 * Producto.php  (modelo)
 * Contiene todas las operaciones sobre la tabla productos:
 * insertar, listar, obtener uno, actualizar y borrar.
 * Todas las consultas usan sentencias preparadas.
 */
require_once 'conexion.php';

class Producto
{
    private mysqli $con;

    public function __construct()
    {
        $this->con = Conexion::conectar();
    }

    /** Lista de categorías para el <select> de los formularios. */
    public function categorias(): array
    {
        $res = $this->con->query('SELECT id_categoria, descripcion FROM categorias ORDER BY descripcion');
        return $res->fetch_all(MYSQLI_ASSOC);
    }

    /** INSERTAR: agrega un producto nuevo. */
    public function insertar(string $nombre, float $precio, int $stock, string $foto, int $categoria): bool
    {
        $sql = 'INSERT INTO productos (nombre, precio, stock, foto_p, categoria_id) VALUES (?, ?, ?, ?, ?)';
        $st = $this->con->prepare($sql);
        $st->bind_param('sdisi', $nombre, $precio, $stock, $foto, $categoria);
        return $st->execute();
    }

    /** MOSTRAR: todos los productos con la descripción de su categoría. */
    public function listar(): array
    {
        $sql = 'SELECT p.id_producto, p.nombre, p.precio, p.stock, p.foto_p, c.descripcion AS categoria
                FROM productos p
                INNER JOIN categorias c ON c.id_categoria = p.categoria_id
                ORDER BY p.id_producto';
        return $this->con->query($sql)->fetch_all(MYSQLI_ASSOC);
    }

    /** Obtiene un solo producto (para llenar el formulario de actualizar). */
    public function obtener(int $id): ?array
    {
        $st = $this->con->prepare('SELECT * FROM productos WHERE id_producto = ?');
        $st->bind_param('i', $id);
        $st->execute();
        $fila = $st->get_result()->fetch_assoc();
        return $fila ?: null;
    }

    /** ACTUALIZAR: modifica los datos de un producto (la foto solo si se envía una nueva). */
    public function actualizar(int $id, string $nombre, float $precio, int $stock, ?string $foto, int $categoria): bool
    {
        if ($foto !== null) {
            $st = $this->con->prepare(
                'UPDATE productos SET nombre=?, precio=?, stock=?, foto_p=?, categoria_id=? WHERE id_producto=?');
            $st->bind_param('sdisii', $nombre, $precio, $stock, $foto, $categoria, $id);
        } else {
            $st = $this->con->prepare(
                'UPDATE productos SET nombre=?, precio=?, stock=?, categoria_id=? WHERE id_producto=?');
            $st->bind_param('sdiii', $nombre, $precio, $stock, $categoria, $id);
        }
        return $st->execute();
    }

    /** BORRAR: elimina un producto por su id. */
    public function borrar(int $id): bool
    {
        $st = $this->con->prepare('DELETE FROM productos WHERE id_producto = ?');
        $st->bind_param('i', $id);
        return $st->execute() && $st->affected_rows > 0;
    }
}
