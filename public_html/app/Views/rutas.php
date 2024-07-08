<?php
use App\Models\transportistas;
helper('controlacceso');

// Obtener los datos del usuario
$data = datos_user();

// Función para obtener el nombre del transportista por su ID
function obtenerNombreTransportistaPorId($id_transportista) {
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
    <h1>Rutas de transporte</h1><br>fecha: <b><?php echo $hoy; ?> </b>
    <?php 
    $nueva_poblacion = "";
    // Recorrer todas las rutas
    foreach ($rutas as $r) { ?>
        <?php if ($nueva_poblacion != $r->poblacion) { ?>
            <!-- Mostrar población -->
            <hr><h2> 
            <?php echo $r->poblacion; 
            $nueva_poblacion = ($r->poblacion); ?>
            </h2>
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
                        <br><h3>MATERIAL NO PREPARADO</h3>
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
    <?php } // Fin del foreach de rutas ?>
</div>
<?= $this->endSection() ?>
