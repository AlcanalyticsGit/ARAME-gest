<?php

    // Ruta de la aplicacion
    define('RUTA_APP', dirname(dirname(__FILE__)));

    define('RUTA_URL', 'http://localhost:8080/ARAME-gest');
    // define('RUTA_URL', 'https://aramegest.alcanalytics.com');

    define('NOMBRE_SITIO', 'ARAME - Gestión de socias');
    define('VERSION', '0.8.1-BETA');
    define('FOOTER_TXT', '© 2022 Alcanalytics');

    // Configuracion de la Base de Datos
    define('DB_HOST', 'localhost');
    define('DB_USUARIO', 'arame_db_user');
    define('DB_PASSWORD', 'w66fl6OQWKFpCgFU');
    define('DB_NOMBRE', 'arame');

    // Configuración de Symfony Mailer
    define('CORREO_ARAME', 'secretaria@arame.org');
    $mail_pass = 'aramE-2021@';
    $servidor = 'mail.arame.org';
    $puerto = '465';
    define('MAILER_DSN', 'smtp://'.CORREO_ARAME.':'.$mail_pass.'@'.$servidor.':'.$puerto);

    // Configuración de ficheros
    define('RUTA_AVATARES', RUTA_APP.'/ficheros/socias/avatares');
    define('RUTA_LOGOS', RUTA_APP.'/ficheros/empresas/logos');
