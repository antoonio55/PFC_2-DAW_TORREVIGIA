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
    $id_pieza = $_POST['id_name'];
    $nombre_pieza = $_POST['nombre_name'];
    $cantidad_pieza = $_POST['cantidad_name'];
    $precio_pieza = $_POST['precio_name'];
    $descripcion_pieza = $_POST['descripcion_name'];

    // Actualizar los datos del repuesto
    if (actualizarPieza($id_pieza, $nombre_pieza, $cantidad_pieza, $precio_pieza, $descripcion_pieza)) {
        // Redireccionar a la página de edición del cliente con un mensaje de éxito
        header('Location: editar_repuesto.php?id_pieza=' . $id_pieza . '&success=1');
        exit();
    } else {
        // Redireccionar a la página de edición del cliente con un mensaje de error
        header('Location: editar_repuesto.php?id_pieza=' . $id_pieza . '&success=2');
        exit();
    }
} elseif (isset($_GET['id_pieza'])) {
    // Verificar si se proporcionó un ID de cliente en la URL
    $id = $_GET['id_pieza'];
    
    // Obtener el cliente por su ID
    $pieza = obtenerPiezaPorID($id);

    // Verificar si se proporcionó un parámetro de éxito en la URL y mostrar el mensaje correspondiente
    if (isset($_GET['success'])) {
        $success = $_GET['success'];
        $message = '';

        if ($success == 1) {
            $message = '¡CAMBIOS REALIZADOS! Se han podido actualizar los datos de la pieza';
        } elseif ($success == 2) {
            $message = '¡ERROR! No se han podido actualizar los datos de la pieza';
        }

        echo '<div class="alert alert-' . ($success == 1 ? 'success' : 'danger') . '" role="alert">';
        echo $message;
        echo '</div>';
    }
} else {
    echo "ID de la pieza no proporcionado en la URL";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificar Datos del Repuesto</title>
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
        <h1>Modificar Datos del Repuesto</h1>
        <br>

        <?php if ($pieza) : ?>
            <!-- Formulario para modificar los datos del cliente -->
            <form action="editar_repuesto.php" method="POST">
                <div class="row">
                    <!-- Campos del formulario -->
                    <input type="hidden" name="id_name" value="<?php echo $pieza['id_pieza']; ?>" required>
                  
                    <div class="form-group col-6">
                        <label for="nombre">Nombre</label>
                        <input type="text" class="form-control" name="nombre_name" value="<?php echo $pieza['nombre_pieza']; ?>" required>
                    </div>
                  
                    <div class="form-group col-6">
                        <label for="cantidad">Cantidad</label>
                        <input type="number" class="form-control" name="cantidad_name" value="<?php echo $pieza['cantidad_pieza']; ?>" required>
                    </div>
                  
                    <div class="form-group col-6">
                        <label for="precio">Precio</label>
                        <input type="number" class="form-control" name="precio_name" value="<?php echo $pieza['precio_pieza']; ?>" required>
                    </div>
                  
                    <div class="form-group col-12">
                        <label for="descripcion">Descripción</label>
                        <textarea class="form-control" name="descripcion_name"><?php echo $pieza['descripcion_pieza']; ?></textarea>
                    </div>
                  
                    <!-- Botón para enviar el formulario -->
                    <div class="form-group col-12">
                        <button type="submit" class="btn btn-primary">Actualizar Datos del Repuesto</button>
                    </div>
                </div>
            </form>
        <?php else : ?>
            <div class="alert alert-danger" role="alert">
                ID del repuesto no proporcionado en la URL
            </div>
        <?php endif; ?>
    </div>

    <!-- Enlaces a los archivos JS de Bootstrap y jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
