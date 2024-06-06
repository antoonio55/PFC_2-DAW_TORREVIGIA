<?php
// Include tus funciones y realiza la búsqueda
include '../../config/config.php';
include 'funciones.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Recopila los datos del formulario
    $nombre_pieza = $_GET['nombre_name'];
    $cantidad_pieza = $_GET['cantidad_name'];
    $precio_pieza = $_GET['precio_name'];

    // Obtén los clientes filtrados
    $piezasFiltradas = obtenerPiezasFiltradas($nombre_pieza, $cantidad_pieza, $precio_pieza);

    // Genera la tabla de resultados
    echo '<table class="table table-bordered text-center">';
    echo '<thead class="thead-light">';
    echo '<tr>';
    echo '<th>ID</th>';
    echo '<th>Nombre</th>';
    echo '<th>Cantidad</th>';
    echo '<th>Precio Unitario</th>';
    echo '<th>Descripción</th>';
    echo '<th>Acciones</th>';
    echo '</tr>';
    echo '</thead>';
    echo '<tbody>';

    foreach ($piezasFiltradas as $pieza) {
        echo '<tr>';
        echo '<td>' . $pieza['id_pieza'] . '</td>';
        echo '<td>' . $pieza['nombre_pieza'] . '</td>';
        echo '<td>' . $pieza['cantidad_pieza'] . '</td>';
        echo '<td>' . $pieza['precio_pieza'] . '</td>';
        echo '<td>' . $pieza['descripcion_pieza'] . '</td>';
        echo '<td>';
        echo '<a href="editar_pieza.php?id=' . $pieza['id_pieza'] . '" class="btn btn-warning"><i class="fas fa-edit"></i></a>';
        echo '</td>';
        echo '</tr>';
    }

    // Cierra la tabla
    echo '</tbody>';
    echo '</table>';
}
?>
