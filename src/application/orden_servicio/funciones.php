<?php

header('Content-Type: text/html; charset=UTF-8');
include '../../config/database.php';



//Determinamos que función ejecutar.
if (isset($_POST['action'])) {
    $action = $_POST['action'];
    if ($action === 'buscarCliente') {
        // Ejecutar la función obtenerCliente.
        $dni = $_POST['dni'];
        obtenerCliente($dni);
    }

    $action = $_POST['action'];
    if ($action === 'insertarCliente') {
        // Ejecutar la función obtenerCliente.
        $dni = $_POST["dni"];
        $nombre = $_POST["nombre"];
        $telefono = $_POST["telefono"];
        insertarCliente($dni, $nombre, $telefono);
    }
    // Agregar más casos para otras acciones si es necesario.
}

// Determinamos que función ejecutar.
if (isset($_GET['action'])) {
    $action = $_GET['action'];
    $orden_id = $_GET['id_orden'];
    
    // Lógica para enrutamiento de acciones
    if ($action === 'imprimirTicket') {
        imprimirTicket($orden_id);
    } elseif ($action === 'imprimirFolio') {
        imprimirFolio($orden_id);
    } elseif ($action === 'imprimirAdhesivo') {
        imprimirAdhesivo($orden_id);
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


function obtenerOrdenes() {
    global $conn;

    $sql = "SELECT * FROM orden_servicio os
            LEFT JOIN clientes c ON os.cliente_id = c.id_cliente
            ORDER BY os.Fecha DESC, os.Numero DESC;";

    $result = $conn->query($sql);
    $ordenes = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ordenes[] = $row;
        }
    }
    return $ordenes;
}

function obtenerOrdenesFiltradas($numero, $fecha, $dni, $nombre, $telefono, $imei) {
    global $conn;
    // Inicializa la consulta SQL
    $sql = "SELECT * FROM orden_servicio os 
            LEFT JOIN clientes c ON os.cliente_id = c.id_cliente 
            WHERE 1 = 1"; // Siempre verdadero para facilitar la construcción de la consulta

    if (!empty($numero)) {
        $sql .= " AND Numero LIKE '%$numero%'";
        //var_dump($sql);
    }

    if (!empty($fecha)) {
        $sql .= " AND Fecha LIKE '$fecha%'";
    }

    if (!empty($nombre)) {
        $sql .= " AND NombreApellidos LIKE '%$nombre%'";
    }

    if (!empty($dni)) {
        $sql .= " AND Dni LIKE '$dni%'";
    }

    if (!empty($telefono)) {
        $sql .= " AND Telefono LIKE '$telefono%'";
    }

    if (!empty($imei)) {
        $sql .= " AND IMEISN LIKE '$imei%'";
    }

    $sql .= " ORDER BY os.Fecha DESC, os.Numero DESC;";

    // Realiza la consulta
    $result = $conn->query($sql);

    $ordenes = array();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ordenes[] = $row;
        }
    }

    // Cierra la conexión a la base de datos
    $conn->close();

    return $ordenes;
}

// Función para generar un código aleatorio único de longitud $longitud y verificar su existencia en la base de datos
function generarCodigoUnico($longitud) {
    global $conn;
    $caracteres = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';

    // Genera el primer carácter como un número
    $codigo = $caracteres[rand(0, 9)]; // 0-9 son los números

    // Genera el resto de los caracteres
    for ($i = 1; $i < $longitud; $i++) {
        $codigo .= $caracteres[rand(0, strlen($caracteres) - 1)];
    }

    // Si el código ya existe en la base de datos, vuelve a generarlo
    do {
        $stmt = $conn->prepare("SELECT COUNT(*) AS count FROM orden_servicio WHERE Numero = ?");
        $stmt->bind_param("s", $codigo);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();
    } while ($count > 0);

    return $codigo;
}

