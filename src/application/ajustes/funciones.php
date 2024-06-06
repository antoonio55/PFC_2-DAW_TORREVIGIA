<?php
include '../../config/database.php';


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

function actualizarDatosEmpresa($nombreEmpresa, $cif, $direccion, $telefono, $email, $eslogan, $condicionesReparacionTicket, $condicionesReparacionFolio, $proteccionDatos) {
    global $conn;

    // Escapar y validar los datos según sea necesario

    $sql = "UPDATE datosEmpresa SET 
            nombreEmpresa = ?,
            cif = ?,
            direccion = ?,
            telefono = ?,
            email = ?,
            eslogan = ?,
            condicionesReparacionTicket = ?,
            condicionesReparacionFolio = ?,
            proteccionDatos = ?
            WHERE 1";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssss", $nombreEmpresa, $cif, $direccion, $telefono, $email, $eslogan, $condicionesReparacionTicket, $condicionesReparacionFolio, $proteccionDatos);
    return $stmt->execute();
}
?>


