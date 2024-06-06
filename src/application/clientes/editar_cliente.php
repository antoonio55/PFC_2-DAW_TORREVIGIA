<?php
include '../../config/config.php';
include 'funciones.php';

session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION["user_id"])) {
    header("Location: ../../../index.php ");
    exit;
}

// Procesar los datos del formulario si se envió un POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los datos del formulario
    $id = $_POST['id_cliente'];
    $nombre = $_POST['nombre'];
    $dni = $_POST['dni'];
    $telefono = $_POST['telefono'];
    $telefonoAdicional = $_POST['telefono_adicional'];
    $email = $_POST['email'];
    $lista_negra = isset($_POST['lista_negra']) ? 1 : 0;
    $anotaciones = $_POST['anotaciones'];
    $rutaFirma = $_POST['ruta_firma'];

    // Asegurarse de que $telefonoAdicional sea nulo si está vacío
    $telefonoAdicional = empty($telefonoAdicional) ? null : $telefonoAdicional;

    // Actualizar los datos del cliente
    if (actualizarCliente($id, $nombre, $dni, $telefono, $telefonoAdicional, $email, $lista_negra, $anotaciones, $rutaFirma)) {
        // Redireccionar a la página de edición del cliente con un mensaje de éxito
        header('Location: editar_cliente.php?id_cliente=' . $id . '&success=1');
        exit();
    } else {
        // Redireccionar a la página de edición del cliente con un mensaje de error
        header('Location: editar_cliente.php?id_cliente=' . $id . '&success=2');
        exit();
    }
} elseif (isset($_GET['id_cliente'])) {
    // Verificar si se proporcionó un ID de cliente en la URL
    $id = $_GET['id_cliente'];
    
    // Obtener el cliente por su ID
    $cliente = obtenerClientePorID($id);

    // Verificar si se proporcionó un parámetro de éxito en la URL y mostrar el mensaje correspondiente
    if (isset($_GET['success'])) {
        $success = $_GET['success'];
        $message = '';

        if ($success == 1) {
            $message = '¡CAMBIOS REALIZADOS! Se han podido actualizar los datos del cliente';
        } elseif ($success == 2) {
            $message = '¡ERROR! No se han podido actualizar los datos del cliente';
        }

        echo '<div class="alert alert-' . ($success == 1 ? 'success' : 'danger') . '" role="alert">';
        echo $message;
        echo '</div>';
    }
} else {
    echo "ID de cliente no proporcionado en la URL";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificar Datos del Cliente</title>
    <!-- Enlaces a los archivos CSS de Bootstrap -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .btn-back {
            margin-bottom: 0px;
            margin-left: -100px;
        }

        .container {
            margin-bottom: 20px;
        }

        /* Estilos para el tamaño del checkbox */
        .form-control-input[type="checkbox"] {
            transform: scale(2.5);
            margin-left: 40px;
            margin-top: 12px;
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <a href="index.php" class="btn btn-outline-dark btn-back">
            <i class="fas fa-arrow-left"></i> <-- Atrás
        </a>
    </div>
    <div class="container">
        <h1>Modificar Datos del Cliente</h1>
        <br>

        <?php if ($cliente) : ?>
            <!-- Formulario para modificar los datos del cliente -->
            <form action="editar_cliente.php" method="POST">
                <div class="row">
                    <!-- Campos del formulario -->
                    <input type="hidden" name="id_cliente" value="<?php echo $cliente['id_cliente']; ?>" required>
                  
                    <div class="form-group col-6">
                        <label for="dni">DNI</label>
                        <input type="text" class="form-control" name="dni" value="<?php echo $cliente['Dni']; ?>" required>
                    </div>
                  
                    <div class="form-group col-6">
                        <label for="nombre">Nombre y Apellidos</label>
                        <input type="text" class="form-control" name="nombre" value="<?php echo $cliente['NombreApellidos']; ?>" required>
                    </div>
                  
                    <div class="form-group col-6">
                        <label for="telefono">Teléfono</label>
                        <input type="text" class="form-control" name="telefono" value="<?php echo $cliente['Telefono']; ?>" required>
                    </div>
                  
                    <div class="form-group col-6">
                        <label for="telefono_adicional">Teléfono Adicional</label>
                        <input type="text" class="form-control" name="telefono_adicional" value="<?php echo $cliente['TelefonoAdicional']; ?>">
                    </div>
                  
                    <div class="form-group col-6">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" name="email" value="<?php echo $cliente['Email']; ?>">
                    </div>
                  
                    <div class="form-group col-6">
                        <label for="lista_negra">En Lista Negra</label>
                        <div class="form-group mb-4">
                            <input type="checkbox" class="form-control-input" name="lista_negra" id="lista_negra" <?php echo $cliente['ListaNegra'] ? 'checked' : ''; ?>>
                        </div>
                    </div>
                  
                    <div class="form-group col-12">
                        <label for="anotaciones">Anotaciones</label>
                        <textarea class="form-control" name="anotaciones"><?php echo $cliente['Anotaciones']; ?></textarea>
                    </div>
                  
                    <div class="form-group col-12">
                        <label for="ruta_firma">Ruta de la Firma</label>
                        <input type="text" class="form-control" name="ruta_firma" value="<?php echo $cliente['ruta_firma']; ?>">
                    </div>
                  
                    <!-- Botón para enviar el formulario -->
                    <div class="form-group col-12">
                        <button type="submit" class="btn btn-primary">Actualizar Datos del Cliente</button>
                    </div>
                </div>
            </form>
        <?php else : ?>
            <div class="alert alert-danger" role="alert">
                ID de cliente no proporcionado en la URL
            </div>
        <?php endif; ?>
    </div>

    <!-- Enlaces a los archivos JS de Bootstrap y jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
