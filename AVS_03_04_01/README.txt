AVS.03.04.01 - Página de gestión de productos
=============================================
1. Copia la carpeta AVS_03_04_01 a C:\xampp\htdocs\
2. Inicia Apache y MySQL en el panel de XAMPP.
3. En phpMyAdmin (http://localhost/phpmyadmin) usa "Importar" y selecciona sql/AVS_03_04_01.sql
   (crea la base AVS_03_04_01, las tablas categorias y productos y 5 categorías iniciales).
4. Abre http://localhost/AVS_03_04_01/index.php

Conexión a la base de datos: conexion.php (usuario root, contraseña vacía, base AVS_03_04_01).
Las fotos se guardan en la carpeta fotos/ (debe poder escribirse; en XAMPP/Windows funciona por defecto).

Archivos
- index.php             Menú principal
- conexion.php          Conexión a MySQL
- Producto.php          Modelo: insertar, listar, obtener, actualizar, borrar
- productoCont.php      Controlador: recibe los formularios, valida y decide la acción
- productoInsertar.php  Formulario de alta (foto obligatoria)
- productoLista.php     Listado con foto; botones Actualizar y Borrar
- productoActualizar.php Formulario con los datos del producto seleccionado
- mensajes.php          Mensajes de éxito/error
- estilos.css           Estilos
