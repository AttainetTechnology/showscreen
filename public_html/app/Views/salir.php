<?= $cabecera; ?>

<body class="page-presentes" onload="startTime()">
   <?= $menu; ?>
   <div class="fondo-empleados">
      <div class="container-fluid d-flex flex-row flex-wrap">
         <!-- Comienza el loop -->
         <div class="ficha-entrada">
            <div class="fichafoto">
               <?php if (!empty($ausentes['userfoto'])) { ?>
                  <img data-src="<?= base_url('public/assets/uploads/files/' . session()->get('id') . '/usuarios/' . $ausentes['userfoto']); ?>">
               <?php } else { ?>
                  <img data-src="<?= base_url('public/assets/uploads/files/silueta.png') ?>">
               <?php } ?>
            </div>
         </div>
         <div class="fichabotones">
            <div class="titulopagina">FICHAR SALIDA</div>
            <div class="fichar-nombre-empleado"><?= $ausentes['nombre_usuario']; ?> <?= $ausentes['apellidos_usuario']; ?></div>
            <div class="botonentrar"><a href="<?= base_url('/sal/') ?>/<?= $ausentes['id']; ?>" class="btn btn-danger btn-lg">TERMINAR JORNADA</a></div>

            
            <?php
            $datos = new \App\Models\Usuarios2_Model();
            $data = usuario_sesion();
            $id_empresa = $data['id_empresa'];
            $dbConnectionsModel = new \App\Models\DbConnectionsModel();
            $nif = $dbConnectionsModel->getNIF($id_empresa);
            if ($nif === null) {
               die('No se encontró un NIF para el id_empresa proporcionado');
            }

            $url = "https://dev.showscreen.app/presentes/" . $nif;
            ?>

            <div class="volver">
               <a href="<?php echo $url; ?>" class="btn btn-light">
                  <span class="glyphicon glyphicon-arrow-left"></span> Volver
               </a>
            </div>
         </div>
      </div>
   </div>

   <!-- Fin del loop -->
   </div>
   </div>

   <?= $pie; ?>
   <?= $recarga; ?>