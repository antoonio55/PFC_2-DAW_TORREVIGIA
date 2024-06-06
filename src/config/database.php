<?php
$servername = "db5015028076.hosting-data.io";
$username = "dbu2095285";
$password = "1hahqh1f6.";
$dbname = "dbs12486453";

// Crear una conexión a la base de datos
$conn = new mysqli($servername, $username, $password, $dbname);
$conn->set_charset("utf8");

// Verificar la conexión
if ($conn->connect_error) {
    die("Error de conexión a la base de datos: " . $conn->connect_error);
}
?>
