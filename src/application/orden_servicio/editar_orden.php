<?php
include '../../config/config.php';
include 'funciones.php';

// Inicia la sesión si no está iniciada
session_start();

// Verifica la autenticación del usuario
if (!isset($_SESSION["user_id"])) {
    header("Location: ../../../index.php ");
    exit;
}

// Obtén el mensaje de éxito de la variable de sesión y luego elimínalo
$success_message = isset($_SESSION['success_message']) ? $_SESSION['success_message'] : null;
unset($_SESSION['success_message']);

// Variables para almacenar datos del formulario
$id_orden = null;
$orden = null;

// Procesa el formulario si se ha enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitiza y valida los datos
    $id_orden = filter_input(INPUT_POST, 'id_name', FILTER_SANITIZE_NUMBER_INT);
    $modelo = filter_input(INPUT_POST, 'modelo_name', 513); // En lugar de FILTER_SANITIZE_STRING
    $imei_sn = filter_input(INPUT_POST, 'imei_sn_name', 513); // En lugar de FILTER_SANITIZE_STRING
    $contrasena = filter_input(INPUT_POST, 'contrasena_name', 513); // En lugar de FILTER_SANITIZE_STRING
    $descripcion = filter_input(INPUT_POST, 'descripcion_name', 513); // En lugar de FILTER_SANITIZE_STRING
    $diagnostico_tecnico = filter_input(INPUT_POST, 'diagnostico_tecnico_name', 513); // En lugar de FILTER_SANITIZE_STRING
    $garantia = filter_input(INPUT_POST, 'garantia_name', 513); // En lugar de FILTER_SANITIZE_STRING
    $estado = filter_input(INPUT_POST, 'estado_name', 513); // En lugar de FILTER_SANITIZE_STRING
    $precio = filter_input(INPUT_POST, 'precio_name', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);

    $fecha_modificacion = date("Y-m-d H:i:s");

    // Actualiza la orden y muestra el mensaje de éxito
    if (actualizarOrden($id_orden, $modelo, $imei_sn, $contrasena, $descripcion, $diagnostico_tecnico, $garantia, $estado, $precio, $fecha_modificacion)) {
        $success_message = 'La orden se ha actualizado exitosamente.';
      	$orden_id = $id_orden;
      	$orden = obtenerOrdenPorID($id_orden);
    } else {
        // Muestra un mensaje de error
        echo "Error al agregar la orden.";
    }
}

// Si hay un id_orden en la URL, obtén la orden correspondiente
if (isset($_GET['id_orden'])) {
    $orden_id = $_GET['id_orden'];
    $orden = obtenerOrdenPorID($orden_id);
}
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
            background-color: rgb(213, 245, 227);
            /* Puedes ajustar otros estilos según tus preferencias */
        }
        
        .btn-back {
            margin-bottom: 0px;
            margin-left: -20px;
        }
      	
      	.button-update {
          	margin-left: -15px;
          	margin-right: 20px;
      	}

        .container {
            margin-bottom: 20px; /* Ajusta el valor según tu preferencia */
        }

    </style>
