<?php
// Configuración de errores
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Configuración de la zona horaria
date_default_timezone_set('Europe/Madrid'); // Ajusta la zona horaria según tus necesidades

// Configuración de rutas
define('BASE_URL', 'https://tools-dev.inforepair.net'); // URL de tu aplicación
define('ROOT_PATH', dirname(__FILE__) . "/ORDEN_SERVICIO_2/" ); // Ruta al directorio raíz del sitio

// Configuración de seguridad
define('SECRET_KEY', 'pm$w3v5)@6!m2voqpi1z_%l)u)nb%$q+2+zhv3em(08vou$s%m'); // Cambia esto con una clave segura
define('HASH_ALGORITHM', 'sha256'); // Algoritmo de hash para contraseñas
define('CSRF_TOKEN_NAME', 'csrf_token'); // Nombre del token CSRF
define('SECURE_COOKIE', true); // Marcar cookies como seguras (solo HTTPS)
define('SECURE_SESSION', true); // Usar sesiones seguras (HTTPS)
define('SECURE_FORMS', true); // Añadir token CSRF a formularios

// Configuración de sesión
//ini_set('session.cookie_lifetime', 3600); // Duración de la cookie de sesión en segundos
//ini_set('session.gc_maxlifetime', 3600); // Tiempo de vida máximo de sesión en segundos
//session_name('session'); // Nombre personalizado para la cookie de sesión
//session_set_cookie_params(0, '/', 'tools-dev.inforepair.net'); // Parámetros de cookie de sesión
//define('SESSION_NAME_PREFIX', 'app_'); // Prefijo para el nombre de la sesión
//define('SESSION_USE_ONLY_COOKIES', true); // Solo permitir el uso de cookies para la sesión
//define('SESSION_HTTP_ONLY', true); // Establecer la bandera HttpOnly en la cookie de sesión

// Configuración de carga de archivos
ini_set('upload_max_filesize', '10M'); // Tamaño máximo de archivo para carga
define('UPLOAD_DIR', ROOT_PATH . '/uploads'); // Directorio para cargar archivos
define('MAX_FILE_SIZE', 10485760); // Tamaño máximo de archivo en bytes (10MB)
define('ALLOWED_FILE_TYPES', ['jpg', 'jpeg', 'png', 'pdf']); // Tipos de archivo permitidos

// Configuración de correo electrónico
define('SMTP_HOST', 'smtp.tudominio.com');
define('SMTP_USER', 'tu_email@tudominio.com');
define('SMTP_PASSWORD', 'tu_contraseña');
define('SMTP_PORT', 587);
define('SMTP_ENCRYPTION', 'tls');
define('ADMIN_EMAIL', 'admin@tudominio.com'); // Correo electrónico del administrador del sitio
define('MAIL_FROM_ADDRESS', 'noreply@tudominio.com');
define('MAIL_FROM_NAME', 'Tu Nombre');
define('MAIL_REPLY_TO', 'support@tudominio.com');
define('MAIL_REPLY_TO_NAME', 'Soporte');

// Configuración de cache
define('CACHE_ENABLED', true);
define('CACHE_EXPIRATION', 3600); // Tiempo de expiración de la caché en segundos
define('CACHE_DIR', ROOT_PATH . '/cache'); // Directorio para archivos de caché
define('CACHE_PREFIX', 'app_cache_'); // Prefijo para claves de caché

// Configuración de idioma
define('DEFAULT_LANGUAGE', 'es'); // Idioma predeterminado

// Configuración de redireccionamiento seguro (HTTPS)
define('FORCE_HTTPS', true);

// Configuración de seguridad adicional
define('X_FRAME_OPTIONS', 'SAMEORIGIN'); // Política de seguridad para evitar ataques de clics en X-Frame
define('CONTENT_SECURITY_POLICY', "default-src 'self'; script-src 'self' 'unsafe-inline'; style-src 'self' 'unsafe-inline';"); // Configuración de la política de seguridad de contenido

// Otras configuraciones
define('MAX_LOGIN_ATTEMPTS', 5); // Número máximo de intentos de inicio de sesión permitidos
define('SESSION_TIMEOUT', 30 * 60); // Tiempo de expiración de sesión en segundos
define('LOG_PATH', ROOT_PATH . './src/logs'); // Directorio para archivos de registro
define('DEBUG_MODE', false); // Modo de depuración
define('MAINTENANCE_MODE', false); // Modo de mantenimiento
define('LOG_ERRORS', true); // Registrar errores en archivos de registro

// Otros parámetros de configuración, si es necesario

?>
