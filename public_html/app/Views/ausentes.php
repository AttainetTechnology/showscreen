<?=$cabecera;?>
<body class="page-ausentes" onload="startTime()">
<?=$menu;?>
<div class="fondo-empleados">
<div class="container-fluid d-flex flex-row flex-wrap">   
   <?php //Creo un array para ver los empleados activos asignándoles su id
    $data = array(); // Initialize $data
    foreach($presentes as $presente):
        $data[$presente['id_empleado']] = $presente;
    endforeach; 
    ?>

   <?php foreach($ausentes as $ausente): ?>
   <!-- Comienza el loop -->
   <!--Si el usuario tiene un fichaje activo, es decir, existe, no pinta su tarjeta en el listado de ausentes-->
    <?php if(!isset($data[$ausente['id']])): ?>
        <a href="<?=base_url('entrar/');?>/<?=$ausente['id'];?>" class="stretched-link">
        <div class="tarjeta-empleado">
        <div class="foto_empleado">
            <?php if(!empty($ausente['userfoto'])) { ?>
               <img data-src="<?=base_url('public/assets/uploads/files/' . session()->get('id') . '/usuarios/' . $ausente['userfoto']);?>">
            <?php } else { ?>
               <img data-src="<?=base_url('public/assets/uploads/files/silueta.png')?>">
            <?php } ?>
         </div>
            <span class="nombre-empleado"><?=$ausente['nombre_usuario'];?> <?=$ausente['apellidos_usuario'];?></span>            
        </div>
        </a> 
    <?php endif; ?>
   <?php endforeach; ?>
<!-- Fin del loop -->
</div>
</div>   

<?=$pie;?>
<?=$recarga;?>