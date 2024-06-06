<?php
// Incluye tus funciones y realiza la búsqueda
include '../../config/config.php';
include 'funciones.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST["nombre_name"];
    $cantidad = $_POST["cantidad_name"];
    $precio_unitario = $_POST["precio_unitario_name"];
    $descripcion = $_POST["descripcion_name"];
    
    if (insertarPieza($nombre, $cantidad, $precio_unitario, $descripcion)) {
        // Redirecciona al listado de piezas o muestra un mensaje de éxito
        header('Location: index.php');
        exit();
    } else {
        // Muestra un mensaje de error
        echo "Error al agregar la pieza.";
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
    <title>Formulario de Repuestos</title>
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
        .background {
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
                        <span class="close-button" onclick="cerrarEmergente()">X</span>
                        Formulario Repuesto
                    </div>
                    <div class="card-body background">
                      <form action="crear_repuesto.php" method="POST">
                          <div class="form-group mb-3">
                              <label for="nombre">Nombre:</label>
                              <input type="text" class="form-control" id="nombre" name="nombre_name" required>
                          </div>
                          <div class="form-group mb-3">
                              <label for="cantidad">Cantidad:</label>
                              <input type="number" class="form-control" id="cantidad" name="cantidad_name" required>
                          </div>
                          <div class="form-group mb-3">
                              <label for="precio_unitario">Precio Unitario:</label>
                              <input type="number" step="0.01" class="form-control" id="precio_unitario" name="precio_unitario_name" required>
                          </div>
                          <div class="form-group mb-3">
                              <label for="descripcion">Descripción:</label>
                              <textarea class="form-control" id="descripcion" name="descripcion_name" required></textarea>
                          </div>
                          <button type="submit" class="btn btn-primary">Crear</button>
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
     function cerrarEmergente() {
         $('#formularioEmergente').modal('hide');
     }
</script>
</body>
</html>

