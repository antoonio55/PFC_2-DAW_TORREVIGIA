<?php
include '../../config/config.php';
include 'funciones.php';

session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: ../../../index.php ");
    exit;
}

// El usuario está autenticado, puedes mostrar el contenido de la página protegida aquí.
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visualizar Orden de Servicio</title>
    <link rel="stylesheet" href="../../css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1>Detalles de la Orden de Servicio</h1>
        
        <?php
        //Obtiene los detalles de la orden
        $orden_id = $_GET['id_orden']; // Pasamos el ID de la orden por la URL
        $orden = obtenerOrdenPorID($orden_id);

        if ($orden) {
            echo '<table class="table">';
            echo '<tr><th>Número:</th><td>' . $orden['Numero'] . '</td></tr>';
            echo '<tr><th>Fecha:</th><td>' . $orden['Fecha'] . '</td></tr>';
            echo '<tr><th>Fecha Modificación:</th><td>' . $orden['FechaModificacion'] . '</td></tr>';
            echo '<tr><th>DNI del Cliente:</th><td>' . $orden['Dni'] . '</td></tr>';
            echo '<tr><th>Nombre del Cliente:</th><td>' . $orden['NombreApellidos'] . '</td></tr>';
            echo '<tr><th>Teléfono del Cliente:</th><td>' . $orden['Telefono'] . '</td></tr>';
            echo '<tr><th>Modelo:</th><td>' . $orden['Modelo'] . '</td></tr>';
            echo '<tr><th>IMEI / SN:</th><td>' . $orden['IMEISN'] . '</td></tr>';
            echo '<tr><th>Contraseña:</th><td>' . $orden['Contrasena'] . '</td></tr>';
            echo '<tr><th>Avería:</th><td>' . $orden['Descripcion'] . '</td></tr>';
            echo '<tr><th>Diagnóstico del Técnico:</th><td>' . $orden['DetallesDiagnostico'] . '</td></tr>';
            echo '<tr><th>Precio:</th><td>' . $orden['Precio'] . '</td></tr>';
            echo '<tr><th>Estado de Reparación:</th><td>' . $orden['EstadoReparacion'] . '</td></tr>';
            echo '</table>';  // Cerrar la tabla antes de mostrar el historial
          	
          	$historial = obtenerHistorialOrden($orden_id);
          	
          	if ($historial) {
                echo '<h2>Historial de Cambios de Estado</h2>';
                echo '<ul>';
                foreach ($historial as $cambio) {
                    echo '<li>' . $cambio['fecha_cambio'] . ': ' . $cambio['estado_anterior'] . ' &rarr; ' . $cambio['estado_nuevo'] . '</li>';
                }
                echo '</ul><hr>';
            }
          
            // Botones para imprimir con márgenes entre ellos y centrados
            echo '<div class="d-flex justify-content-center my-3">';
            
            // Botón para imprimir el ticket
            echo '<a href="funciones.php?action=imprimirTicket&id_orden=' . $orden_id . '" class="btn btn-success mx-2">Imprimir Ticket</a>';

            // Botón para imprimir el folio
            echo '<a href="funciones.php?action=imprimirFolio&id_orden=' . $orden_id . '" class="btn btn-primary mx-2">Imprimir Folio</a>';

            // Botón para imprimir el adhesivo
            echo '<a href="funciones.php?action=imprimirAdhesivo&id_orden=' . $orden_id . '" class="btn btn-info mx-2">Imprimir Etiqueta</a>';
            
            echo '</div>';
        } else {
            echo '<div class="alert alert-danger">Orden no encontrada.</div>';
        }
        ?>
        
        <div class="text-center mt-5">
            <a href="index.php" class="btn btn-primary">Volver a la lista de órdenes</a>
        </div>
    </div>

    <script src="../../js/jquery-3.5.1.min.js"></script>
    <script src="../../js/bootstrap.min.js"></script>
</body>
</html>
