<?php
use App\Models\transportistas;

helper('controlacceso');

// Obtener los datos del usuario
$data = datos_user();

// Función para obtener el nombre del transportista por su ID
function obtenerNombreTransportistaPorId($id_transportista)
{
    $data = usuario_sesion();
    $db_cliente = db_connect($data['new_db']);
    $builder = $db_cliente->table('users');
    $builder->select('nombre_usuario, apellidos_usuario');
    $builder->where('id', $id_transportista);
    $query = $builder->get();

    if ($query->getNumRows() > 0) {
        $result = $query->getRow();
        return $result->nombre_usuario . ' ' . $result->apellidos_usuario;
    } else {
        return 'No asignado';
    }
}
?>

<?= $this->extend('layouts/partes') ?>

<?= $this->section('content') ?>
<div id="fondo_rutas">
    <?php $hoy = date("d/m/Y"); ?>
    <br>
    <h1  style="margin-left: 20px;">Rutas de transporte</h1><br>
    <p  style="margin-left: 20px;">fecha: <b><?php echo $hoy; ?> </p> </b>
    <?php
    $nueva_poblacion = "";
    // Recorrer todas las rutas
    foreach ($rutas as $r) { ?>
        <?php if ($nueva_poblacion != $r->poblacion) { ?>
            <!-- Mostrar población -->
            <hr>
            <h2  style="margin-left: 20px;">
                <?php echo $r->poblacion;
                $nueva_poblacion = ($r->poblacion); ?>
            </h2>
            <br>
        <?php } ?>
        <div class="col-lg-12">
            <div class="alert <?php if ($r->estado_ruta == '1') { ?>alert-warning<?php } else { ?>panel-default<?php } ?>">
                <div class="panel-heading">
                    <?php $fecha = ($r->fecha_ruta); ?>
                    <!-- Mostrar fecha y detalles de la ruta -->
                    <?php echo date('d/m/Y', strtotime($fecha)); ?> - <?php if ($r->recogida_entrega == '1') { ?>RECOGER<?php } else { ?>ENTREGAR<?php } ?> en: <b><?php echo $r->lugar; ?></b> para
                    <b><?php echo $r->nombre_cliente; ?></b>
                    <?php if ($data['nivel'] > 1) {
                        $transport = obtenerNombreTransportistaPorId($r->transportista);
                    ?>
                        <!-- Mostrar nombre del transportista si el nivel es mayor a 1 -->
                        <br> Transportista: <b><?php echo $transport; ?></b>
                    <?php } ?>
                </div>
                <div class="panel-body">
                    <?php if ($r->estado_ruta == '1') { ?>
                        <!-- Mostrar mensaje si el material no está preparado -->
                        <br>
                        <h3>MATERIAL NO PREPARADO</h3>
                    <?php } else { ?>
                        <!-- Mostrar observaciones y botones de acción -->
                        <?php echo $r->observaciones; ?><br><br>
                        <a href="<?php echo base_url() ?>/Rutas_transporte/entregar_ruta/<?php echo $r->id_ruta; ?>" type="button" class="btn btn-success btn-sm btnrutas">Finalizado</a>
                        <a href="<?php echo base_url() ?>/Rutas_transporte/pendiente_ruta/<?php echo $r->id_ruta; ?>" type="button" class="btn btn-danger btn-sm btnrutas">No preparado</a>
                    <?php } ?>
                </div>
                <br>
            </div>
        </div>


    <?php } // Fin del foreach de rutas 
    ?>
    <br>
    <?php
$session = session();
$session_data = $session->get('logged_in');
$display = 'none';
if ($session_data && array_key_exists('nivel', $session_data) && $session_data['nivel'] == 1) {
    $display = 'block';
}
?>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <div class="navbar-nav" style="display: flex; gap: 10px;">
                <!-- Botón de datos de acceso -->
                <button type="button" id="DatosAcceso" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editUserModal" style="display: <?= $display ?>;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-circle" viewBox="0 0 16 16">
                        <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0"/>
                        <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1"/>
                    </svg>
                </button>

                <!-- Opción para salir -->
                <a href="<?php echo site_url('/home/logout') ?>" id="BotonSalir" class="btn btn-primary" onclick="localStorage.clear();">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-box-arrow-right" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M10 12.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v2a.5.5 0 0 0 1 0v-2A1.5 1.5 0 0 0 9.5 2h-8A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-2a.5.5 0 0 0-1 0z"/>
                        <path fill-rule="evenodd" d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708z"/>
                    </svg>
                </a>
            </div>
        </div>
    </div>
</nav>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var navbarToggler = document.querySelector('.navbar-toggler');

        navbarToggler.addEventListener('click', function() {
            setTimeout(function() {
                window.scrollTo(0, document.body.scrollHeight);
            }, 300);
        });
    });
</script>

<?= view('editTransportista') ?>
</div>
<?= $this->endSection() ?>