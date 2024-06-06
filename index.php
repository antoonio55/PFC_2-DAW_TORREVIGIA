<?php
/////////////////////////////////////////////////
// COMPROBAMOS QUE EL USUARIO ESTÁ AUTENTICADO //
/////////////////////////////////////////////////

session_start();

if (!isset($_SESSION["user_id"])) {
    error_log("Intento de acceso no autorizado: " . $_SERVER['REQUEST_URI']);
    header("Location: ./src/application/login/index.php");
    exit;
}

// Regenera la ID de sesión después de la autenticación
session_regenerate_id(true);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema Orden de Servicio</title>
    <!-- Enlaces a los archivos CSS de Bootstrap -->
    <link rel="stylesheet" href="./src/css/bootstrap.min.css">
    <link rel="stylesheet" href="./src/font-awesome/css/all.css">

    <style>
        .nav-pills .nav-item .active {
            background-color: #ffc107; /* Color similar al de bg-warning */
            color: black !important; /* Texto negro */
        }
        iframe {
          width: 100%;
          border: none;
          box-sizing: border-box; /* Incluir el padding y border en la altura */
        }
        footer {
            background-color: #343a40; /* Color oscuro */
            color: white; /* Texto blanco */
            text-align: center; /* Alinear al centro */
            padding: 10px; /* Añadir espacio alrededor del contenido */
            position: fixed; /* Fijar el footer en la parte inferior de la página */
            width: 100%; /* Ocupar todo el ancho */
            bottom: 0; /* Pegarlo en la parte inferior */
            box-sizing: border-box; /* Incluir el padding y border en la altura */
        }
    </style>
</head>

<body>

    <!-- Banner superior utilizando Bootstrap -->
    <header class="bg-dark text-white text-center py-1">
        <h1>Sistema Orden de Servicio</h1>
    </header>

    <!-- Menú de opciones utilizando Bootstrap -->
    <nav class="navbar navbar-expand-lg navbar-secondary bg-secondary">
        <ul class="nav nav-pills" style="display: flex; justify-content: space-between; width: 100%;">
            <li class="nav-item ms-2">
                <a class="nav-link text-warning" href="#" onclick="ejecutarAccion('./src/application/main/index.php')">INICIO</a>
            </li>
          
            <li class="nav-item">
                <a class="nav-link text-warning" href="#" onclick="ejecutarAccion('./src/application/orden_servicio/index.php')">ÓRDENES DE SERVICIO</a>
            </li>
          
            <li class="nav-item">
                <a class="nav-link text-warning" href="#" onclick="ejecutarAccion('./src/application/clientes/index.php')">CLIENTES</a>
            </li>
          
          	<li class="nav-item">
                <a class="nav-link text-warning" href="#" onclick="ejecutarAccion('./src/application/repuestos/index.php')">REPUESTOS</a>
            </li>
          
            <li class="nav-item">
                <a class="nav-link text-warning" href="#" onclick="ejecutarAccion('./src/application/ajustes/index.php')">AJUSTES</a>
            </li>
          
            <li class="nav-item ms-auto">
                <a class="nav-link text-warning" href="src/application/login/cerrar_sesion.php"><i class="fas fa-sign-out-alt" style="color: #ffc107; font-size: 1.3em;"></i></a>
            </li>
        </ul>
    </nav>

    <div id="contenedor_iframe" style="margin-bottom: 60px;"> <!-- Añadir un margen inferior para el footer -->
    	<iframe id="iframe_principal" onload="retrasarAjusteAlturaIframe(this)"></iframe>
	</div>

    <!-- Footer -->
    <footer>
        &copy; 2024 Sistema Orden de Servicio
    </footer>

	<script src="./src/js/jquery-3.5.1.slim.min.js"></script>
	<script src="./src/js/bootstrap.bundle.min.js"></script>

	<script>
        document.addEventListener('DOMContentLoaded', function () {
            ejecutarAccion('./src/application/main/index.php');
        });
      
      	// Retrasa el ajuste de la altura del iframe
        function retrasarAjusteAlturaIframe(iframe) {
            setTimeout(function() {
                ajustarAlturaIframe(iframe);
            }, 500); // Aumenta el tiempo de espera a 500 milisegundos
        }

        function ejecutarAccion(url) {
            var ifr = document.getElementById("iframe_principal");
            ifr.setAttribute("src", url);
        }

        // Ajusta dinámicamente la altura del iframe cuando se carga completamente
        function ajustarAlturaIframe(iframe) {
            var footerHeight = document.querySelector('footer').offsetHeight;
            var nuevaAltura = iframe.contentWindow.document.body.scrollHeight + footerHeight;

            // Establecer una altura mínima por defecto
            var alturaMinima = 770; // Puedes ajustar este valor según tus necesidades

            // Verificar si la nueva altura es menor que la altura mínima por defecto
            if (nuevaAltura < alturaMinima) {
                iframe.style.height = alturaMinima + 'px';
            } else {
                iframe.style.height = nuevaAltura + 'px';
            }
        }

        // Escuchar el evento load del iframe para ajustar su altura
        document.addEventListener('DOMContentLoaded', function () {
            var iframe = document.getElementById("iframe_principal");
            iframe.onload = function() {
                retrasarAjusteAlturaIframe(this);
            };

            // Escuchar el evento resize del contenido del iframe
            iframe.contentWindow.addEventListener('resize', function() {
                ajustarAlturaIframe(iframe);
            });
        });

        // Marca el elemento "INICIO" como activo al cargar la página
        document.addEventListener('DOMContentLoaded', function () {
            var inicioLink = document.querySelector('.nav-pills .nav-item:first-child .nav-link');
            inicioLink.classList.add('active');
        });

        // Agrega la clase 'active' al botón pulsado
        document.addEventListener('DOMContentLoaded', function () {
            var navLinks = document.querySelectorAll('.nav-pills .nav-link');
            navLinks.forEach(function (link) {
                link.addEventListener('click', function () {
                    navLinks.forEach(function (innerLink) {
                        innerLink.classList.remove('active');
                    });
                    this.classList.add('active');
                });
            });
        });
    </script>

</body>

</html>

