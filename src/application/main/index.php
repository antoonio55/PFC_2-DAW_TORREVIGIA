<?php
include '../../config/config.php';
include 'funciones.php';

$ordenes = obtenerOrdenes();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mostrar datos</title>
    <link rel="stylesheet" href="../../css/bootstrap.min.css">
    <style>
        /* Agrega estilos personalizados aquí */
        .container-personalizado {
            margin-top: 10px; /* Margen superior */
            margin-bottom: 10px; /* Margen inferior */
            margin-left: 20px; /* Margen izquierdo */
            margin-right: 20px; /* Margen derecho */

            padding-top: 10px; /* Margen interior en la parte superior */
            padding-bottom: 10px; /* Margen interior en la parte inferior */
            padding-left: 40px; /* Margen interior en el lado izquierdo */
            padding-right: 40px; /* Margen interior en el lado derecho */
        }
      
      	/* Estilos para la tabla personalizada */
        .custom-table {
            width: 100%;
            margin-bottom: 1rem;
            color: #212529;
            border-collapse: collapse;
        }

        .custom-table th,
        .custom-table td {
            padding: 0.75rem;
            vertical-align: top;
            border-top: 1px solid #dee2e6;
        }

        .custom-table thead th {
            vertical-align: bottom;
            border-bottom: 2px solid #dee2e6;
        }

        .custom-table tbody + tbody {
            border-top: 2px solid #dee2e6;
        }

        .custom-table .table {
            background-color: #fff;
        }
      
      	.custom-table th, .table td {
            text-align: center; /* Centra el texto en las celdas de la tabla */
        }

        .custom-table th:nth-child(1),
        .custom-table td:nth-child(1) {
            width: 5%; /* Ancho de la primera columna (Orden #) */
        }

        .custom-table th:nth-child(2),
        .custom-table td:nth-child(2) {
            width: 10%; /* Ancho de la segunda columna (Fecha) */
        }

        .custom-table th:nth-child(3),
        .custom-table td:nth-child(3) {
            width: 15%; /* Ancho de la tercera columna (Nombre y Apellidos) */
        }

        .custom-table th:nth-child(4),
        .custom-table td:nth-child(4) {
            width: 10%; /* Ancho de la cuarta columna (Teléfono) */
        }

        .custom-table th:nth-child(5),
        .custom-table td:nth-child(5) {
            width: 10%; /* Ancho de la quinta columna (Modelo) */
        }

        .custom-table th:nth-child(6),
        .custom-table td:nth-child(6) {
            width: 10%; /* Ancho de la sexta columna (Contraseña) */
        }

        .custom-table th:nth-child(7),
        .custom-table td:nth-child(7) {
            width: 25%; /* Ancho de la séptima columna (Descripción) */
        }

        .custom-table th:nth-child(8),
        .custom-table td:nth-child(8) {
            width: 5%; /* Ancho de la octava columna (Precio) */
        }

        .custom-table th:nth-child(9),
        .custom-table td:nth-child(9) {
            width: 10%; /* Ancho de la novena columna (Estado Reparación) */
        }

       	/* Agrega los estilos específicos para los estados de reparación */
        .custom-table .estado-en-proceso {
          	background-color: #fff176; /* Amarillo */
            color: #212529;
        }

        .custom-table .estado-entregado {
            background-color: #98fb98; /* Verde suave */
            color: #212529;
        }

        .custom-table .estado-presupuestado {
            background-color: #afeeee; /* Azul suave */
            color: #212529;
        }

        .custom-table .estado-cancelado {
            background-color: #dc3545; /* Rojo */
            color: #212529;
        }

        .custom-table .estado-finalizado {
            background-color: #dcdcdc; /* Naranja suave */
            color: #212529;
        }

        .custom-table .estado-admitido {
            background-color: #ece2c6; /* Amarillo suave */
            color: #212529;
        }

        .custom-table .estado-en-revision {
            background-color: #add8e6; /* Azul claro */
            color: #212529;
        }

        .custom-table .estado-espera-aprobacion {
            background-color: #ffcc80; /* Naranja claro */
            color: #212529;
        }

        .custom-table .estado-espera-piezas {
            background-color: #ffcccb; /* Rosa claro */
            color: #212529;
        }

        .custom-table .estado-en-pruebas {
            background-color: #98fb98; /* Verde claro */
            color: #212529;
        }

        .custom-table .estado-esperando-recogida {
            background-color: #ffeb3b; /* Amarillo */
            color: #212529;
        }

        .custom-table .estado-en-garantia {
            background-color: #fff176; /* Amarillo */
            color: #212529;
        }

        .custom-table .estado-en-taller-externo {
            background-color: #e6e6fa; /* Lila claro */
            color: #212529;
        }
    </style>
