<?php
function rutausers()
{
    $url_model = model('App\Models\Config_model');
    $urlinstalacion = $url_model->find('1'); // 'find' en minúsculas

    if (isset($urlinstalacion['url_instalacion'])) {
        echo "https://" . $urlinstalacion['url_instalacion'] . "/public/assets/uploads/usuarios/";
    } else {
        echo "La clave 'url_instalacion' no existe en el registro.";
    }
}
