<?php
include '../../config/database.php';

// Determinamos que función ejecutar.
if (isset($_GET['action'])) {
    $action = $_GET['action'];
    $cliente_id = $_GET['id_cliente'];
    
    // Lógica para enrutamiento de acciones
    if ($action === 'imprimirPD') {
        imprimirPD($cliente_id);
    }elseif ($action === 'subirPD') {
        subirPD($cliente_id);
    }
    elseif ($action === 'eliminarPD') {
        eliminarRutaPD($cliente_id);
    }
}

if (isset($_POST['action']) && !empty($_POST['action'])) {
    $action = $_POST['action'];
    switch ($action) {
        case 'verificarDNI':
            verificarDNI($_POST['dni']);
            break;
        // Puedes agregar más casos para otras funciones si es necesario
    }
}


// ---------------- FUNCIONES ---------------------------

function ObtenerDatosEmpresa() {
    global $conn;

    // Realiza la consulta SQL para obtener los datos de la empresa
    $sql = "SELECT * FROM datosEmpresa";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Si se encontraron resultados, obtén los datos de la empresa
        $datosEmpresa = $result->fetch_assoc();
        return $datosEmpresa;
    } else {
        // Si no se encontraron resultados, puedes retornar un arreglo vacío o null, según tu preferencia
        return array();
    }
}

function obtenerClientes() {
    global $conn;
    $sql = "SELECT * FROM clientes ORDER BY id_cliente DESC;";
    $result = $conn->query($sql);
    $clientes = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $clientes[] = $row;
        }
    }
    return $clientes;
}

function obtenerClientesFiltrados($id, $nombre, $dni, $telefono, $lista_negra) {

    global $conn;
    // Inicializa la consulta SQL
    $sql = "SELECT * FROM clientes WHERE 1 = 1"; // Siempre verdadero para facilitar la construcción de la consulta

    if (!empty($id)) {
        $sql .= " AND id_cliente = '$id'";
    }

    if (!empty($nombre)) {
        $sql .= " AND NombreApellidos LIKE '%$nombre%'";
    }

    if (!empty($dni)) {
        $sql .= " AND Dni = '$dni'";
    }

    if (!empty($telefono)) {
        $sql .= " AND Telefono = '$telefono'";
    }

    if ($lista_negra === 1) {
        $sql .= " AND ListaNegra = 1";
    }

    $sql .= " ORDER BY id_cliente DESC;";

    // Realiza la consulta
    $result = $conn->query($sql);

    $clientes = array();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $clientes[] = $row;
        }
    }

    // Cierra la conexión a la base de datos
    $conn->close();

    return $clientes;
}


function agregarCliente($nombre, $dni, $telefono, $telefonoAdicional, $email, $anotaciones) {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO clientes (NombreApellidos, Dni, Telefono, TelefonoAdicional, Email, Anotaciones) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $nombre, $dni, $telefono, $telefonoAdicional, $email, $anotaciones);
    return $stmt->execute();
}

