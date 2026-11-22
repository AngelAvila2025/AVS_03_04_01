<?php
/* productoActualizar.php: formulario con los datos del producto seleccionado */
require_once 'Producto.php';
require_once 'mensajes.php';

$id = filter_var($_GET['id'] ?? '', FILTER_VALIDATE_INT);
$modelo = new Producto();
$p = $id !== false ? $modelo->obtener($id) : null;
if (!$p) {
    header('Location: productoLista.php?msg=no_existe');
    exit;
}
$categorias = $modelo->categorias();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Actualizar producto</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
<main class="contenedor">
    <h1>Actualizar producto #<?= (int)$p['id_producto'] ?></h1>
    <?php mostrarMensaje(); ?>
    <form action="productoCont.php" method="post" enctype="multipart/form-data" class="formulario">
        <input type="hidden" name="accion" value="actualizar">
        <input type="hidden" name="id_producto" value="<?= (int)$p['id_producto'] ?>">

        <label>Nombre
            <input type="text" name="nombre" maxlength="30" required value="<?= htmlspecialchars($p['nombre']) ?>">
        </label>
        <label>Precio
            <input type="number" name="precio" step="0.01" min="0" required value="<?= htmlspecialchars((string)$p['precio']) ?>">
        </label>
        <label>Stock
            <input type="number" name="stock" step="1" min="0" required value="<?= (int)$p['stock'] ?>">
        </label>
        <label>Categoría
            <select name="categoria_id" required>
                <?php foreach ($categorias as $c): ?>
                    <option value="<?= (int)$c['id_categoria'] ?>" <?= $c['id_categoria'] == $p['categoria_id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($c['descripcion']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </label>
        <div>
            <span class="etq">Foto actual</span><br>
            <img class="foto grande" src="fotos/<?= htmlspecialchars($p['foto_p']) ?>" alt="Foto actual">
        </div>
        <label>Cambiar foto (opcional)
            <input type="file" name="foto" accept="image/jpeg,image/png,image/gif,image/webp">
        </label>

        <div class="botones">
            <button type="submit">Actualizar</button>
            <a class="btn-sec" href="productoLista.php">Cancelar</a>
        </div>
    </form>
</main>
</body>
</html>
