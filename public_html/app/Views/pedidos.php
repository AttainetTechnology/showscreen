<?php 
use App\Models\Usuarios_model;
		//comienza el foreach		
 foreach($pedido as $ped) { ?>
<div id="fondo">
    <!-- <input action="action" type="button" value="<- Vover" onclick="history.go(-1);" class="btn btn-warning btn-sm"/> -->
    <input type="button" onclick="printDiv('printableArea')" value="Imprimir Pedido" class="btn btn-success btn-sm"/>

    <div id="printableArea">
    <!-- info row -->
    <div class="fila">
        <div id="fila_left">
           <?php
           $data=datos_user();
           $logo= $data['url_logo'];
           ?>
              <img src="<?php 
              $session = session();
              $session_data = $session->get('logged_in');
              $id_empresa = $session_data['id_empresa']; 
              echo base_url('public/assets/uploads/files/' . $logo);
            ?>" class="logo_partes"><br>
				<br>
                <?php

            $db_cliente = db_connect($data['new_db']);
            $builder = $db_cliente->table('users');
            $builder->select('id, nombre_usuario, apellidos_usuario');
            $user_ped = $nombre_usuario . " " . $apellidos_usuario;
            ?>
		</div>	
        <div id="fila_right"> 
           <div class="capa-numero-parte">
              <div class="numero_pedido">Id: <strong><?php echo $ped->id_pedido; ?></strong></div>
           </div>
            <strong><h3><?php echo $ped->nombre_cliente; ?></h3></strong><br>
            Ref: <strong><?php echo $ped->referencia; ?></strong>
            <div class="parte-fechas">
               <div class="f-entrada">Entrada: <?php echo date("d/m/Y", strtotime($ped->fecha_entrada));?> &nbsp; </div>
               <div class="f-entrega"> Entrega:
                  <strong><?php echo date("d/m/Y", strtotime($ped->fecha_entrega));?></strong></div>
            </div>	
        </div>
    </div> <!-- Cierro fila --> 
       <div class="tituparte">Pedido interno</div>
    <div id="observaciones">
      <div class="seccionparte">Observaciones de pedido:</div>
		<?php echo $ped->observaciones; ?>		
    </div>
    <div class="row">
    <div class="col-xs-12 table-responsive" id="tabla_tipopieza">
        <table class="table">
            <thead>
                <tr> 
	                <th>Unidades</th>
                    <th>Producto</th>
                    <th>Nombre de la base</th>
                    <th>Med. inic.</th>
                    <th>Med. fin.</th>
   					<th>Total</th>
                    </tr>
                </thead>

                <tbody>
    <?php $total=0; ?>
    <?php foreach($lineas as $l) { ?>
                        <tr>
                            <td><b><?php echo $l->n_piezas; ?>	</b></td>
							
                            <td><b><?php echo $l->nombre_producto; ?>	</b></td>
                            <td><b><?php echo $l->nom_base; ?></b></td>
                            <td><b><?php echo $l->med_inicial; ?></b></td>
                            <td><b><?php echo $l->med_final; ?></b></td>
							<td><b><?php echo $l->total_linea; ?></b> &euro;</td>
							<?php $total += $l->total_linea; ?>
                        </tr>
    <?php } // Cierro foreach lineas ?>

			            <tr id="total_pedido">
							<td colspan="6">
				               Total pedido: <b><?php echo $total; ?> &euro;</b><br />
				
				                <strong></strong>
							</td>
			            </tr>
                    </tbody>
            </table>
        </div>
        
        <!-- /.col -->
    </div>
    <div class="row">
        <div class="col-xs-6">

        </div>
        <!-- /.col -->
        <div class="col-xs-6">
    
        </div>
    </div>
    <div class="detalles-pie"> 
          <small>User: <strong><?php echo $user_ped; ?> </strong> | Impresi&oacute;n: <?php echo ' '. date('d-m-Y') ."\n"; ?></small><br>	
    </div>
    </div> <!-- /#Printable area -->
</div> <!-- Fondo -->

<?php } // Cierro foreach pedidos ?>