<?php
/** mensajes.php: muestra el mensaje de resultado recibido por ?msg= (solo códigos conocidos). */
function mostrarMensaje(): void
{
    $textos = [
        'insertado'      => ['ok',    'Producto insertado con éxito.'],
        'actualizado'    => ['ok',    'Producto actualizado con éxito.'],
        'borrado'        => ['ok',    'Producto borrado con éxito.'],
        'datos_invalidos'=> ['error', 'Datos inválidos: revisa nombre, precio, stock y categoría.'],
        'foto_invalida'  => ['error', 'La foto es obligatoria o no es válida (JPG, PNG, GIF o WEBP, máximo 2 MB).'],
        'no_existe'      => ['error', 'El producto indicado no existe.'],
        'accion_invalida'=> ['error', 'Acción no válida.'],
        'error'          => ['error', 'Ocurrió un error al procesar la operación.'],
    ];
    $clave = $_GET['msg'] ?? '';
    if (isset($textos[$clave])) {
        [$tipo, $texto] = $textos[$clave];
        echo '<div class="mensaje ' . $tipo . '">' . htmlspecialchars($texto) . '</div>';
    }
}
