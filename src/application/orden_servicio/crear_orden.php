<?php
include '../../config/config.php';
include 'funciones.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $numero = $_POST["numero_name"];
    $fecha = $_POST["fecha_name"];
    $id_cliente = $_POST["id_cliente_name"];
    $nombre = $_POST["nombre_name"];
    $telefono = $_POST["telefono_name"];
    $modelo = $_POST["modelo_name"];
    $imei_sn = $_POST["imei_sn_name"];
    $contrasena = $_POST["contrasena_name"];
    $descripcion = $_POST["descripcion_name"];
    $precio = $_POST["precio_name"];
    $estado = $_POST["estado_name"];
    
    if (agregarOrden($numero, $fecha, $fechaModificacion, $modelo, $imei_sn, $contrasena, $descripcion, $precio, $estado, $id_cliente)) {
        // Redirecciona al listado de órdenes o muestra un mensaje de éxito
        header('Location: index.php');
        exit();
    } else {
        // Muestra un mensaje de error
        echo "Error al agregar la orden.";
    }
}
?>
<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: ../../../index.php ");
    exit;
}

// El usuario está autenticado, puedes mostrar el contenido de la página protegida aquí.
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Formulario de Reparación</title>
    <!-- Agrega la referencia a Bootstrap CSS -->
    <link rel="stylesheet" href="../../css/bootstrap.min.css">
    <!-- Agrega la referencia a Font Awesome -->
    <link rel="stylesheet" href="../../font-awesome/css/all.css">
    <style>
        /* Agrega estilos personalizados aquí */
        .container-personalizado {
            margin-top: 30px; /* Margen superior */
            margin-bottom: 10px; /* Margen inferior */
            margin-left: 200px; /* Margen izquierdo */
            margin-right: 200px; /* Margen derecho */

            padding-top: 10px; /* Margen interior en la parte superior */
            padding-bottom: 10px; /* Margen interior en la parte inferior */
            padding-left: 40px; /* Margen interior en el lado izquierdo */
            padding-right: 40px; /* Margen interior en el lado derecho */
        }
        /* Definir una clase personalizada para un fondo amarillo */
        .bg-orden_servicio {
            background-color: rgb(255, 255, 255);
            /* Puedes ajustar otros estilos según tus preferencias */
        }

        .close-button {
            float: right; /* Alinea el botón a la derecha del encabezado */
            cursor: pointer; /* Cambia el cursor al pasar por encima para indicar que es un botón */
        }

        .close-button:hover {
            color: red; /* Cambia el color del botón al pasar el cursor por encima (opcional) */
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <span class="close-button" onclick="cerrarModalOrden()">X</span>
                        Formulario Orden Servicio
                    </div>
                    <div class="card-body bg-orden_servicio">
                        <form id="formulario_agregar_os" action="crear_orden.php" method="POST">
                            <div class="form-group mb-3">
                                <label for="numero">Nº :</label>
                                <input type="text" class="form-control" id="numero" name="numero_name" value="<?php echo $numero = generarCodigoUnico(5); ?>" required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="fecha">Fecha:</label>
                                <input type="date" class="form-control" id="fecha" name="fecha_name" required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="dni">DNI:</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="dni" name="dni_name" required>
                                    <div class="input-group-append">
                                        <button type="button" class="btn" id="search_btn" disabled><i class="fa fa-search" style="color: #000000;"></i></button>
                                    </div>
                                </div>
                            </div>

                            <!-- Este input no se muestra pero almacena el id del cliente -->
                            <input type="hidden" name="id_cliente_name" id="id_cliente" value=""> 
                            
                            <div class="form-group mb-3">
                                <label for="nombre">Nombre y apellidos:</label>
                                <input type="text" class="form-control" id="nombre" name="nombre_name"  disabled required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="telefono">Teléfono:</label>
                                <input type="tel" class="form-control" id="telefono" name="telefono_name"  disabled required>
                            </div>
                            
                            <div class="form-group mb-3">
                                <label for="modelo">Modelo:</label>
                                <input type="text" class="form-control" id="modelo" name="modelo_name" required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="imei">IMEI / SN:</label>
                                <input type="text" class="form-control" id="imei_sn" name="imei_sn_name">
                            </div>
                            <div class="form-group mb-3">
                                <label for="contrasena">Contraseña:</label>
                                <input type="text" class="form-control" id="contrasena" name="contrasena_name">
                            </div>
                            <div class="form-group mb-3">
                                <label for="descripcion">Avería:</label>
                                <textarea class="form-control" id="descripcion" name="descripcion_name" required></textarea>
                            </div>
                            <div class="form-group mb-3">
                                <label for="precio">Precio:</label>
                                <input type="number" class="form-control" id="precio" name="precio_name" required>
                            </div>

                            <!-- Botón del desplegable -->
                            <div class="form-group mb-5">
                                <label for="exampleFormControlSelect1">Estado Reparación:</label>
                                <select class="form-control" id="exampleFormControlSelect1" name="estado_name">
                                <option selected>ADMITIDO</option>
                                <option>EN REVISIÓN</option>
                                <option>EN ESPERA DE APROBACIÓN</option>
                                <option>EN ESPERA DE PIEZAS</option>
                                <option>EN REPARACIÓN</option>
                                <option>EN PRUEBAS</option>
                                <option>ESPERANDO RECOGIDA</option>
                                <option>ENTREGADO</option>
                                <option>CANCELADO / S.S.R</option>
                                <option>EN TALLER EXTERNO</option>
                                <option>EN GARANTÍA</option>
                                </select>
                            </div>

                            <div class="d-grid gap-2 mb-3">
                                <button id="enviar_form_bt" type="submit" class="btn btn-outline-success btn-lg">Enviar</button>    
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Configura el modal para que no cierre haciendo clic fuera y carga el formulario
        $('#formularioOrdenEmergente').modal({ backdrop: 'static' });

        // Función para cerrar el modal
        function cerrarModalOrden() {
            $('#formularioOrdenEmergente').modal('hide');
        }
    </script>

    <!-- Recopilar los valores de los campos desactivados antes de enviar el formulario -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Selecciona el formulario y agrega un evento de envío
            document.getElementById("formulario_agregar_os").addEventListener("submit", function(event) {
                // Habilita los campos desactivados antes de enviar el formulario
                document.getElementById('nombre').removeAttribute('disabled');
                document.getElementById('telefono').removeAttribute('disabled');
            });
        });
    
    </script>


    <!-- Buscar cliente -->
    <script>
        document.getElementById('search_btn').addEventListener('click', function() {
            var dni = document.getElementById('dni').value;

            // Realizar una solicitud AJAX al servidor para buscar el cliente por DNI
            $.ajax({
                url: 'funciones.php', // Ruta al archivo PHP con las funciones
                type: 'POST', // Utiliza POST para enviar el DNI de manera segura
                data: {
                    action: 'buscarCliente', // Acción para buscar un cliente
                    dni: dni
                },
                success: function(data) {
                    var respuesta = JSON.parse(data);
                    if (respuesta && respuesta.length > 0) {
                        // Actualizar los campos del formulario con los datos recibidos
                        document.getElementById('nombre').value = respuesta[0].NombreApellidos;
                        document.getElementById('telefono').value = respuesta[0].Telefono;
                        document.getElementById('id_cliente').value = respuesta[0].id; // Asigna el valor a id_cliente
                    } else {
                        alert('Cliente no encontrado.');
                        document.getElementById("nombre").removeAttribute("disabled");
                        document.getElementById("telefono").removeAttribute("disabled");
                    }
                },
                error: function() {
                    // Manejar errores de la solicitud AJAX
                    alert('Error al buscar el cliente.');
                }
            });
        });
    </script>

    <!-- Agregar cliente -->
    <script>
    document.getElementById('formulario_agregar_os').addEventListener('submit', function(event) {
        event.preventDefault(); // Evitar el envío predeterminado del formulario

        var dni = document.getElementById('dni').value;
        var nombre = document.getElementById('nombre').value;
        var telefono = document.getElementById('telefono').value;

        if (dni && !nombre && !telefono) {
            // El DNI se ingresó, pero no se encontró el cliente
            alert('Cliente no encontrado. Por favor, complete los campos de nombre y teléfono.');
        } else {
            // Verificar si el cliente ya existe o si se debe agregar
            if (!nombre || !telefono) {
                alert('Por favor, complete los campos de nombre y teléfono del nuevo cliente.');
            } else {
                // Realizar una solicitud AJAX para buscar o agregar el cliente según sea necesario
                $.ajax({
                    url: 'funciones.php', // Ruta del script del servidor
                    type: 'POST',
                    data: {
                        action: 'insertarCliente',
                        dni: dni, nombre: nombre, telefono: telefono 
                    },
                    success: function(data) {
                        var respuesta = JSON.parse(data);
                        if (respuesta.success) {
                            // Cliente encontrado o agregado con éxito, ahora puedes enviar el formulario principal
                            document.getElementById('id_cliente').value = respuesta.cliente_id;
                            document.getElementById('formulario_agregar_os').submit(); // Enviar el formulario principal
                        } else {
                            alert('Error al agregar el cliente.');
                        }
                    }
                });
            }
        }
    });
    </script>

    <!--FUNCIÓN ACTIVAR / DESACTIVAR BOTÓN BUSCAR CLIENTE -->
    <script>
        // Obtén una referencia al input y al botón
        var input_dni = document.getElementById("dni");
        var boton_search = document.getElementById("search_btn");

        // Agrega un controlador de eventos al campo DNI para habilitar/deshabilitar el botón
        input_dni.addEventListener("input", function() {
            if (input_dni.value.trim() !== "") {
                // Si el campo DNI no está vacío, habilita el botón
                boton_search.removeAttribute("disabled");
            } else {
                // Si el campo DNI está vacío, deshabilita el botón
                boton_search.setAttribute("disabled", "disabled");
            }
        });
    </script>

    <!-- Agrega la referencia a Bootstrap, JS, jQuery al final del body 
    <script src="../../js/jquery-3.5.1.min.js"></script>
    <script src="../../js/bootstrap.min.js"></script>-->
</body>
</html>