function obtenerClientePorID($id) {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM clientes WHERE id_cliente = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

function actualizarCliente($id, $nombre, $dni, $telefono, $telefonoAdicional, $email, $lista_negra, $anotaciones, $rutaFirma) {
    global $conn;
    // Comprobar si $telefonoAdicional es un número entero o nulo
    
    $stmt = $conn->prepare("UPDATE clientes SET NombreApellidos = ?, Dni = ?, Telefono = ?, TelefonoAdicional = ?, Email = ?, ListaNegra = ?, Anotaciones = ?, ruta_firma = ? WHERE id_cliente = ?");
    $stmt->bind_param("ssssssssi", $nombre, $dni, $telefono, $telefonoAdicional, $email, $lista_negra, $anotaciones, $rutaFirma, $id);
    return $stmt->execute();
    
}

function eliminarCliente($id) {
    global $conn;
    $stmt = $conn->prepare("DELETE FROM clientes WHERE id_cliente = ?");
    $stmt->bind_param("i", $id);
    return $stmt->execute();
}

function imprimirPD($cliente_id) {

    global $conn;

    // Incluye la biblioteca TCPDF
    require '../../vendor/tcpdf/tcpdf.php';

    $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

    $pdf->SetMargins(10, 10, 10);
    $pdf->AddPage();

    $pdf->SetFont('Arial', '', 12);

    $cliente = ObtenerClientePorID($cliente_id);
    $empresa = ObtenerDatosEmpresa();


    $html = '
        
            <br><h1 style="text-align: center;"><u>Consentimiento para el Tratamiento de Datos Personales</u></h1>

            <p></p>

            <br><p style="text-align: justify;">Por favor, lea atentamente la siguiente declaración y, si está de acuerdo, proporcione su consentimiento para el tratamiento de sus datos personales:</p>

            <p style="text-align: justify;">Yo, ' . $cliente['NombreApellidos'] . ', con DNI/NIE/Pasaporte ' . $cliente['Dni'] . ', en calidad de CLIENTE, doy mi consentimiento expreso para que ' . $empresa['nombreEmpresa'] . ' con CIF ' . $empresa['cif'] . ' y sus filiales/socios autorizados traten mis datos personales con los siguientes fines:</p>

            <ol>
                <li>Recopilación y almacenamiento de datos personales para prestación de servicios, comunicación de novedades y facturación.</li>
                <li>Procesamiento y análisis de datos personales con fines estadísticos y de mejora de servicios.</li>
                <li>Comunicación y envío de información promocional, publicitaria y novedades de productos o servicios ofrecidos por ' . $empresa['nombreEmpresa'] . '.</li>
            </ol>

            <p style="text-align: justify;">Entiendo que mis datos personales serán tratados de acuerdo con la legislación de protección de datos aplicable y la política de privacidad de ' . $empresa['nombreEmpresa'] . '. Acepto que mis datos personales podrán ser compartidos con terceros únicamente para los fines mencionados anteriormente.</p>

            <p style="text-align: justify;">Comprendo que tengo derecho a revocar mi consentimiento en cualquier momento y a solicitar la eliminación de mis datos personales. Para ejercer mis derechos o para obtener más información sobre cómo se tratan mis datos personales, puedo contactar con ' . $empresa['nombreEmpresa'] . ' a través de [Información de Contacto].</p>

            <p style="text-align: justify;">La presente declaración de consentimiento es válida desde ' . date('d-m-Y') . ' y permanecerá en efecto hasta que sea revocada por escrito.</p>

            <p style="text-align: justify;">Al proporcionar mi consentimiento, confirmo que he leído, comprendido y aceptado las condiciones de tratamiento de mis datos personales según se ha establecido anteriormente.</p>
            <br>
            <p></p>
            <br>
            <p>_____________________________</p>
            <p>' . $cliente['NombreApellidos'] . ' - ' . date('d/m/Y') . '</p>
        ';

        // Imprimir el código de barras
        $pdf->writeHTMLCell(0, 0, '', '', $pdf->writeHTML($html, true, false, true, false, ''), 0, 1, 0, true);

        $pdf->Output('firma.pdf', 'I');

    }

    function obtenerRutaPD($cliente_id) {
        global $conn;
    
        // Consulta SQL para obtener la ruta del archivo PDF del cliente
        $sql = "SELECT ruta_firma FROM clientes WHERE id_cliente = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $cliente_id);
        $stmt->execute();
        $result = $stmt->get_result();
    
        if ($result->num_rows > 0) {
            // Si se encuentra una ruta de archivo PDF en la base de datos, retornarla
            $row = $result->fetch_assoc();
            return $row['ruta_firma'];
        } else {
            // Si no se encuentra una ruta de archivo PDF, retornar una cadena vacía o null
            return '';
        }
    }

    function eliminarRutaPD($cliente_id) {
        global $conn;
    
        // Verifica si la ruta PDF existe en la base de datos
        $sql = "SELECT ruta_firma FROM clientes WHERE id_cliente = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $cliente_id);
        $stmt->execute();
        $stmt->bind_result($rutaPDFCliente);
    
        if ($stmt->fetch()) {
            // Si se encontró una ruta PDF, elimínala
            if (file_exists($rutaPDFCliente) && unlink($rutaPDFCliente)) {
                $stmt->close();
                $sql = "UPDATE clientes SET ruta_firma = NULL WHERE id_cliente = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $cliente_id);
    
                if ($stmt->execute()) {
                    $stmt->close();
                    // Exito al realizar la consulta. 
                    // Redirige a la página anterior
                    header("Location: " . $_SERVER['HTTP_REFERER']);
                    exit; // Asegura que no haya más salida después de la redirección
                } else {
                    return "Error en la actualización de la base de datos";
                }
            } else {
                return "Error al eliminar el archivo PDF";
            }
        } else {
            return "No se encontró una ruta PDF en la base de datos";
        }
    }

    function subirPD($cliente_id) {
        global $conn;
    
        if ($_FILES['archivo']['error'] == 0) {
            $nombreArchivoOriginal = $_FILES['archivo']['name'];
            $extension = pathinfo($nombreArchivoOriginal, PATHINFO_EXTENSION);

            // Obtén el DNI del cliente
            $cliente = obtenerClientePorID($cliente_id);
            $dniCliente = $cliente['Dni'];

            // Genera el nuevo nombre del archivo
            $nombreArchivoNuevo = $dniCliente . '.' . $extension;
    
            $rutaTemporal = $_FILES['archivo']['tmp_name'];
            $carpetaDestino = 'firmas/'; // Ruta donde se almacenarán los archivos PDF
    
            if (move_uploaded_file($rutaTemporal, $carpetaDestino . $nombreArchivoNuevo)) {
                $rutaArchivo = $carpetaDestino . $nombreArchivoNuevo;
    
                // Actualizar la base de datos con la ruta del archivo PDF
                $sql = "UPDATE clientes SET ruta_firma = ? WHERE id_cliente = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("si", $rutaArchivo, $cliente_id);
    
                if ($stmt->execute()) {
                    // Redirige a la página anterior
                    header("Location: " . $_SERVER['HTTP_REFERER']);
                    exit; // Asegura que no haya más salida después de la redirección
                } else {
                    echo "Error en la actualización de la base de datos.";
                }
            } else {
                echo "Error al mover el archivo PDF.";
            }
        } else {
            echo "No se envió ningún archivo.";
        }
    }

    function verificarDNI($dni) {
        global $conn;
        // Preparar la consulta SQL con un parámetro para el DNI
        $stmt = $conn->prepare("SELECT Dni FROM clientes WHERE Dni = ?");
        $stmt->bind_param("s", $dni); // "s" indica que el parámetro es una cadena (string)

        // Ejecutar la consulta
        $stmt->execute();

        // Vincular el resultado
        $stmt->bind_result($dniEncontrado);

        // Obtener el resultado
        $stmt->fetch();

        // Cerrar la consulta y la conexión
        $stmt->close();
        $conn->close();

        // Verificar si el DNI fue encontrado en la base de datos
        if ($dniEncontrado) {
            echo "existe";
        } else {
            echo "no_existe";
        }
    }

?>
