<?php
// Include tus funciones y realiza la búsqueda
include '../../config/config.php';
include 'funciones.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Recopila los datos del formulario
    $id = $_GET['id_name'];
    $nombre = $_GET['nombre_name'];
    $dni = $_GET['dni_name'];
    $telefono = $_GET['telefono_name'];
    $lista_negra = isset($_GET['lista_negra_name']) ? 1 : 0;

    // Obtén los clientes filtrados
    $clientesFiltrados = obtenerClientesFiltrados($id, $nombre, $dni, $telefono, $lista_negra);

    // Genera la tabla de resultados
    echo '<table class="table table-bordered text-center">';
    echo '<thead class="thead-light">';
    echo '<tr>';
    echo '<th>ID</th>';
    echo '<th>Nombre</th>';
    echo '<th>DNI</th>';
    echo '<th>Teléfono</th>';
    echo '<th>Teléfono Adicional</th>';
    echo '<th>Lista Negra</th>';
    echo '<th class="custom-actions">Acciones</th>';
    echo '</tr>';
    echo '</thead>';
    echo '<tbody>';

    foreach ($clientesFiltrados as $cliente) {
        echo '<tr>';
        echo '<td>' . $cliente['id_cliente'] . '</td>';
        echo '<td>' . $cliente['NombreApellidos'] . '</td>';
        echo '<td>' . $cliente['Dni'] . '</td>';
        echo '<td>' . $cliente['Telefono'] . '</td>';
        echo '<td>' . $cliente['TelefonoAdicional'] . '</td>';
        echo '<td>' . ($cliente['ListaNegra'] == 1 ? "SI" : "NO") . '</td>';
        echo '<td>';
        echo '<a href="visualizar_cliente.php?id_cliente=' . $cliente['id_cliente'] . '" class="btn btn-info"><i class="fas fa-eye"></i></a><br>';
        echo '<a href="editar_cliente.php?id_cliente=' . $cliente['id_cliente'] . '" class="btn btn-warning"><i class="fas fa-edit"></a>';
        echo '</td>';
        echo '</tr>';
    }

    // Cierra la tabla
    echo '</tbody>';
    echo '</table>';
}
?>