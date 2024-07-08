<?php
// Ruta absoluta al directorio del script
$scriptDirectory = dirname(__FILE__);

// Ruta al archivo que almacena la última fecha de acceso
$lastAccessFile = $scriptDirectory . '/last_access.txt';

// Ruta al directorio de sesiones
$sessionDirectory = $scriptDirectory . '/writable/session';

// Iniciar sesión si no hay una sesión activa
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Leer la fecha del archivo
$lastAccessDate = file_get_contents($lastAccessFile);

// Variable de control para verificar si la fecha ha cambiado
$dateChanged = false;

// Actualizar la fecha de último acceso en el archivo si es diferente a la fecha actual
$currentDate = date('Y-m-d');
if ($currentDate != $lastAccessDate) {
    file_put_contents($lastAccessFile, $currentDate);
    $dateChanged = true;
}

// Si la fecha ha cambiado, eliminar las sesiones
if ($dateChanged) {
    // Obtener el nombre del archivo de la sesión actual
    $currentSessionFile = $sessionDirectory . '/ci_session' . session_id();

    // Eliminar todos los archivos de sesión en el directorio de sesiones, excepto la sesión actual
    $files = glob($sessionDirectory . '/ci_session*');
    foreach($files as $file) {
        if(is_file($file) && $file != $currentSessionFile) {
            unlink($file); // Eliminar archivo
        }
    }
}
?>