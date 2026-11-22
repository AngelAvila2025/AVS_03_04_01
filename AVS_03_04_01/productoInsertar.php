<?php
/* productoInsertar.php: formulario para dar de alta un producto (con foto obligatoria) */
require_once 'Producto.php';
require_once 'mensajes.php';
$categorias = (new Producto())->categorias();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Insertar producto</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
<main class="contenedor">
    <h1>Insertar producto</h1>
    <?php mostrarMensaje(); ?>
    <form action="productoCont.php" method="post" enctype="multipart/form-data" class="formulario">
        <input type="hidden" name="accion" value="insertar">

        <label>Nombre
            <input type="text" name="nombre" maxlength="30" required>
        </label>
        <label>Precio
            <input type="number" name="precio" step="0.01" min="0" required>
        </label>
        <label>Stock
            <input type="number" name="stock" step="1" min="0" required>
        </label>
        <label>Categoría
            <select name="categoria_id" required>
                <option value="">-- Selecciona --</option>
                <?php foreach ($categorias as $c): ?>
                    <option value="<?= (int)$c['id_categoria'] ?>"><?= htmlspecialchars($c['descripcion']) ?></option>
                <?php endforeach; ?>
            </select>
        </label>
        <label>Foto del producto
            <input type="file" name="foto" accept="image/jpeg,image/png,image/gif,image/webp" required>
        </label>

        <div class="botones">
            <button type="submit">Guardar</button>
            <a class="btn-sec" href="index.php">Menú</a>
        </div>
    </form>
</main>
</body>
</html>
