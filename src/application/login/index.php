<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Acceso</title>    
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="sidenav">
         <div class="login-main-text">
            <h2>SISTEMA ORDEN SERVICIO</h2>
            <p>Inicia sesión o regístrate desde aquí para acceder.</p>
            <div class="d-flex justify-content-center align-items-center" style="min-height: 50vh;">
                <img src="../img/logo-s.o.s.png" alt="logo-s.o.s" width="50%" height="50%">
            </div>
         </div>
      </div>
      <div class="main">
         <div class="col-md-6 col-sm-12">
            <?php
                if (isset($_GET["error"])) {
                    echo '<div class="alert alert-danger">' . $_GET["error"] . '</div>';
                }
            ?>
            <div class="login-form">
                <form action="login.php" method="post">
                  <div class="form-group">
                     <label>Usuario</label>
                     <input type="text" class="form-control" placeholder="Introducir Usuario" name="usuario" id="usuario" required>
                  </div>
                  <div class="form-group">
                     <label>Contraseña</label>
                     <input type="password" class="form-control" placeholder="Introducir Contraseña" name="contrasenya" id="contrasenya" required>
                  </div>
                  <button type="submit" class="btn btn-black">Acceder</button>
                  <button type="submit" class="btn btn-secondary">SOY CLIENTE</button>
               </form>
            </div>
         </div>
      </div>
    </div>
    <script src="../../js/jquery-3.5.1.min.js"></script>
    <script src="../../js/bootstrap.min.js"></script>
</body>
</html>