</head>
<body>
    <br>
    <div class="container-personalizado">
        <h1>Visualizar Ordenes De Servicio</h1>
        <p>Las ordenes "ENTREGADAS" ó "CANCELADAS" que tengan más de 15 días no serán mostradas.</p>
          <table class="custom-table">
              <thead>
                  <tr>
                      <!--<th>ID</th>-->
                      <th>Orden #</th>
                      <th>Fecha</th>
                      <!--<th>Dni</th>-->
                      <th>Nombre y Apellidos</th>
                      <th>Teléfono</th>
                      <th>Modelo</th>
                      <!--<th>IMEI/SN</th>-->
                      <th>Contraseña</th>
                      <th width="500px">Avería</th>
                      <th>Precio</th>
                      <th>Estado Reparación</th>
                  </tr>
              </thead>
              <tbody>
                  <!-- Aquí se mostrarán los datos de la base de datos -->
                  <?php
                  if (!empty($ordenes)) {
                      // Bandera para controlar si ya se mostró la fila o no
                      $sinRevisarMostrado = false;
                      $reparandoMostrado = false;
                      $finalizadoMostrado = false;

                      // Imprimir los datos en la tabla
                      foreach ($ordenes as $orden) {
                          // Determina la clase CSS según el estado de reparación
                          $claseEstado = '';

                          $estadosClases = [
                              "ADMITIDO" => 'estado-admitido',
                              "EN REVISIÓN" => 'estado-en-revision',
                              "EN ESPERA DE APROBACIÓN" => 'estado-espera-aprobacion',
                              "EN ESPERA DE PIEZAS" => 'estado-espera-piezas',
                              "EN REPARACIÓN" => 'estado-en-proceso',
                              "EN PRUEBAS" => 'estado-en-pruebas',
                              "ESPERANDO RECOGIDA" => 'estado-esperando-recogida',
                              "ENTREGADO" => 'estado-entregado',
                              "CANCELADO / S.S.R" => 'estado-cancelado',
                              "EN TALLER EXTERNO" => 'estado-en-taller-externo',
                              "EN GARANTÍA" => 'estado-en-garantia'
                          ];

                          $estadoReparacion = $orden["EstadoReparacion"];

                          // Si el estado es "ADMITIDO" y la fila "SIN REVISAR" aún no se ha mostrado, mostrar el título "SIN REVISAR"
                          if (!$sinRevisarMostrado && $estadoReparacion === "ADMITIDO") {
                              echo '<tr style="background-color: #f2f2f2;"><td colspan="10" style="text-align:center;"><strong> ------------------------------  SIN REVISAR  ------------------------------ </strong></td></tr>';
                              $sinRevisarMostrado = true; // Cambiar el valor de la bandera para indicar que ya se mostró alguna fila especial
                          }

                          // Si la fila "REPARANDO" no se ha mostrado y el estado está dentro de estos, mostrar el título "REPARANDO"
                          if (!$reparandoMostrado && in_array($estadoReparacion, ["EN REVISION", "EN ESPERA DE APROBACIÓN", "EN ESPERA DE PIEZAS", "EN REPARACIÓN", "EN PRUEBAS", "EN TALLER EXTERNO", "EN GARANTÍA"])) {
                              echo '<tr style="background-color: #f2f2f2;"><td colspan="10" style="text-align:center;"><strong> -------------------------------  REPARANDO  ------------------------------- </strong></td></tr>';
                              $reparandoMostrado = true; // Cambiar el valor de la bandera para indicar que ya se mostró alguna fila especial
                          }

                          // Si la fila "FINALIZADO" no se ha mostrado y el estado está dentro de estos, mostrar el título "FINALIZADO"
                          if (!$finalizadoMostrado && in_array($estadoReparacion, ["ESPERANDO RECOGIDA", "ENTREGADO", "CANCELADO / S.S.R"])) {
                              echo '<tr style="background-color: #f2f2f2;"><td colspan="10" style="text-align:center;"><strong> -------------------------------  FINALIZADO  ------------------------------- </strong></td></tr>';
                              $finalizadoMostrado = true; // Cambiar el valor de la bandera para indicar que ya se mostró alguna fila especial
                          }

                          // Imprimir la fila correspondiente a la orden
                          echo '<tr class="' . $estadosClases[$orden["EstadoReparacion"]] . '">';
                          echo "<td style=\"text-align:center;\">" . $orden["Numero"] . "</td>";
                          echo "<td style=\"text-align:center;\">" . $orden["Fecha"] . "</td>";
                          echo "<td style=\"text-align:center;\">" . $orden["NombreApellidos"] . "</td>";
                          echo "<td style=\"text-align:center;\">" . $orden["Telefono"] . "</td>";
                          echo "<td style=\"text-align:center;\">" . $orden["Modelo"] . "</td>";
                          echo "<td style=\"text-align:center;\">" . $orden["Contrasena"] . "</td>";
                          echo "<td>" . $orden["Descripcion"] . "</td>";
                          echo "<td style=\"text-align:center;\">" . $orden["Precio"] . "€</td>";
                          echo "<td style=\"text-align:center;\">" . $orden["EstadoReparacion"] . "</td>";
                          echo "</tr>";
                      }
                  } else {
                      echo "No se encontraron resultados.";
                  }
                  ?>
              </tbody>
          </table>