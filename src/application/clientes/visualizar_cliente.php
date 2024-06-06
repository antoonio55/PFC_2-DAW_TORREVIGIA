<?php
include '../../config/config.php';
include 'funciones.php';

session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: ../../../index.php ");
    exit;
}

// El usuario está autenticado, puedes mostrar el contenido de la página protegida aquí.
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visualizar Cliente</title>
    <link rel="stylesheet" href="../../css/bootstrap.min.css">
    <style>
        .align-items-center {
            display: flex;
            align-items: center;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1>Detalles del Cliente</h1>
        
        <?php
        // Obtiene los detalles de la cliente
        $cliente_id = $_GET['id_cliente']; // Pasamos el ID de la cliente por la URL
        $cliente = obtenerClientePorID($cliente_id);

        if ($cliente) {
            echo '<table class="table">';
            echo '<tr><th>ID:</th><td>' . $cliente['id_cliente'] . '</td></tr>';
            echo '<tr><th>Nombre:</th><td>' . $cliente['NombreApellidos'] . '</td></tr>';
            echo '<tr><th>DNI:</th><td>' . $cliente['Dni'] . '</td></tr>';
            echo '<tr><th>Teléfono:</th><td>' . $cliente['Telefono'] . '</td></tr>';
            echo '<tr><th>Teléfono Adicional:</th><td>' . $cliente['TelefonoAdicional'] . '</td></tr>';
            echo '<tr><th>E-mail:</th><td>' . $cliente['Email'] . '</td></tr>';
            echo '<tr><th>Lista Negra:</th><td>' . ($cliente['ListaNegra'] ? 'SI' : 'NO') . '</td></tr>';
            echo '<tr><th>Anotaciones:</th><td>' . $cliente['Anotaciones'] . '</td></tr>';
            echo '</table>';
            // Botones para imprimir con márgenes entre ellos y centrados
            echo '<div class="d-flex justify-content-center align-items-center my-3">';
            echo '    <!-- Botón para imprimir la protección de datos -->';
            echo '<a href="funciones.php?action=imprimirPD&id_cliente=' . $cliente['id_cliente'] . '" class="btn btn-info mx-2">Imprimir Protección de Datos</a>';

            $rutaPDFCliente = obtenerRutaPD($cliente_id);

            if (!empty($rutaPDFCliente)) {
                // Mostrar botones "Eliminar PDF" y "Visualizar PDF"
                echo '    <label class="btn btn-secondary btn-file">';
                echo '      <a href="funciones.php?action=eliminarPD&id_cliente=' . $cliente_id . '" class="btn btn-danger mx-2" onclick="return confirm(\'¿Estás seguro de que deseas eliminar el archivo?\')">Eliminar PD</a>';
                echo '      <a href="' . $rutaPDFCliente . '" class="btn btn-success mx-2" target="_blank">Visualizar PD</a>';
                echo '    </label>';
                echo '    </div>';

            } else {
                // Mostrar formulario para subir archivos PDF
                echo '    <!-- Formulario para subir archivos PDF -->';
                echo '    <form action="funciones.php?action=subirPD&id_cliente=' . $cliente_id . '" method="POST" enctype="multipart/form-data" class="mx-2">';
                echo '        <label class="btn btn-secondary btn-file">';
                echo '          <input type="file" name="archivo" accept=".pdf" required>';
                echo '          <input type="hidden" name="cliente_id" value="' . $cliente_id . '">';
                echo '          <button type="submit" class="btn btn-primary">Subir PDF</button>';
                echo '        </label>';
                echo '    </form>';
                echo '    </div>';
            }
        } else {
            echo '<div class="alert alert-danger">cliente no encontrado.</div>';
        }
        ?>
        
        <div class="text-center mt-5">
            <a href="index.php" class="btn btn-primary">Volver a la lista de clientes</a>
        </div>
    </div>

    <script src="../../js/jquery-3.5.1.min.js"></script>
    <script src="../../js/bootstrap.min.js"></script>
</body>
</html>