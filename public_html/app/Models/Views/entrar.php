<?=$cabecera;?>
<body class="page-ausentes" onload="startTime()">
<?=$menu;?>
<div class="fondo-empleados">
<div class="container-fluid d-flex flex-row flex-wrap">
<!-- Comienza el loop -->
      <div class="ficha-entrada">
         <div class="fichafoto">

            <?php if(!empty($ausentes['userfoto'])) { ?>
               <img data-src="<?=base_url('public/assets/uploads/files/' . session()->get('id') . '/usuarios/' . $ausentes['userfoto']);?>">
            <?php } else { ?>
               <img data-src="<?=base_url('public/assets/uploads/files/silueta.png')?>">
            <?php } ?>

         </div>
         <div class="fichabotones">
            <div class="titulopagina">FICHAR ENTRADA</div>
           <div class="fichar-nombre-empleado"><?=$ausentes['nombre_usuario'];?> <?=$ausentes['apellidos_usuario'];?></div>
          <div class="botonentrar"><a href="<?=base_url('/entra/')?>/<?=$ausentes['id'];?>" class="btn btn-lg btn-success">ENTRAR</a></div>
          <div class="botonextras"><a href="<?=base_url('/entraextras/')?>/<?=$ausentes['id'];?>">Horas Extras</a></div>
          <div class="volver"><a href="<?=base_url('/ausentes')?>" class="btn btn-light"><span class="glyphicon glyphicon-arrow-left"></span> Volver</a></div>
          </div>
         </div> 
      </div>

<!-- Fin del loop -->
</div>
</div>   

<?=$pie;?>
<?=$recarga;?>
