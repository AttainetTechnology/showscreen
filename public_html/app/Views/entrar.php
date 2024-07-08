<?=$cabecera;?>
<body class="page-ausentes" onload="startTime()">
<?=$menu;?>
<div class="fondo-empleados">
<div class="container-fluid d-flex flex-row flex-wrap">
<!-- Comienza el loop -->
      <div class="ficha-entrada">
         <div class="fichafoto">
               <? if($ausentes['userfoto']){ ?>
                  <img src="<? helper('rutausers');
                       $rutausers=rutausers();
                       echo $rutausers; ?>/<?=$ausentes['userfoto'];?>">
                  <? } else {?>
                  <img src="<?=base_url('resources/images/silueta.png')?>">
                  <?}?>
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
