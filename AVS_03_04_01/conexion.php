<?php
/**
 * conexion.php
 * Conexión a la base de datos AVS_03_04_01 (XAMPP: MySQL/MariaDB).
 * Los valores por defecto de XAMPP son usuario "root" y contraseña vacía.
 */
class Conexion
{
    private const SERVIDOR  = 'localhost';
    private const USUARIO   = 'root';
    private const PASSWORD  = '';
    private const BASE      = 'AVS_03_04_01';

    /** Devuelve un objeto mysqli conectado, o termina con un mensaje claro. */
    public static function conectar(): mysqli
    {
        // Hace que mysqli lance excepciones en lugar de warnings
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        try {
            $con = new mysqli(self::SERVIDOR, self::USUARIO, self::PASSWORD, self::BASE);
            $con->set_charset('utf8mb4');
            return $con;
        } catch (mysqli_sql_exception $e) {
            http_response_code(500);
            die('No se pudo conectar a la base de datos AVS_03_04_01. '
              . 'Verifica que MySQL esté iniciado en XAMPP y que la base exista.');
        }
    }
}
