<?php
// Incluir el archivo de configuración de la base de datos
require_once('../../config/database.php');

function obtenerOrdenes() {
    global $conn;

    // Consulta SQL para obtener los datos
    $sql = "
        SELECT *
        FROM orden_servicio os
        LEFT JOIN clientes c ON os.cliente_id = c.id_cliente
        WHERE
        	(
                os.EstadoReparacion NOT IN ('ENTREGADO', 'CANCELADO / S.S.R')
                
                OR
                
                (
                    os.EstadoReparacion IN ('ENTREGADO', 'CANCELADO / S.S.R')
                    AND DATEDIFF(CURRENT_DATE(), os.Fecha) <= 15
                )
            )
        ORDER BY
            CASE 
                WHEN os.EstadoReparacion = 'INGRESADO' THEN 1
                WHEN os.EstadoReparacion = 'EN REVISIÓN' THEN 2
                WHEN os.EstadoReparacion = 'EN ESPERA DE APROBACIÓN' THEN 3
                WHEN os.EstadoReparacion = 'EN ESPERA DE PIEZAS' THEN 4
                WHEN os.EstadoReparacion = 'EN REPARACIÓN' THEN 5
                WHEN os.EstadoReparacion = 'EN GARANTÍA' THEN 6
                WHEN os.EstadoReparacion = 'EN TALLER EXTERNO' THEN 7
                WHEN os.EstadoReparacion = 'EN PRUEBAS' THEN 8
                WHEN os.EstadoReparacion = 'ESPERANDO RECOGIDA' THEN 9
                WHEN os.EstadoReparacion = 'ENTREGADO' THEN 10
                WHEN os.EstadoReparacion = 'CANCELADO / S.S.R' THEN 11
            END,
            os.Fecha;  -- Ordena por fecha ascendente dentro de cada grupo
    ";

    // Realizamos la consulta
    $result = $conn->query($sql);

    // Genera filas de tabla con los datos de la base de datos
    $ordenes = array();
    if ($result->num_rows > 0) {
        // Imprimir los datos en la tabla
        while ($row = $result->fetch_assoc()) {
            $ordenes[] = $row;
        }
    }

    // Cerrar conexión a la base de datos
    $conn->close();

    return $ordenes;
}
?>