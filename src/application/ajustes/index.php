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

    $empresa = obtenerDatosEmpresa();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Obtiene los valores del formulario:
        $nombreEmpresa = $_POST['nombreEmpresa'];
        $cif = $_POST['cif'];
        $direccion = $_POST['direccion'];
        $telefono = $_POST['telefono'];
        $email = $_POST['email'];
        $eslogan = $_POST['eslogan'];
        $condicionesReparacionTicket = $_POST['condicionesReparacionTicket'];
        $condicionesReparacionFolio = $_POST['condicionesReparacionFolio'];
        $proteccionDatos = $_POST['proteccionDatos'];

        if (actualizarDatosEmpresa($nombreEmpresa, $cif, $direccion, $telefono, $email, $eslogan, $condicionesReparacionTicket, $condicionesReparacionFolio, $proteccionDatos)) {
            // Redirecciona a la página con un mensaje de éxito
            header('Location: index.php?message=1');

            // Asegúrate de que no se ejecute más código después de la redirección
            exit();

        } else {
            // Redirecciona a la página con un mensaje de error
            header('Location: index.php?message=2');
            // Asegúrate de que no se ejecute más código después de la redirección
            exit();
        }

    } elseif (isset($_GET['message'])) {
        if ($_GET['message'] == 1) {
            // Verifica si hay un parámetro de éxito presente en la URL
            // Muestra un mensaje de éxito
            echo '<div class="alert alert-primary" role="alert">';
            echo '   <strong>¡CAMBIOS REALIZADOS!</strong> Se han podido actualizar los datos de la empresa';
            echo '</div>';
        }
        if ($_GET['message'] == 2) {
            // Verifica si hay un parámetro de error presente en la URL
            // Muestra un mensaje de error:
            echo '<div class="alert alert-danger" role="alert">';
            echo '   <strong>¡ERROR!</strong> No se han podido actualizar los datos de la empresa';
            echo '</div>';
        }
    }

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="../../css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Ajustes - Sistema Orden Servicio</h2>

        <div class="accordion accordion-flush" id="accordionFlushExample">
            <div class="accordion-item">
                <h2 class="accordion-header" id="flush-headingOne">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                        DATOS - EMPRESA
                    </button>
                </h2>
                <div id="flush-collapseOne" class="accordion-collapse collapse" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
                    <div class="accordion-body">
                    
                        <form action="index.php" method="POST">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="form-group mt-3">
                                            <label for="nombreEmpresa">Nombre de la empresa:</label>
                                            <input type="text" name="nombreEmpresa" value="<?php echo $empresa['nombreEmpresa']; ?>" class="form-control form-control-lg" required>
                                        </div>

                                        <div class="form-group mt-3">
                                            <label for="eslogan">Eslogan:</label>
                                            <input type="text" name="eslogan" value="<?php echo $empresa['eslogan']; ?>" class="form-control">
                                        </div>
                                                    
                                        <div class="form-group mt-3 col-3">
                                            <label for="cif">CIF:</label>
                                            <input type="text" name="cif" value="<?php echo $empresa['cif']; ?>" class="form-control">
                                        </div>

                                        <div class="form-group mt-3 col-9">
                                            <label for="direccion">Dirección:</label>
                                            <input type="text" name="direccion" value="<?php echo $empresa['direccion']; ?>" class="form-control">
                                        </div>

                                        <div class="form-group mt-3 col-4">
                                            <label for="telefono">Teléfono:</label>
                                            <input type="text" name="telefono" value="<?php echo $empresa['telefono']; ?>" class="form-control">
                                        </div>

                                        <div class="form-group mt-3 col-8">
                                            <label for="email">Email:</label>
                                            <input type="email" name="email" value="<?php echo $empresa['email']; ?>" class="form-control" required>
                                        </div>

                                        <div class="form-group mt-3">
                                            <label for="condicionesReparacionTicket">Condiciones Reparación Ticket:</label>
                                            <textarea name="condicionesReparacionTicket" class="form-control form-control-lg" rows="8" required><?php echo $empresa['condicionesReparacionTicket']; ?></textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">

                                    <div class="form-group mt-3">
                                        <label for="condicionesReparacionFolio">Condiciones Reparación Folio:</label>
                                        <textarea name="condicionesReparacionFolio" class="form-control form-control-lg" rows="9" required><?php echo $empresa['condicionesReparacionFolio']; ?></textarea>
                                    </div>

                                    <div class="form-group mt-3">
                                        <label for="proteccionDatos">Protección de Datos:</label>
                                        <textarea name="proteccionDatos" class="form-control form-control-lg" rows="8" required><?php echo $empresa['proteccionDatos']; ?></textarea>
                                    </div>

                                </div>
                                

                                <button type="submit" class="btn btn-primary mt-3">Actualizar</button>
                            </div>
                        </form>


                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header" id="flush-headingTwo">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseTwo" aria-expanded="false" aria-controls="flush-collapseTwo">
                    Accordion Item #2
                </button>
                </h2>
                <div id="flush-collapseTwo" class="accordion-collapse collapse" aria-labelledby="flush-headingTwo" data-bs-parent="#accordionFlushExample">
                <div class="accordion-body">Placeholder content for this accordion, which is intended to demonstrate the <code>.accordion-flush</code> class. This is the second item's accordion body. Let's imagine this being filled with some actual content.</div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header" id="flush-headingThree">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseThree" aria-expanded="false" aria-controls="flush-collapseThree">
                    Accordion Item #3
                </button>
                </h2>
                <div id="flush-collapseThree" class="accordion-collapse collapse" aria-labelledby="flush-headingThree" data-bs-parent="#accordionFlushExample">
                <div class="accordion-body">Placeholder content for this accordion, which is intended to demonstrate the <code>.accordion-flush</code> class. This is the third item's accordion body. Nothing more exciting happening here in terms of content, but just filling up the space to make it look, at least at first glance, a bit more representative of how this would look in a real-world application.</div>
                </div>
            </div>
        </div>
        
    </div>

    <script src="../../js/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="../../js/bootstrap.min.js"></script>

    <script>
        $(document).ready(function(){
        // Cuando se expande o colapsa el panel, recalcular el tamaño del iframe
        $('.collapse').on('shown.bs.collapse hidden.bs.collapse', function () {
            resizeIframe();
        });
        });

        // Función para recalcular el tamaño del iframe
        function resizeIframe() {
        var iframe = document.getElementById('iframe_principal');
        if (iframe) {
            iframe.style.height = iframe.contentWindow.document.body.scrollHeight + 'px';
        }
        }
    </script>
</body>
</html>