<?php
include '../../config/config.php';
include 'funciones.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $dni = $_POST['dni'];
    $telefono = $_POST['telefono'];
    $telefonoAdicional = $_POST['telefono_adicional'];
    $email = $_POST['email'];
    $anotaciones = $_POST['anotaciones'];

    //CONDICIONES:
    if ($telefonoAdicional === '') {
        $telefonoAdicional = null;
    }

    if (agregarCliente($nombre, $dni, $telefono, $telefonoAdicional, $email, $anotaciones)) {
        // Redireccionar al listado de clientes
        header('Location: index.php?mensaje=exito');
        exit; // Asegúrate de detener la ejecución del script después de la redirección
    
    } else {
        // Redireccionar al listado de clientes
        header('Location: index.php');
        exit; // Asegúrate de detener la ejecución del script después de la redirección
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
    <!-- Agrega la referencia a Bootstrap CSS -->
    <link rel="stylesheet" type="text/css" href="../../css/bootstrap.min.css">
    <style>
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
                        <span class="close-button" onclick="cerrarModal()">X</span>
                        Formulario de Cliente
                    </div>
                    <div class="card-body">
                        <form action="crear_cliente.php" method="post">
                            <!-- Agrega tus campos de formulario aquí -->
                            <div class="form-group mb-3">
                                <label for="nombre">Nombre:</label>
                                <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Nombre" required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="dni">Dni:</label>
                                <input type="text" class="form-control" id="dni" name="dni" placeholder="Dni" required>
                            </div>

                            <div id="mensaje" class="alert alert-primary" role="alert" style="display: none;"></div>

                            <div class="form-group mb-3">
                                <label for="telefono">Teléfono:</label>
                                <input type="text" class="form-control" id="telefono" name="telefono" placeholder="Teléfono" required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="telefono2">Teléfono Adicional:</label>
                                <input type="text" class="form-control" id="telefono2" name="telefono_adicional" placeholder="Teléfono Adicional">
                            </div>
                            <div class="form-group mb-3">
                                <label for="email">Correo electrónico:</label>
                                <input type="email" class="form-control" id="email" name="email" placeholder="Correo electrónico">
                            </div>
                            <div class="form-group mb-3">
                                <label for="anotaciones">Anotaciones:</label>
                                <textarea class="form-control" id="anotaciones" name="anotaciones" placeholder="Anotaciones"></textarea>
                            </div>
                            
                            <button type="submit" class="btn btn-primary mt-1" id="botonGuardar">Guardar</button>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Configura el modal para que no cierre haciendo clic fuera y carga el formulario
        $('#formularioEmergente').modal({ backdrop: 'static' });

        // Función para cerrar el modal
        function cerrarModal() {
            $('#formularioEmergente').modal('hide');
        }
    </script>

    <script>
        $(document).ready(function() {
            var $dniInput = $("#dni");
            var $mensaje = $("#mensaje");
            var $botonGuardar = $("#botonGuardar");

            $mensaje.hide(); // Oculta el mensaje al cargar la página

            $dniInput.on("input", function() {
                var dni = $(this).val();
                if (dni !== "") {
                    $.ajax({
                        url: "funciones.php", // Puedes mantener el mismo archivo
                        type: "POST",
                        data: { dni: dni, action: 'verificarDNI' }, // Enviando el nombre de la función como acción
                        success: function(response) {
                            if (response === "existe") {
                                $mensaje.html("El DNI ya está en uso. Por favor, ingrese un DNI diferente.");
                                $mensaje.show(); // Muestra el mensaje
                                $botonGuardar.prop("disabled", true); // Desactiva el botón
                            } else {
                                $mensaje.html("");
                                $mensaje.hide(); // Oculta el mensaje
                                $botonGuardar.prop("disabled", false); // Activa el botón
                            }
                        }
                    });
                } else {
                    $mensaje.html("");
                    $mensaje.hide(); // Oculta el mensaje
                    $botonGuardar.prop("disabled", false); // Activa el botón
                }
            });
        });
    </script>

</body>
</html>
