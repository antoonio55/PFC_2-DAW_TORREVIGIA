<?php
	/////////////////////////////////////////////////
	// COMPROBAMOS QUE EL USUARIO ESTÁ AUTENTICADO //
	/////////////////////////////////////////////////

    session_start();

    if (!isset($_SESSION["user_id"])) {
        error_log("Intento de acceso no autorizado: " . $_SERVER['REQUEST_URI']);
        header("Location: ../login/index.php");
        exit;
    }

    // Regenera la ID de sesión después de la autenticación
    session_regenerate_id(true);
?>
<?php
	include '../../config/config.php';
    include 'funciones.php'; // Asegúrate de que esta inclusión sea relevante para la función obtenerClientes()

    $clientes = obtenerClientes();

    if(isset($_GET['mensaje'])){
        if($_GET['mensaje']=='exito'){
            //imprimes el mensaje
            echo '<div class="alert alert-primary alert-dismissible fade show" role="alert">';
            echo '   <strong>¡Cliente Agregado!</strong> Se han añadido los datos del cliente a la base de datos';
            echo '   <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
            echo '</div>';
        }

    }
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="../../css/bootstrap.min.css">
    <link rel="stylesheet" href="../../font-awesome/css/all.css">
    <style>
        /* Ajustar el ancho de la columna "Acciones" */
        .custom-actions {
            width: 210px;
        }

        /* Mover el label encima del checkbox */
        .form-check-input[type="checkbox"] {
            transform: scale(1.5); /* Ajusta el valor para cambiar el tamaño del checkbox */
            margin-top: 0.5rem; /* Ajusta el valor para alinear el label encima del checkbox */
            margin-left: -5rem;
            position: absolute;
        }

        /* Tamaño de fuente en la tabla */
        .table td {
            font-size: 14px; /* Ajusta el tamaño de letra según tus preferencias */
            vertical-align: middle;
        }
        .table th{
            vertical-align: middle;
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <div class="row">
            <div class="col">
                <h1>Listado de Clientes</h1>
            </div>
            <div class="col text-right">
                <!--<a href="crear_cliente.php" style="float:right;" class="btn btn-primary mb-3">Agregar Cliente</a>-->
                <button id="mostrarFormulario" style="float:right;" class="btn btn-primary mb-3">Agregar Cliente</button>
            </div>
        </div>
        
        <div id="formularioEmergente" class="modal" style="display: none;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <!-- Carga el contenido del formulario.html aquí mediante una solicitud AJAX -->
                </div>
            </div>
        </div>

        <div class="filter-section mb-4 border-top border-bottom border-2 p-3">
            <h4>Filtrar Clientes</h4>
            <form class="form-inline" method="GET" action="#">
                <div class="row">
                    <div class="col-md-1">
                        <div class="form-group mb-3">
                            <label for="id">ID Cliente:</label>
                            <input type="text" class="form-control" id="id" name="id_name" placeholder="Ej. 12345">
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group mb-3">
                            <label for="nombre">Nombre del cliente:</label>
                            <input type="text" class="form-control" id="nombre" name="nombre_name" placeholder="Ej. Juan">
                        </div>
                    </div>
                    
                    <div class="col-md-2">
                        <div class="form-group mb-3">
                            <label for="dni">DNI del Cliente:</label>
                            <input type="text" class="form-control" id="dni" name="dni_name" placeholder="Ej. 12345678X">
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group mb-3">
                            <label for="telefono">Teléfono:</label>
                            <input type="text" class="form-control" id="telefono" name="telefono_name" placeholder="Ej. 6XX XXX XXX">
                        </div>
                    </div>

                    <div class="col-md-2 d-flex align-items-center">
                        <label class="form-check-label mb-5" for="lista_negra">Lista Negra:</label>
                        <div class="form-group mb-4" style="margin-left: 25px;">
                            <input type="checkbox" class="form-check-input" id="lista_negra" name="lista_negra_name">
                        </div>
                    </div>

                    <div class="col-md-1">
                        <div class="form-group mt-4 ">
                            <button type="submit" class="btn btn-primary btn-block">Buscar</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Aquí utilizamos la misma estructura de tabla que ya tenías en el código original -->
        <div id="resultados-busqueda">
        <table class="table table-bordered text-center">
            <thead class="thead-light">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>DNI</th>
                    <th>Teléfono</th>
                    <th>Teléfono Adicional</th>
                    <th>Lista Negra</th>
                    <th class="custom-actions">Acciones</th>
                </tr>
            </thead>
            <tbody>
                
                <?php

                    // Verificar si se ha enviado el formulario
                    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                        // ID
                        if (isset($_GET['id_name'])) {
                            $id = $_GET['id_name'];
                            // Aquí puedes usar $id sin preocuparte por advertencias
                        } else {
                            // Manejar el caso en el que 'id_name' no está presente en la consulta GET
                            $id = ''; // O cualquier otro valor por defecto que desees
                        }

                        // Nombre
                        if (isset($_GET['nombre_name'])) {
                            $nombre = $_GET['nombre_name'];
                            // Aquí puedes usar $nombre sin preocuparte por advertencias
                        } else {
                            // Manejar el caso en el que 'nombre_name' no está presente en la consulta GET
                            $nombre = ''; // O cualquier otro valor por defecto que desees
                        }

                        // DNI
                        if (isset($_GET['dni_name'])) {
                            $dni = $_GET['dni_name'];
                            // Aquí puedes usar $dni sin preocuparte por advertencias
                        } else {
                            // Manejar el caso en el que 'dni_name' no está presente en la consulta GET
                            $dni = ''; // O cualquier otro valor por defecto que desees
                        }

                        // Teléfono
                        if (isset($_GET['telefono_name'])) {
                            $telefono = $_GET['telefono_name'];
                            // Aquí puedes usar $telefono sin preocuparte por advertencias
                        } else {
                            // Manejar el caso en el que 'telefono_name' no está presente en la consulta GET
                            $telefono = ''; // O cualquier otro valor por defecto que desees
                        }

                        // Lista negra (booleano)
                        $lista_negra = isset($_GET['lista_negra_name']) ? 1 : 0;

                        // Obtén los clientes filtrados
                        $clientesFiltrados = obtenerClientesFiltrados($id, $nombre, $dni, $telefono, $lista_negra);

                        // Número de elementos por página
                        $itemsPorPagina = 10;

                        // Número total de clientes filtrados
                        $totalClientes = count($clientesFiltrados);

                        // Número total de páginas
                        $totalPaginas = ceil($totalClientes / $itemsPorPagina);

                        // Página actual (obtenida de la URL o cualquier otra fuente de datos)
                        if (isset($_GET['pagina'])) {
                            $paginaActual = $_GET['pagina'];
                        } else {
                            $paginaActual = 1;
                        }

                        // Calcular el índice de inicio y fin de los clientes a mostrar en la página actual
                        $indiceInicio = ($paginaActual - 1) * $itemsPorPagina;
                        $indiceFin = $indiceInicio + $itemsPorPagina - 1;

                        // Obtén los clientes a mostrar en la página actual
                        $clientesPagina = array_slice($clientesFiltrados, $indiceInicio, $itemsPorPagina);

                        
                        foreach ($clientesPagina as $cliente) {
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

                        // Mostrar la paginación
                        echo '<div id="pagination" class="pagination">';
                        if ($paginaActual > 1) {
                            echo '<a href="?pagina=' . ($paginaActual - 1) . '" class="page-link">Anterior</a> ';
                        } else {
                            echo '<a href="#" class="page-link">Anterior</a> ';
                        }
                        for ($i = 1; $i <= $totalPaginas; $i++) {
                            if ($i == $paginaActual) {
                                echo '<span class="page-link active">' . $i . '</span> ';
                            } else {
                                echo '<a href="?pagina=' . $i . '" class="page-link">' . $i . '</a> ';
                            }
                        }
                        if ($paginaActual < $totalPaginas) {
                            echo '<a href="?pagina=' . ($paginaActual + 1) . '" class="page-link">Siguiente</a>';
                        } else {
                            echo '<a href="#" class="page-link">Siguiente</a>';
                        }
                        echo '</div><br>';
                        
                        }

                ?>
                
            </tbody>
        </table>
        <br>
     </div>
    </div>


    

    <script src="../../js/jquery-3.5.1.min.js"></script>
    <script src="../../js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function () {
            // Escucha el evento 'submit' del formulario
            $('form').submit(function (event) {
            event.preventDefault(); // Evita el envío del formulario por defecto

            // Realiza la solicitud AJAX al script_de_busqueda.php
            $.ajax({
                type: 'GET', // Tipo de solicitud
                url: 'filtrar_clientes.php', // URL del script que procesa la búsqueda
                data: $(this).serialize(), // Serializa el formulario para enviar los datos
                success: function (response) {
                // Inserta la respuesta en el div de resultados
                $('#resultados-busqueda').html(response);
                },
                error: function () {
                alert('Error al buscar clientes');
                },
            });
            });
        });

        //FORMULARIO EMERGENTE:
        // Al hacer clic en el botón "Agregar Cliente", muestra el formulario emergente cargando el contenido de formulario.html
        $("#mostrarFormulario").click(function() {
            $("#formularioEmergente").load("crear_cliente.php").modal("show");
        });
    </script>

    <!--CERRAR MENSAJE ALERTA -->
    <script>
      var alertList = document.querySelectorAll('.alert');
      alertList.forEach(function (alert) {
        new bootstrap.Alert(alert)
      })
    </script>
    
  
</body>
</html>

