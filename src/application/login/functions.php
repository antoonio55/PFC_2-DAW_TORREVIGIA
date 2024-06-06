<?php
include '../../config/config.php'; // Incluye el archivo de configuración primero
include '../../config/database.php'; // Luego incluye otros archivos de ser necesario

// Inicia la sesión después de incluir el archivo de configuración
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    global $conn;
    
    $username = $_POST["usuario"];
    $password = $_POST["contrasenya"];
    
    $query = "SELECT id_usuario, nombre_usuario, contrasenya_usuario FROM usuarios WHERE nombre_usuario = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($user_id, $db_username, $db_password);
    $stmt->fetch();
    
    if (password_verify($password, $db_password)) {
        // Contraseña válida, iniciar sesión
        $_SESSION["user_id"] = $user_id;
        header("Location: ../../../index.php");
    } else {
        // Credenciales incorrectas, muestra un mensaje de error
        header("Location: ./index.php?error=Credenciales incorrectas");
    }
    
    $stmt->close();
    $conn->close();
}
?>