function agregarOrden($numero, $fecha, $fechaModificacion, $modelo, $imei_sn, $contrasena, $descripcion, $precio, $estado, $id_cliente) {
    global $conn;
    
    $stmt = $conn->prepare("INSERT INTO orden_servicio (Numero, Fecha, FechaModificacion, Modelo, IMEISN, Contrasena, Descripcion, Precio, EstadoReparacion, cliente_id)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    // Vincular parámetros
    $stmt->bind_param("ssssssssss", $numero, $fecha, $fechaModificacion, $modelo, $imei_sn, $contrasena, $descripcion, $precio, $estado, $id_cliente);

    // Ejecutar la consulta
    $resultado = $stmt->execute();

    // Comprobar si la consulta se ejecutó con éxito
    if ($resultado) {
        return true;
    } else {
        return false;
    }
}


function obtenerOrdenPorID($id) {
    global $conn;
    $stmt = $conn->prepare("SELECT os.*, c.Dni, c.NombreApellidos, c.Telefono FROM orden_servicio os
                            LEFT JOIN clientes c ON os.cliente_id = c.id_cliente
                             WHERE os.id_orden= ?;");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}


function actualizarOrden($id, $modelo, $imei_sn, $contrasena, $descripcion, $diagnostico_tecnico, $garantia, $estado_reparacion, $precio, $fecha_modificacion) {
    global $conn;

    // Obtiene el estado actual de la orden antes de la actualización
    $estado_anterior = obtenerEstadoActual($id);

    // Verifica si el nuevo estado es diferente al estado anterior
    if ($estado_anterior != $estado_reparacion) {
        // Si son diferentes, procede con la actualización y registra el cambio en el historial
        $stmt = $conn->prepare("UPDATE orden_servicio SET Modelo = ?, IMEISN = ?, Contrasena = ?, Descripcion = ?, DetallesDiagnostico = ?, MesGarantia = ?, EstadoReparacion = ?, Precio = ?, FechaModificacion = ? WHERE id_orden = ?");
        $stmt->bind_param("sssssssssi", $modelo, $imei_sn, $contrasena, $descripcion, $diagnostico_tecnico, $garantia, $estado_reparacion, $precio, $fecha_modificacion, $id);

        if ($stmt->execute()) {
            // Inserta un registro en el historial solo si el estado ha cambiado
            insertarHistorialOrden($id, $estado_anterior, $estado_reparacion);
            return true;
        }
    } else {
        // Si el nuevo estado es igual al estado anterior, solo realiza la actualización sin registrar en el historial
        $stmt = $conn->prepare("UPDATE orden_servicio SET Modelo = ?, IMEISN = ?, Contrasena = ?, Descripcion = ?, DetallesDiagnostico = ?, MesGarantia = ?, EstadoReparacion = ?, Precio = ?, FechaModificacion = ? WHERE id_orden = ?");
        $stmt->bind_param("sssssssssi", $modelo, $imei_sn, $contrasena, $descripcion, $diagnostico_tecnico, $garantia, $estado_reparacion, $precio, $fecha_modificacion, $id);

        return $stmt->execute();
    }
}


// Función para registrar un cambio en el historial
function insertarHistorialOrden($id_orden, $estado_anterior, $estado_nuevo) {
    global $conn;

    $stmt = $conn->prepare("INSERT INTO historial_ordenes (id_orden, estado_anterior, estado_nuevo) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $id_orden, $estado_anterior, $estado_nuevo);
    $stmt->execute();
}

function obtenerEstadoActual($id_orden) {
    global $conn;

    $stmt = $conn->prepare("SELECT EstadoReparacion FROM orden_servicio WHERE id_orden = ?");
    $stmt->bind_param("i", $id_orden);
    $stmt->execute();
    $stmt->bind_result($estado);
    $stmt->fetch();
    $stmt->close();

    return $estado;
}

function obtenerHistorialOrden($id_orden) {
    global $conn;

    // Prepara y ejecuta la consulta SQL
    $stmt = $conn->prepare("SELECT * FROM historial_ordenes WHERE id_orden = ? ORDER BY fecha_cambio ASC");
    $stmt->bind_param("i", $id_orden);
    $stmt->execute();

    // Obtiene los resultados
    $result = $stmt->get_result();

    // Verifica si hay filas en el resultado
    if ($result->num_rows > 0) {
        // Almacena los resultados en un array
        $historial = array();

        while ($row = $result->fetch_assoc()) {
            $historial[] = $row;
        }

        // Retorna el array con el historial de cambios
        return $historial;
    } else {
        // Si no hay resultados, retorna un array vacío
        return array();
    }
}

function eliminarOrden($id) {
    global $conn;
    $stmt = $conn->prepare("DELETE FROM orden_servicio WHERE id_orden = ?");
    $stmt->bind_param("i", $id);
    return $stmt->execute();
}

function obtenerCliente($dni) {
    global $conn;
    $sql = "SELECT id_cliente, NombreApellidos, Telefono FROM clientes WHERE Dni = ?";
    
    // Preparar la consulta
    $stmt = $conn->prepare($sql);
    
    if ($stmt) {
        // Vincular el valor del DNI a la consulta
        $stmt->bind_param("s", $dni);
        
        // Ejecutar la consulta
        $stmt->execute();
        
        // Obtener resultados
        $result = $stmt->get_result();
        
        $cliente = array();
        
        // Obtener los datos
        while ($row = $result->fetch_assoc()) {
            $cliente[] = $row;
        }
        
        // Cerrar la declaración
        $stmt->close();
        
        // Devolver la respuesta en formato JSON
        echo json_encode($cliente);
    } else {
        // Manejar error en la preparación de la consulta
        echo json_encode(array("error" => "No se pudo buscar el cliente"));
    }
}

function insertarCliente($dni, $nombre, $telefono) {

    global $conn;

    // Usa una declaración preparada para verificar si el cliente existe
    $sql = "SELECT id_cliente FROM clientes WHERE Dni = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $dni);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // El cliente ya existe, obtén su ID
        $row = $result->fetch_assoc();
        $cliente_id = $row['id_cliente'];
        $response = array('success' => true, 'cliente_id' => $cliente_id);
        echo json_encode($response);
    } else {
        // El cliente no existe, intenta agregarlo
        if (!empty($nombre) && !empty($telefono)) {
            // Usa una declaración preparada para insertar al nuevo cliente
            $nombre = mysqli_real_escape_string($conn, $nombre);
            $telefono = mysqli_real_escape_string($conn, $telefono);
            $sql_insert = "INSERT INTO clientes (NombreApellidos, Telefono, Dni) VALUES (?, ?, ?)";
            $stmt_insert = $conn->prepare($sql_insert);
            $stmt_insert->bind_param("sss", $nombre, $telefono, $dni);

            if ($stmt_insert->execute()) {
                $cliente_id = $stmt_insert->insert_id; // Obtiene el ID del nuevo cliente insertado
                $response = array('success' => true, 'cliente_id' => $cliente_id);
                echo json_encode($response);
            } else {
                $response = array('success' => false, 'error' => $stmt_insert->error);
                echo json_encode($response);
            }
            // Cierra la declaración preparada para la operación de inserción
            $stmt_insert->close();
        } else {
            $response = array('success' => false, 'error' => 'Por favor, complete los campos de nombre y teléfono.');
            echo json_encode($response);
        }
    }
    // Cierra la declaración preparada para la consulta inicial
    $stmt->close();
}

// Función para imprimir el ticket
function imprimirTicket($orden_id) {

    global $conn;

    // Incluye la biblioteca TCPDF
    require '../../vendor/tcpdf/tcpdf.php';

    // Estilos barcode
    $style_barcode = array(
        'position' => 'center',
        'align' => 'C',
        'stretch' => true,
        'fitwidth' => true,
        'cellfitalign' => true,
        'border' => true,
        'hpadding' => 'auto',
        'vpadding' => 'auto',
        'fgcolor' => array(0,0,0),
        'bgcolor' => false, //array(255,255,255),
        'text' => false,
        'font' => 'helvetica',
        'fontsize' => 12,
        'stretchtext' => 4
        );

    // Crear una instancia de TCPDF
    $pdf = new TCPDF('P', 'mm', 'A7', true, '', false); 
    $pdf->SetMargins(3, 6, 3, true);
    // Agrega una página
    $pdf->AddPage();

    // Configura la fuente
    $pdf->SetFont('Arial', '', 8);

    // Obtén los datos de la orden
    $orden = ObtenerOrdenPorID($orden_id);
    $empresa = ObtenerDatosEmpresa();

    if ($orden) {

        // Contenido del ticket
        $header = "
        <div>
            <br>
            {$empresa['nombreEmpresa']}<br>
            {$empresa['direccion']}<br>
            {$empresa['cif']}<br>
            {$empresa['telefono']}<br>
            {$empresa['email']}<br>
            {$empresa['eslogan']}
        </div>
        ";
        // Contenido del ticket
        $content = "
            <table cellpadding=\"5\" style=\"width: 100%;\">
            <br><br>
            <tr>
                <td colspan=\"2\" style=\"text-align: center; font-weight: bold;\"><h2> Nº {$orden['Numero']}</h2></td>
            </tr>
            <hr>
            <br>
            <tr>
                <td width=\"37%\">Fecha Entrega:</td>
                <td width=\"63%\">{$orden['Fecha']}</td>
            </tr>
            <tr>
                <td>Fecha Recogida:</td>
                <td>{$orden['FechaModificacion']}</td>
            </tr>
            <tr>
                <td>DNI:</td>
                <td>{$orden['Dni']}</td>
            </tr>
            <tr>
                <td>Nombre:</td>
                <td>{$orden['NombreApellidos']}</td>
            </tr>
            <tr>
                <td>Teléfono:</td>
                <td>{$orden['Telefono']}</td>
            </tr>
            <tr>
                <td>Modelo:</td>
                <td>{$orden['Modelo']}</td>
            </tr>
            <tr>
                <td>IMEI / SN:</td>
                <td>{$orden['IMEISN']}</td>
            </tr>
            <tr>
                <td>PIN:</td>
                <td>{$orden['Contrasena']}</td>
            </tr>
            <tr>
                <td>Defecto:</td>
                <td>{$orden['Descripcion']}</td>
            </tr>
            <tr>
                <td>Diagnóstico:</td>
                <td>{$orden['DetallesDiagnostico']}</td>
            </tr>

            <tr>
                <td>Estado:</td>
                <td>{$orden['EstadoReparacion']}</td>
            </tr>
            
            <hr>
            <tr>
                <td><h3>Garantía:</h3></td>
                <td><h3>{$orden['MesGarantia']} MES</h3></td>
            </tr>
            
            <hr>
            <div style=\"height: 10px; display: block;\"><\div>
            <tr>
                <td style=\"text-align: right;\"><h2>Precio:</h2></td>
                <td style=\"font-weight: bold; text-align: center;\"><h2>{$orden['Precio']} €</h2></td>
            </tr>
            
        </table>
        <br>
        ";


        // Footer
        $footer = "
        {$empresa['condicionesReparacionTicket']}
        ";

        //CALCULAR LARGO PAPEL
        $height_header = $pdf->GetStringHeight('0',$header) ;
        $height_principal = $pdf->GetStringHeight('0',$content) ;
        $height_footer = $pdf->GetStringHeight('0',$footer) ;
        $heightTotal= $height_header + $height_principal + $height_footer;

        //LO ASIGNAMOS A LA MEDIDA
        $medidas = array(80, $heightTotal);
        $pdf2 = new TCPDF('P', 'mm', $medidas, true, 'UTF-8', false);
        $pdf2->SetMargins(0, 6, 0, true); //SIN MARGEN
        $pdf2->SetAutoPageBreak(false, 0);
        // Agrega una página
        $pdf2->AddPage();

        // Configura la fuente
        $pdf2->SetFont('Arial', '', 12);

        // Escribir el contenido del header
        $pdf2->writeHTMLCell(0, 0, '', '', $header, 0, 1,'', '','C');

        // Escribir la línea horizontal
        $pdf2->writeHTML('<hr>', false, false, false, false, '');

        

        // Escribir el contenido principal
        $pdf2->writeHTMLCell(0, 0, '', '', $content, 0, 1);

        // Escribir la línea horizontal
        $pdf2->writeHTML('<hr>', false, false, false, false, '');

        // Escribir el footer
        $pdf2->writeHTML($footer, true, false, true, false, '');
        //$pdf2->MultiCell(0, 5, $footer, 0, 'J');

        // CODE 93 BARCODE
        $pdf2->write1DBarcode($orden['Numero'], 'C93', '5', '', 200, 18, 0.7, $style_barcode, 'M');

        $pdf->Ln();
    } else {
        // Manejo de error si no se encuentra la orden
        $pdf2->Cell(0, 10, 'Orden no encontrada', 0, 1, 'L');
    }

    // Salida del PDF (se descargará al navegador)
    $pdf2->Output('ticket.pdf', 'I');

    // ¡Importante! Termina la ejecución del script después de generar el PDF
    exit();
  	
}


// Función para imprimir el folio
function imprimirFolio($orden_id) {

    global $conn;

    // Incluye la biblioteca TCPDF
    require '../../vendor/tcpdf/tcpdf.php';

    $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

    $pdf->SetMargins(10, 10, 10);
    $pdf->AddPage();

    $pdf->SetFont('Arial', '', 12);

    $orden = ObtenerOrdenPorID($orden_id);
    $empresa = ObtenerDatosEmpresa();


    $html = '
    <table cellpadding= "10" style="width: 100%;">
            <tr>
                <td colspan="15" style="padding: 10px; border: 1px solid #000;">
                    <ul style="list-style-type: none; margin: 5; padding: 0;">
                    <li>' . $empresa['nombreEmpresa'] . '</li>
                    <li>CIF: ' . $empresa['cif'] . '</li>
                    <li>Dirección: ' . $empresa['direccion'] . '</li>
                    <li>Teléfono: ' . $empresa['telefono'] . '</li>
                    </ul>
                </td>
                <td colspan="5"style="padding: 10px; border: 1px solid #000;"></td>
                
            </tr>
            <tr>
                <td colspan="5" style="padding: 10px; border: 1px solid #000;">FECHA ENTREGA:</td>
                <td colspan="5" style="padding: 10px; border: 1px solid #000;">' . $orden['Fecha'] . '</td>
                <td colspan="2" style="padding: 10px; border: 1px solid #000;">TLF:</td>
                <td colspan="8" style="padding: 10px; border: 1px solid #000;">' . $orden['Telefono'] . '</td>
            </tr>
            <tr>
                <td colspan="3" style="padding: 10px; border: 1px solid #000;">NOMBRE:</td>
                <td colspan="17" style="padding: 10px; border: 1px solid #000;">' . $orden['NombreApellidos'] . '</td>
            </tr>
            <tr>
                <td colspan="2" style="padding: 10px; border: 1px solid #000;">DNI:</td>
                <td colspan="4" style="padding: 10px; border: 1px solid #000;">' . $orden['Dni'] . '</td>
                <td colspan="2" style="padding: 10px; border: 1px solid #000;">IMEI:</td>
                <td colspan="14" style="padding: 10px; border: 1px solid #000;">' . $orden['IMEISN'] . '</td>
            </tr>
            <tr>
                <td colspan="3" style="padding: 10px; border: 1px solid #000;">MODELO:</td>
                <td colspan="9" style="padding: 10px; border: 1px solid #000;">' . $orden['Modelo'] . '</td>
                <td colspan="2" style="padding: 10px; border: 1px solid #000;">PIN:</td>
                <td colspan="6" style="padding: 10px; border: 1px solid #000;">' . $orden['Contrasena'] . '</td>
            </tr>

            <tr>
                <td colspan="20" style="padding: 10px; border: 1px solid #000;">
                    DEFECTO: 
                    <ul style="list-style-type: none; margin:0; padding:0;">
                        <li style="margin:0; padding:0;">' . $orden['Descripcion'] . '</li>
                    </ul>
                </td>
            </tr>
            
            <tr>
                <td colspan="17" style="padding: 10px; border: 1px solid #000;">
                    TRABAJO REALIZADO:
                    <ul style="list-style-type: none; margin:0; padding:0;">
                        <li style="margin:0; padding:0;">' . $orden['DetallesDiagnostico'] . '</li>
                    </ul>
                </td>
                <td colspan="3" style="padding: 10px; border: 1px solid #000; text-align: center;">
                    PRECIO:
                    <h2>' . $orden['Precio'] . ' €</h2>
                </td>
            </tr>
            <tr>
                <td colspan="5" style="padding: 10px; border: 1px solid #000;">FECHA ENTREGA:</td>
                <td  colspan="15" style="padding: 10px; border: 1px solid #000;">' . $orden['FechaEntrega'] . '</td>
            </tr>
            <tr>
                <td colspan="9" style="text-align: right;">GARANTÍA</td>
                <td>' . $orden['MesGarantia'] . '</td>
                <td colspan="10">MES</td>
            </tr>
            <tr>
                <td colspan="20"> <h5>' . $empresa['condicionesReparacionFolio'] . '</h5> </td>
            </tr>
            <hr>
            <tr>
                <td colspan="20" align="justify"> <h6>' . $empresa['proteccionDatos'] . '</h6> </td>
            </tr>
    </table>
    ';

    // Generar el código de barras
    $pdf->write1DBarcode($orden['Numero'], 'C39', '155', '15', '', 18, 0.4, array('position' => '', 'align' => 'C', 'stretch' => false, 'fitwidth' => true, 'cellfitalign' => '', 'border' => false, 'hpadding' => 'auto', 'vpadding' => 'auto', 'fgcolor' => array(0,0,0), 'bgcolor' => false, 'text' => true, 'font' => 'helvetica', 'fontsize' => 8, 'stretchtext' => 4), 'M');

    // Establecer la posición para imprimir el código de barras
    $pdf->SetXY(10, 10);  // Ajusta las coordenadas según tus necesidades

    // Imprimir el código de barras
    $pdf->writeHTMLCell(0, 0, '', '', $pdf->writeHTML($html, true, false, true, false, ''), 0, 1, 0, true);

    $pdf->Output('factura.pdf', 'I');



}

// Función para imprimir el adhesivo
function imprimirAdhesivo($orden_id) {
    // Implementa la lógica para generar e imprimir el adhesivo de la orden
    // Esto puede incluir la creación de un archivo PDF, impresión directa, etc.
    // Asegúrate de que el ID de la orden esté disponible en esta función para generar el adhesivo.
}


?>
