<?php
/**
 * productoCont.php  (controlador)
 * Recibe los datos de los formularios, valida, decide la acción
 * (insertar / actualizar / borrar) y regresa a la lista con un mensaje.
 */
require_once 'Producto.php';

const CARPETA_FOTOS = __DIR__ . '/fotos/';
const EXT_PERMITIDAS = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/gif' => 'gif', 'image/webp' => 'webp'];
const MAX_BYTES = 2 * 1024 * 1024; // 2 MB

/** Redirige a una página con un código de mensaje. */
function volver(string $pagina, string $msg): void
{
    $sep = str_contains($pagina, '?') ? '&' : '?';
    header("Location: {$pagina}{$sep}msg=" . urlencode($msg));
    exit;
}

/**
 * Guarda la foto subida en /fotos con nombre único.
 * Devuelve el nombre del archivo, null si no se envió foto, o false si es inválida.
 */
function guardarFoto(): string|null|false
{
    if (!isset($_FILES['foto']) || $_FILES['foto']['error'] === UPLOAD_ERR_NO_FILE) {
        return null;
    }
    $f = $_FILES['foto'];
    if ($f['error'] !== UPLOAD_ERR_OK || $f['size'] > MAX_BYTES) {
        return false;
    }
    $mime = (new finfo(FILEINFO_MIME_TYPE))->file($f['tmp_name']);
    if (!isset(EXT_PERMITIDAS[$mime])) {
        return false;
    }
    $nombre = uniqid('prod_', true) . '.' . EXT_PERMITIDAS[$mime];
    return move_uploaded_file($f['tmp_name'], CARPETA_FOTOS . $nombre) ? $nombre : false;
}

/** Borra una foto del disco (solo dentro de /fotos). */
function borrarFoto(?string $nombre): void
{
    if ($nombre && basename($nombre) === $nombre && is_file(CARPETA_FOTOS . $nombre)) {
        unlink(CARPETA_FOTOS . $nombre);
    }
}

/** Valida los campos comunes del formulario. Devuelve null si algo es inválido. */
function leerCampos(): ?array
{
    $nombre = trim($_POST['nombre'] ?? '');
    $precio = filter_var($_POST['precio'] ?? '', FILTER_VALIDATE_FLOAT);
    $stock  = filter_var($_POST['stock'] ?? '', FILTER_VALIDATE_INT);
    $cat    = filter_var($_POST['categoria_id'] ?? '', FILTER_VALIDATE_INT);
    if ($nombre === '' || mb_strlen($nombre) > 30 || $precio === false || $precio < 0
        || $stock === false || $stock < 0 || $cat === false) {
        return null;
    }
    return [$nombre, (float)$precio, (int)$stock, (int)$cat];
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$accion   = $_POST['accion'] ?? '';
$producto = new Producto();

try {
    switch ($accion) {
        case 'insertar':
            $campos = leerCampos();
            if (!$campos) volver('productoInsertar.php', 'datos_invalidos');
            $foto = guardarFoto();
            if (!$foto) volver('productoInsertar.php', 'foto_invalida'); // la foto es obligatoria
            [$nombre, $precio, $stock, $cat] = $campos;
            if ($producto->insertar($nombre, $precio, $stock, $foto, $cat)) {
                volver('productoLista.php', 'insertado');
            }
            borrarFoto($foto);
            volver('productoInsertar.php', 'error');

        case 'actualizar':
            $id = filter_var($_POST['id_producto'] ?? '', FILTER_VALIDATE_INT);
            $campos = leerCampos();
            if ($id === false || !$campos) volver('productoLista.php', 'datos_invalidos');
            $actual = $producto->obtener($id);
            if (!$actual) volver('productoLista.php', 'no_existe');
            $foto = guardarFoto();
            if ($foto === false) volver("productoActualizar.php?id=$id", 'foto_invalida');
            [$nombre, $precio, $stock, $cat] = $campos;
            if ($producto->actualizar($id, $nombre, $precio, $stock, $foto, $cat)) {
                if ($foto !== null) borrarFoto($actual['foto_p']); // se quita la foto anterior
                volver('productoLista.php', 'actualizado');
            }
            if ($foto !== null) borrarFoto($foto);
            volver('productoLista.php', 'error');

        case 'borrar':
            $id = filter_var($_POST['id_producto'] ?? '', FILTER_VALIDATE_INT);
            if ($id === false) volver('productoLista.php', 'datos_invalidos');
            $actual = $producto->obtener($id);
            if ($actual && $producto->borrar($id)) {
                borrarFoto($actual['foto_p']);
                volver('productoLista.php', 'borrado');
            }
            volver('productoLista.php', 'no_existe');

        default:
            volver('index.php', 'accion_invalida');
    }
} catch (mysqli_sql_exception $e) {
    volver('productoLista.php', 'error');
}
