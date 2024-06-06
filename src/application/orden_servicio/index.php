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
include 'funciones.php'; // Asegúrate de que esta inclusión sea relevante para la función obtenerOrdenes()

$orden = obtenerOrdenes();
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
                <h1>Listado de Órdenes</h1>
            </div>
            <div class="col text-right">
                <!--<a href="crear_orden.php" style="float:right;" class="btn btn-primary mb-3">Agregar Orden</a>-->
                <button id="mostrarFormulario" style="float:right;" class="btn btn-primary mb-3">Agregar Orden</button>
            </div>
        </div>

        <div id="formularioOrdenEmergente" class="modal" style="display: none;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <!-- Carga el contenido del formulario.html aquí mediante una solicitud AJAX -->
                </div>
            </div>
        </div>
      
		<div class="filter-section mb-4 border-top border-bottom border-2 p-3">
            <h4>Filtrar Ordenes</h4>
            <form class="form-inline" method="GET" action="#">
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group mb-3">
                            <label for="numero">Número de Orden:</label>
                            <input type="text" class="form-control" id="numero" name="numero_name" placeholder="Ejemplo: 12345">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group mb-3">
                            <label for="fecha">Fecha de Servicio:</label>
                            <input type="date" class="form-control" id="fecha" name="fecha_name">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group mb-3">
                            <label for="dni">DNI del Cliente:</label>
                            <input type="text" class="form-control" id="dni" name="dni_name" placeholder="Ejemplo: 12345678X">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="nombre">Nombre del Cliente:</label>
                            <input type="text" class="form-control" id="nombre" name="nombre_name" placeholder="Ejemplo: Juan">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group mb-3">
                            <label for="telefono">Teléfono:</label>
                            <input type="text" class="form-control" id="telefono" name="telefono_name" placeholder="Ejemplo: 6XX XXX XXX">
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="form-group mb-3">
                            <label for="imei">IMEI:</label>
                            <input type="text" class="form-control" id="imei" name="imei_name" placeholder="Ejemplo: 123456789012345">
                        </div>
                    </div>
                    <div class="col-md-1 mt-4">
                        <button type="submit" class="btn btn-primary btn-block">Buscar</button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Aquí utilizamos la misma estructura de tabla que ya tenías en el código original -->
        <div id="resultados-busqueda">
            <div class="table-container">
                <table class="table table-bordered text-center">
                    <thead class="thead-light">
                        <tr>
                            <!--<th>ID</th>-->
                            <th>Nº</th>
                            <th>Fecha</th>
                            <th>Dni</th>
                            <th>Nombre y Apellidos</th>
                            <th>Teléfono</th>
                            <th>Modelo</th>
                            <th>IMEI/SN</th>
                            <!--<th>Contraseña</th>-->
                            <th width="500px">Avería</th>
                            <!--<th>Precio</th>-->
                            <th>Estado</th>
                            <th class="custom-actions">Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php

                            // Verificar si se ha enviado el formulario
                            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                                // Recopilar los valores del formulario
                              	
                              	// Verificar y obtener el valor de 'numero_name'
                                if (isset($_GET['numero_name'])) {
                                    $numero = $_GET['numero_name'];
                                } else {
                                    // Manejar el caso en el que 'numero_name' no está presente en la consulta GET
                                    $numero = ''; // O cualquier otro valor por defecto que desees
                                }
                              
                                // Verificar y obtener el valor de 'fecha_name'
                                if (isset($_GET['fecha_name'])) {
                                    $fecha = $_GET['fecha_name'];
                                } else {
                                    // Manejar el caso en el que 'fecha_name' no está presente en la consulta GET
                                    $fecha = ''; // O cualquier otro valor por defecto que desees
                                }

                                // Verificar y obtener el valor de 'dni_name'
                                if (isset($_GET['dni_name'])) {
                                    $dni = $_GET['dni_name'];
                                } else {
                                    // Manejar el caso en el que 'dni_name' no está presente en la consulta GET
                                    $dni = ''; // O cualquier otro valor por defecto que desees
                                }

                                // Verificar y obtener el valor de 'nombre_name'
                                if (isset($_GET['nombre_name'])) {
                                    $nombre = $_GET['nombre_name'];
                                } else {
                                    // Manejar el caso en el que 'nombre_name' no está presente en la consulta GET
                                    $nombre = ''; // O cualquier otro valor por defecto que desees
                                }

                                // Verificar y obtener el valor de 'telefono_name'
                                if (isset($_GET['telefono_name'])) {
                                    $telefono = $_GET['telefono_name'];
                                } else {
                                    // Manejar el caso en el que 'telefono_name' no está presente en la consulta GET
                                    $telefono = ''; // O cualquier otro valor por defecto que desees
                                }

                                // Verificar y obtener el valor de 'imei_name'
                                if (isset($_GET['imei_name'])) {
                                    $imei = $_GET['imei_name'];
                                } else {
                                    // Manejar el caso en el que 'imei_name' no está presente en la consulta GET
                                    $imei = ''; // O cualquier otro valor por defecto que desees
                                }


                                // Obtén los ordenes filtrados
                                $ordenesFiltrados = obtenerOrdenesFiltradas($numero, $fecha, $dni, $nombre, $telefono, $imei);
                                
                                // Número de elementos por página
                                $itemsPorPagina = 10;

                                // Número total de clientes filtrados
                                $totalOrdenes = count($ordenesFiltrados);

                                // Número total de páginas
                                $totalPaginas = ceil($totalOrdenes / $itemsPorPagina);

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
                                $clientesPagina = array_slice($ordenesFiltrados, $indiceInicio, $itemsPorPagina);

                                foreach ($ordenesFiltrados as $orden) {
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
            url: 'filtrar_orden.php', // URL del script que procesa la búsqueda
            data: $(this).serialize(), // Serializa el formulario para enviar los datos
            success: function (response) {
            // Inserta la respuesta en el div de resultados
            $('#resultados-busqueda').html(response);
            },
            error: function () {
            alert('Error al buscar ordenes');
            },
        });
        });
    });
    </script>

    <script>
        //FORMULARIO EMERGENTE:
        // Al hacer clic en el botón "Agregar Cliente", muestra el formulario emergente cargando el contenido de formulario.html
        $("#mostrarFormulario").click(function() {
            $("#formularioOrdenEmergente").load("crear_orden.php").modal("show");
        });
    </script>

</body>
</html>

