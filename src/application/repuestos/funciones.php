<?php
include '../../config/database.php';

// Determinamos qué función ejecutar.
if (isset($_POST['action'])) {
    $action = $_POST['action'];
    if ($action === 'insertarPieza') {
        // Ejecutar la función insertarPieza.
        $nombre_pieza = $_POST["nombre_pieza"];
        $cantidad_pieza = $_POST["cantidad_pieza"];
        $precio_pieza = $_POST["precio_pieza"];
        $descripcion_pieza = $_POST["descripcion_pieza"];
        insertarPieza($nombre_pieza, $cantidad_pieza, $precio_pieza, $descripcion_pieza);
    } elseif ($action === 'actualizarPieza') {
        // Ejecutar la función actualizarPieza.
        $id_pieza = $_POST["id_pieza"];
        $nombre_pieza = $_POST["nombre_pieza"];
        $cantidad_pieza = $_POST["cantidad_pieza"];
        $precio_pieza = $_POST["precio_pieza"];
        $descripcion_pieza = $_POST["descripcion_pieza"];
        actualizarPieza($id_pieza, $nombre_pieza, $cantidad_pieza, $precio_pieza, $descripcion_pieza);
    } elseif ($action === 'eliminarPieza') {
        // Ejecutar la función eliminarPieza.
        $id_pieza = $_POST["id_pieza"];
        eliminarPieza($id_pieza);
    }
    // Agregar más casos para otras acciones si es necesario.
}

// Determinamos qué función ejecutar para las acciones GET.
if (isset($_GET['action'])) {
    $action = $_GET['action'];
    if ($action === 'obtenerPiezaPorID') {
        // Ejecutar la función obtenerPiezaPorID.
        $id_pieza = $_GET['id_pieza'];
        obtenerPiezaPorID($id_pieza);
    }
}

// Funciones

function insertarPieza($nombre_pieza, $cantidad_pieza, $precio_pieza, $descripcion_pieza) {
    global $conn;

    $stmt = $conn->prepare("INSERT INTO piezas_repuesto (nombre_pieza, cantidad_pieza, precio_pieza, descripcion_pieza) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sidd", $nombre_pieza, $cantidad_pieza, $precio_pieza, $descripcion_pieza);

    return $stmt->execute();
}

function actualizarPieza($id_pieza, $nombre_pieza, $cantidad_pieza, $precio_pieza, $descripcion_pieza) {
    global $conn;

    $stmt = $conn->prepare("UPDATE piezas_repuesto SET nombre_pieza = ?, cantidad_pieza = ?, precio_pieza = ?, descripcion_pieza = ? WHERE id_pieza = ?");
    $stmt->bind_param("sidsi", $nombre_pieza, $cantidad_pieza, $precio_pieza, $descripcion_pieza, $id_pieza);

    return $stmt->execute();
}

function eliminarPieza($id_pieza) {
    global $conn;

    $stmt = $conn->prepare("DELETE FROM piezas_repuesto WHERE id_pieza = ?");
    $stmt->bind_param("i", $id_pieza);

    return $stmt->execute();
}

function obtenerPiezasRepuesto() {
    global $conn;

    // Consulta SQL para seleccionar todas las piezas de repuesto
    $sql = "SELECT * FROM piezas_repuesto";

    // Ejecutar la consulta
    $result = $conn->query($sql);

    // Verificar si hay resultados
    if ($result->num_rows > 0) {
        // Array para almacenar las piezas de repuesto
        $piezas = array();

        // Iterar sobre los resultados y almacenar cada fila en el array de piezas
        while ($row = $result->fetch_assoc()) {
            $piezas[] = $row;
        }

        // Devolver el array de piezas de repuesto
        return $piezas;
    } else {
        // Si no hay resultados, devolver un array vacío
        return array();
    }
}

function obtenerPiezasFiltradas($nombre_pieza, $cantidad_pieza, $precio_pieza) {
    global $conn;
    // Inicializa la consulta SQL
    $sql = "SELECT * FROM piezas_repuesto WHERE 1 = 1"; // Siempre verdadero para facilitar la construcción de la consulta

    if (!empty($nombre_pieza)) {
        $sql .= " AND nombre_pieza LIKE '%$nombre_pieza%'";
    }

    if (!empty($cantidad_pieza)) {
        $sql .= " AND cantidad_pieza = '$cantidad_pieza'";
    }

    if (!empty($precio_pieza)) {
        $sql .= " AND precio_pieza = '$precio_pieza'";
    }

    $sql .= " ORDER BY id_pieza DESC;";

    // Realiza la consulta
    $result = $conn->query($sql);

    $piezas_repuesto = array();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $piezas_repuesto[] = $row;
        }
    }

    // Cierra la conexión a la base de datos
    $conn->close();

    return $piezas_repuesto;
}

function obtenerPiezaPorID($id_pieza) {
    global $conn;

    $stmt = $conn->prepare("SELECT * FROM piezas_repuesto WHERE id_pieza = ?");
    $stmt->bind_param("i", $id_pieza);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}
?>
