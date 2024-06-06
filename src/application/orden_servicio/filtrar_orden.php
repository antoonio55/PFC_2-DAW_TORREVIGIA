<?php
// Incluye tus funciones y realiza la búsqueda
include '../../config/config.php';
include 'funciones.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Recopila los datos del formulario
    $numero = $_GET['numero_name'];
    $fecha = $_GET['fecha_name'];
    $dni = $_GET['dni_name'];
    $nombre = $_GET['nombre_name'];
    $telefono = $_GET['telefono_name'];
    $imei = $_GET['imei_name'];

    // Obtén las ordenes filtradas
    $ordenesFiltradas = obtenerOrdenesFiltradas($numero, $fecha, $dni, $nombre, $telefono, $imei);

    // Genera la tabla de resultados
    echo '<table class="table table-bordered text-center">';
    echo '<thead class="thead-light">';
    echo '<tr>';
    echo '<th>Nº</th>';
    echo '<th>Fecha</th>';
    echo '<th>Dni</th>';
    echo '<th>Nombre y Apellidos</th>';
    echo '<th>Teléfono</th>';
    echo '<th>Modelo</th>';
    echo '<th>IMEI/SN</th>';
    //echo '<th>Contraseña</th>';
    echo '<th>Defecto</th>';
    //echo '<th>Precio</th>';
    echo '<th>Estado</th>';
    echo '<th class="custom-actions">Acciones</th>';
    echo '</tr>';
    echo '</thead>';
    echo '<tbody>';

    foreach ($ordenesFiltradas as $orden) {
        echo '<tr>';
        echo '<td>' . $orden['Numero'] . '</td>';
        echo '<td>' . $orden['Fecha'] . '</td>';
        echo '<td>' . $orden['Dni'] . '</td>';
        echo '<td>' . $orden['NombreApellidos'] . '</td>';
        echo '<td>' . $orden['Telefono'] . '</td>';
        echo '<td>' . $orden['Modelo'] . '</td>';
        echo '<td>' . $orden['IMEISN'] . '</td>';
        //echo '<td>' . $orden['Contrasena'] . '</td>';
        echo '<td>' . $orden['Descripcion'] . '</td>';
        //echo '<td>' . $orden['Precio'] . '</td>';
        echo '<td>' . $orden['EstadoReparacion'] . '</td>';
        echo '<td>';
        echo '<a href="visualizar_orden.php?id_orden=' . $orden['id_orden'] . '" class="btn btn-info"><i class="fas fa-eye"></i></a><br>';
        echo '<a href="editar_orden.php?id_orden=' . $orden['id_orden'] . '" class="btn btn-warning"><i class="fas fa-edit"></i></a>';
        echo '</td>';
        echo '</tr>';
    }

    // Cierra la tabla
    echo '</tbody>';
    echo '</table>';
}
?>