</head>
<body>
  
  	<?php
      // Muestra el mensaje de éxito si existe
      if ($success_message) {
          echo '<div class="alert alert-success alert-dismissible fade show" role="alert">';
          echo $success_message;
          echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>';
          echo '</div>';
      }
    ?>

    <div class="container mt-4">
        <a href="index.php" class="btn btn-outline-dark btn-back">
            <i class="fas fa-arrow-left"></i> Atrás
        </a>
    </div>
    <div class="container mt-4">
      
      	<ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="editar-tab" data-bs-toggle="tab" data-bs-target="#editar" type="button" role="tab" aria-controls="editar" aria-selected="true">Editar Orden de Servicio</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="otro-formulario-tab" data-bs-toggle="tab" data-bs-target="#otro-formulario" type="button" role="tab" aria-controls="otro-formulario" aria-selected="false">Añadir Repuestos</button>
            </li>
        </ul>
      
      	<div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active p-3 mb-3" id="editar" role="tabpanel" aria-labelledby="editar-tab">

              <?php
                  if ($orden) {
                      // Generar el formulario con los datos precargados
                      echo '<form id="formulario_editar_os" action="editar_orden.php" method="POST">';

                      echo '<input type="hidden" name="id_name" value="' . $orden["id_orden"] . '">';

                      echo '<div class="row">';
                      echo '<div class="col-2 form-group mb-3">';
                      echo '<label for="numero">Nº:</label>';
                      echo '<input type="text" class="form-control" id="numero" name="not_valid_numero_name" disabled required value="' . $orden["Numero"] . '">';
                      echo '<input type="hidden" name="valid_numero_name" value="' . $orden["Numero"] . '">';
                      echo '</div>';

                      echo '<div class="col-2 form-group mb-3">';
                      echo '<label for="fecha">Fecha:</label>';
                      echo '<input type="date" class="form-control" id="fecha" name="fecha_name" disabled required value="' . $orden["Fecha"] . '">';
                      echo '</div>';

                      echo '<div class="col-2 form-group mb-3">';
                      echo '<label for="dni">DNI:</label>';
                      echo '<input type="text" class="form-control" id="dni" name="dni_name" disabled required value="' . $orden["Dni"] . '">';
                      echo '</div>';

                      echo '<input type="hidden" name="id_cliente_name" id="id_cliente" value="' . $orden["cliente_id"] . '">';

                      echo '<div class="col-4 form-group mb-3">';
                      echo '<label for="nombre">Nombre y apellidos:</label>';
                      echo '<input type="text" class="form-control" id="nombre" name="nombre_name" disabled required value="' . $orden["NombreApellidos"] . '">';
                      echo '</div>';

                      echo '<div class="col-2 form-group mb-3">';
                      echo '<label for="telefono">Teléfono:</label>';
                      echo '<input type="tel" class="form-control" id="telefono" name="telefono_name"  disabled required value="' . $orden["Telefono"] . '">';
                      echo '</div>';

                      echo '<div class="col-6 form-group mb-3">';
                      echo '<label for="modelo">Modelo:</label>';
                      echo '<input type="text" class="form-control" id="modelo" name="modelo_name" required value="' . $orden["Modelo"] . '">';
                      echo '</div>';

                      echo '<div class="col-4 form-group mb-3">';
                      echo '<label for="imei">IMEI / SN:</label>';
                      echo '<input type="text" class="form-control" id="imei_sn" name="imei_sn_name" required value="' . $orden["IMEISN"] . '">';
                      echo '</div>';

                      echo '<div class="col-2 form-group mb-3">';
                      echo '<label for="contrasena">Contraseña:</label>';
                      echo '<input type="text" class="form-control" id="contrasena" name="contrasena_name" required value="' . $orden["Contrasena"] . '">';
                      echo '</div>';

                      echo '<div class="form-group mb-3">';
                      echo '<label for="descripcion">Avería:</label>';
                      echo '<textarea class="form-control" id="descripcion" name="descripcion_name" required>' . $orden["Descripcion"] . '</textarea>';
                      echo '</div>';

                      echo '<div class="form-group mb-3">';
                      echo '<label for="descripcion_tecnico">Diagnóstico Técnico:</label>';
                      echo '<textarea class="form-control" id="diagnostico_tecnico" name="diagnostico_tecnico_name" required>' . $orden["DetallesDiagnostico"] . '</textarea>';
                      echo '</div>';

                      echo '<div class="col-1 form-group mb-3">';
                      echo '<label for="garantía">Garantía:</label>';
                      echo '<input type="number" class="form-control" id="garantia" name="garantia_name" value="' . $orden["MesGarantia"] . '">';
                      echo '</div>';

                      echo '<div class="col-3 form-group mb-5">';
                      echo '<label for="exampleFormControlSelect1">Estado Reparación:</label>';
                      echo '<select class="form-control" id="exampleFormControlSelect1" name="estado_name" onchange="toggleGarantiaRequired(this.value)">';

                      // Define las opciones y verifica cuál de ellas debe estar seleccionada
                      $opciones = array(
                          "ADMITIDO",
                          "EN REVISIÓN",
                          "EN ESPERA DE APROBACIÓN",
                          "EN ESPERA DE PIEZAS",
                          "EN REPARACIÓN",
                          "EN PRUEBAS",
                          "ESPERANDO RECOGIDA",
                          "ENTREGADO",
                          "CANCELADO / S.S.R",
                          "EN TALLER EXTERNO",
                          "EN GARANTÍA"
                      );

                      foreach ($opciones as $opcion) {
                          if ($orden["EstadoReparacion"] == $opcion) {
                              echo '<option selected>' . $opcion . '</option>';
                          } else {
                              echo '<option>' . $opcion . '</option>';
                          }
                      }

                      echo '</select>';
                      echo '</div>';

                      echo '<div class="col-2 form-group mb-3">';
                      echo '<label for="precio">Precio:</label>';
                      echo '<input type="number" class="form-control" id="precio" name="precio_name" required value="' . $orden["Precio"] . '">';
                      echo '</div>';

                      /*
                      echo '<div class="btn-group btn-group-toggle" data-toggle="buttons">';
                      echo '    <button id="enviar_form_bt" type="submit" class="btn btn-primary btn-lg">ACTUALIZAR</button>';
                      echo '    <button type="button" class="btn btn-info">IMPRIMIR</button>';
                      echo '    <button type="button" class="btn btn-secondary">IMPRIMIR (ETIQUETA)</button>'; 
                      echo '</div>';
                      */

                      // Botones para imprimir con márgenes entre ellos y centrados
                      echo '<div class="d-flex justify-content-center my-3">';

                          //Botón para actualizar los datos
                          echo '<button id="enviar_form_bt" type="submit" class="button-update btn btn-primary btn-block">ACTUALIZAR</button>';

                          // Botón para imprimir el ticket
                          echo '<a href="funciones.php?action=imprimirTicket&id_orden=' . $orden_id . '" class="btn btn-secondary mx-2">Imprimir Ticket</a>';

                          // Botón para imprimir el folio
                          echo '<a href="funciones.php?action=imprimirFolio&id_orden=' . $orden_id . '" class="btn btn-secondary mx-2">Imprimir Folio</a>';

                          // Botón para imprimir el adhesivo
                          echo '<a href="funciones.php?action=imprimirAdhesivo&id_orden=' . $orden_id . '" class="btn btn-secondary mx-2">Imprimir Etiqueta</a>';

                      echo '</div>';

                      echo '</div>';
                      echo '</form>';
                  } else {
                      echo '<div class="alert alert-danger">¡ERROR! Orden no encontrada.</div>';
                  }
              ?>
              
              </div>
            <div class="tab-pane fade p-3 mb-3" id="otro-formulario" role="tabpanel" aria-labelledby="otro-formulario-tab">
              
              <!-- FORMULARIO REPARACIÓN -->
              
              </div>
        </div>
      
        <div class="text-center mt-5">
           	<a href="index.php" class="btn btn-outline-dark btn-back">
            	<i class="fas fa-arrow-left"></i> Atrás
        	</a>
        </div>
    </div>
  
    <script>
      function toggleGarantiaRequired(selectedValue) {
          var garantiaInput = document.getElementById("garantia");

          // Verifica el valor seleccionado
          if (selectedValue === "ESPERANDO RECOGIDA" || selectedValue === "ENTREGADO") {
              garantiaInput.setAttribute("required", "required");
          } else {
              garantiaInput.removeAttribute("required");
          }
      }
    </script>

    <script src="../../js/jquery-3.5.1.slim.min.js"></script>
    <script src="../../js/bootstrap.min.js"></script>

</body>
</html>