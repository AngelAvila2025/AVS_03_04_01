<?php
/* productoLista.php: lista todos los productos con foto; desde aquí se actualiza o borra */
require_once 'Producto.php';
require_once 'mensajes.php';
$productos = (new Producto())->listar();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lista de productos</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
<main class="contenedor ancho">
    <h1>Lista de productos</h1>
    <?php mostrarMensaje(); ?>
    <div class="botones">
        <a class="btn" href="productoInsertar.php">➕ Nuevo producto</a>
        <a class="btn-sec" href="index.php">Menú</a>
    </div>

    <?php if (!$productos): ?>
        <p class="vacio">Aún no hay productos registrados.</p>
    <?php else: ?>
    <div class="tabla-wrap">
    <table>
        <thead>
            <tr><th>ID</th><th>Foto</th><th>Nombre</th><th>Precio</th><th>Stock</th><th>Categoría</th><th>Acciones</th></tr>
        </thead>
        <tbody>
        <?php foreach ($productos as $p): ?>
            <tr>
                <td><?= (int)$p['id_producto'] ?></td>
                <td><img class="foto" src="fotos/<?= htmlspecialchars($p['foto_p']) ?>" alt="Foto de <?= htmlspecialchars($p['nombre']) ?>"></td>
                <td><?= htmlspecialchars($p['nombre']) ?></td>
                <td>$<?= number_format((float)$p['precio'], 2) ?></td>
                <td><?= (int)$p['stock'] ?></td>
                <td><?= htmlspecialchars($p['categoria']) ?></td>
                <td class="acciones">
                    <a class="btn-sec" href="productoActualizar.php?id=<?= (int)$p['id_producto'] ?>">Actualizar</a>
                    <form action="productoCont.php" method="post"
                          onsubmit="return confirm('¿Borrar el producto «<?= htmlspecialchars($p['nombre'], ENT_QUOTES) ?>»?');">
                        <input type="hidden" name="accion" value="borrar">
                        <input type="hidden" name="id_producto" value="<?= (int)$p['id_producto'] ?>">
                        <button type="submit" class="peligro">Borrar</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    </div>
    <?php endif; ?>
</main>
</body>
</html>
