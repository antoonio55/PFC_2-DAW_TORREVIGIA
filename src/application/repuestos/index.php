<?php
    // Incluimos el archivo de configuración y de funciones:
    include '../../config/config.php';
    include 'funciones.php';
    
    // Comprobar la autenticación del usuario
    session_start();
    if (!isset($_SESSION["user_id"])) {
        error_log("Intento de acceso no autorizado: " . $_SERVER['REQUEST_URI']);
        header("Location: ../login/index.php");
        exit;
    }
    session_regenerate_id(true);
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
                <h1>Listado de Piezas de Repuesto</h1>
            </div>
            <div class="col text-right">
                <button id="mostrarFormulario" style="float:right;" class="btn btn-primary mb-3">Agregar Pieza de Repuesto</button>
            </div>
        </div>

        <div id="formularioEmergente" class="modal" style="display: none;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <!-- Carga el contenido del formulario de creación de piezas de repuesto aquí mediante una solicitud AJAX -->
                </div>
            </div>
        </div>
      	
      	<div class="filter-section mb-4 border-top border-bottom border-2 p-3">
            <h4>Filtrar Piezas de Repuesto</h4>
            <form class="form-inline" method="GET" action="filtrar_repuestos.php">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group mb-3">
                            <label for="nombre">Nombre de la Pieza:</label>
                            <input type="text" class="form-control" id="nombre" name="nombre_name" placeholder="Ejemplo: Tornillo">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group mb-3">
                            <label for="cantidad">Cantidad:</label>
                            <input type="number" class="form-control" id="cantidad" name="cantidad_name" placeholder="Ejemplo: 10">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group mb-3">
                            <label for="precio_unitario">Precio Unitario:</label>
                            <input type="text" class="form-control" id="precio_unitario" name="precio_name" placeholder="Ejemplo: 5.00">
                        </div>
                    </div>
                    <div class="col-md-3 mt-4">
                        <button type="submit" class="btn btn-primary btn-block">Buscar</button>
                    </div>
                </div>
            </form>
        </div>
      
        <div id="resultados-busqueda">
            <div class="table-container">
                <table class="table table-bordered text-center">
                    <thead class="thead-light">
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Cantidad</th>
                            <th>Precio Unitario</th>
                            <th>Descripción</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                                // Lógica para obtener y mostrar piezas de repuesto
                                $piezasRepuesto = obtenerPiezasRepuesto();
                                foreach ($piezasRepuesto as $pieza) {
                                    echo '<tr>';
                                    echo '<td>' . $pieza['id_pieza'] . '</td>';
                                    echo '<td>' . $pieza['nombre_pieza'] . '</td>';
                                    echo '<td>' . $pieza['cantidad_pieza'] . '</td>';
                                    echo '<td>' . $pieza['precio_pieza'] . '</td>';
                                    echo '<td>' . $pieza['descripcion_pieza'] . '</td>';
                                    echo '<td>';
                                    echo '<a href="editar_repuesto.php?id_pieza=' . $pieza['id_pieza'] . '" class="btn btn-warning"><i class="fas fa-edit"></i></a>';
                                    echo '</td>';
                                    echo '</tr>';
                                }
                            }
                        ?>
                    </tbody>
                </table>
            </div>
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
                url: 'filtrar_repuestos.php', // URL del script que procesa la búsqueda
                data: $(this).serialize(), // Serializa el formulario para enviar los datos
                success: function (response) {
                // Inserta la respuesta en el div de resultados
                $('#resultados-busqueda').html(response);
                },
                error: function () {
                alert('Error al buscar repuestos');
                },
            });
            });
        });
    </script>
    
    <script>
        // JavaScript para cargar el formulario emergente
        $(document).ready(function () {
            $("#mostrarFormulario").click(function() {
                $("#formularioEmergente").load("crear_repuesto.php").modal("show");
            });
        });
    </script>

</body>
</html>

