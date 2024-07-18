<?php if (!empty($lineas)) { ?>
    <?php foreach ($lineas as $l) { ?>
        <?php if (!empty($productos)) { ?>
            <?php foreach ($productos as $prod) { ?>
                <?php if (!empty($pedidos)) { ?>
                    <?php foreach ($pedidos as $p) { ?>
                        <?php if (!empty($clientes)) { ?>
                            <?php foreach ($clientes as $cli) { ?>
                                <div id="fondo">
                                    <?php
                                    $volver = isset($_GET['pg2']) ? $_GET['pg2'] : ""; 
                                    ?> 

                                    <a href="javascript:history.back()" class="btn btn-warning btn-sm">Cerrar</a>

                                    <a href="<?php echo base_url()."/Partes_controller/CambiaEstado/".$l->id_lineapedido."?volver=".$volver; ?>"  class="btn btn-info btn-sm">Cerrar y marcar línea como recibida</a>

                                    <input type="button" onclick="printDiv('printableArea')" value="Imprimir Parte" class="btn btn-success btn-sm"/>

                                    <div id="printableArea">
                                        <!-- Cabecera -->
                                        <div class="row">
                                            <div id="parte_fila_left">
                                                <?php
                                                $data = datos_user();
                                                $logo = $data['url_logo'];
                                                $session = session();
                                                $session_data = $session->get('logged_in');
                                                $id_empresa = $session_data['id_empresa']; 
                                                ?>
                                                <img src="<?php echo base_url('public/assets/uploads/files/' .  $logo); ?>" class="logo_partes"><br>
                                                <br>
                                                Cliente: 
                                                <address>
                                                    <strong><?php echo $cli->nombre_cliente; ?></strong>
                                                </address>
                                                Referencia ped:
                                                <address>
                                                    <strong><?php echo $p->referencia; ?></strong>
                                                </address>
                                            </div>
                                            <div id="parte_fila_right" class="imagenparte">
                                                <div class="capa-numero-parte">
                                                    <div class="numero_parte">Id: <strong><?php echo $p->id_pedido; ?></strong></div>
                                                </div>	
                                                <h3><b><?php echo $prod->nombre_producto; ?></b></h3>
                                                <div class="parte-fechas">
                                                    <div class="f-entrada">Entrada: <?php echo date("d-m-Y", strtotime($p->fecha_entrada)); ?> &nbsp;</div>
                                                    <div class="f-entrega">Entrega:
                                                        <strong><?php echo date("d-m-Y", strtotime($p->fecha_entrega)); ?></strong>
                                                    </div>
                                                </div>
                                                <img src="<?php echo base_url('public/assets/uploads/files') . "/$id_empresa/productos/" . $prod->imagen; ?>" class="imagen_parte"/><br /> 			
                                            </div>
                                            <!-- END Cabecera -->
                                        </div><!-- /.row -->
                                        <!-- Table row -->
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
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td><b><?php echo $l->n_piezas; ?></b></td>
                                                            <td><b><?php echo $prod->nombre_producto; ?></b></td>
                                                            <td><b><?php echo $l->nom_base; ?></b></td>
                                                            <td><b><?php echo $l->med_inicial; ?></b></td>
                                                            <td><b><?php echo $l->med_final; ?></b></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                                <div id="observaciones">
                                                    <?php if ($l->lado != "") { ?>
                                                        Lado a mecanizar: <strong><?php echo $l->lado; ?></strong><br>
                                                    <?php } ?>
                                                    <?php if ($l->distancia != "") { ?>
                                                        Distancia de las ranuras: <strong><?php echo $l->distancia; ?> cm.</strong><br><br>
                                                    <?php } ?>
                                                    <?php if ($l->observaciones != "") { ?>
                                                        Observaciones: <strong><?php echo $l->observaciones; ?></strong>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                            <!-- /.col -->
                                        </div>
                                        <div class="row">
                                            <div class="col-xs-12 table-responsive">
                                                <?php 
                                                $i = 1;
                                                helper('controlacceso');
                                                $data= usuario_sesion(); 
                                                $dbClient = db_connect($data['new_db']);                                         
                                                $id_producto = $prod->id_producto; 
                                                $builder = $dbClient->table('procesos_productos');
                                                $builder->select('procesos.*');
                                                $builder->join('procesos', 'procesos.id_proceso = procesos_productos.id_proceso');
                                                $builder->where('procesos_productos.id_producto', $id_producto);
                                                $builder->orderBy('procesos_productos.orden', 'ASC');
                                                $query = $builder->get();
                                                $result = $query->getResult();
                                        
                                                foreach ($result as $proc) { ?>
                                                    <?php if ($i == 7) { ?>
                                                        <div class="detalles-pie"> 
                                                            <small>L.P: <strong><?php echo $l->id_lineapedido; ?></strong> | User: <strong><?php echo $nombre_usuario; ?> <?php echo $apellidos_usuario; ?></strong> | Impresi&oacute;n: <?php echo ' '. date('d-m-Y') ."\n"; ?></small><br>	
                                                        </div>
                                                        <div class="pagina1"></div>
                                                        <div class="pagina2"></div>
                                                        <!-- Repetimos la cabecera para la segunda pagina -->
                                                        <!-- Cabecera -->
                                                        <div class="row">
                                                            <div id="parte_fila_left">
                                                                <img src="<?php echo base_url("public/assets/uploads/logo/") . "/" . $url_logo; ?>" class="logo_partes"><br> 
                                                                Cliente: 
                                                                <address>
                                                                    <strong><?php echo $cli->nombre_cliente; ?></strong>
                                                                </address>
                                                                Referencia ped:
                                                                <address>
                                                                    <strong><?php echo $p->referencia; ?></strong>
                                                                </address>
                                                            </div>
                                                            <div id="parte_fila_right" class="imagenparte">
                                                                <div class="capa-numero-parte">
                                                                    <div class="numero_parte">Parte T. <strong><?php echo $p->id_pedido; ?></strong></div><H2>Hoja 2 &nbsp;</H2>
                                                                </div>
                                                                <h3><b><?php echo $prod->nombre_producto; ?></b></h3>
                                                                <div class="parte-fechas">
                                                                    <div class="f-entrada">Entrada: <?php echo date("d-m-Y", strtotime($p->fecha_entrada)); ?> &nbsp;</div>
                                                                    <div class="f-entrega">Entrega:
                                                                        <strong><?php echo date("d-m-Y", strtotime($p->fecha_entrega)); ?></strong>
                                                                    </div>
                                                                </div>
                                                                <img src="<?php echo base_url("public/assets/uploads/files/") . "/" . $prod->imagen; ?>" class="imagen_parte"/><br /> 			
                                                            </div>
                                                            <!-- END Cabecera -->
                                                            <div class="row"> <!-- DIV ROW-->
                                                                <div class="col-xs-12 table-responsive" id="tabla_tipopieza"> <!-- DIV COL XS 12-->
                                                                    <table class="table">
                                                                        <thead>
                                                                            <tr>
                                                                                <th>Unidades</th>
                                                                                <th>Producto</th>
                                                                                <th>Nombre de la base</th>
                                                                                <th>Med. inic.</th>
                                                                                <th>Med. fin.</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            <tr>
                                                                                <td><b><?php echo $l->n_piezas; ?></b></td>
                                                                                <td><b><?php echo $prod->nombre_producto; ?></b></td>
                                                                                <td><b><?php echo $l->nom_base; ?></b></td>
                                                                                <td><b> <?php echo $l->med_inicial; ?></b></td>
                                                                                <td><b> <?php echo $l->med_final; ?></b></td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                    <div id="observaciones">
                                                                        <?php if ($l->lado != "") { ?>
                                                                            Lado a mecanizar: <strong><?php echo $l->lado; ?></strong><br>
                                                                        <?php } ?>
                                                                        <?php if ($l->distancia != "") { ?>
                                                                            Distancia de las ranuras: <strong><?php echo $l->distancia; ?> cm.</strong><br>
                                                                        <?php } ?>
                                                                        <br>
                                                                        <?php if ($l->observaciones != "") { ?>
                                                                            Observaciones: <strong><?php echo $l->observaciones; ?></strong>
                                                                        <?php } ?>
                                                                    </div><!-- DIV FILA OBSERVACIONES -->
                                                                </div> <!-- DIV COL XS 12-->	
                                                            </div> <!-- DIV ROW-->
                                                        </div> <!-- CIERRO DIV LIBRE -->
                                                        <!-- Fin Repetimos la cabecera para la segunda pagina -->
                                                    <?php } ?>
                                                    <div class="linea_proceso">
                                                        <table class="tabla_proceso">
                                                            <tr>
                                                                <td><b><?php echo $proc->nombre_proceso ?></b><br><br>Operario/s:</td>
                                                                <td>
                                                                    <table class="casillas">
                                                                        <tr>
                                                                            <td class="cabecera_tabla">Fecha</td>
                                                                            <td class="cabecera_tabla">Hora ini.</td>
                                                                            <td class="cabecera_tabla">Hora fin.</td>
                                                                            <td class="cabecera_tabla"><b>Buenas</b></td>
                                                                            <td class="cabecera_tabla"><b>Malas</b></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td class="casillas"></td>
                                                                            <td class="casillas"></td>
                                                                            <td class="casillas"></td>
                                                                            <td class="casillas"></td>
                                                                            <td class="casillas"></td>
                                                                        </tr>
                                                                    </table>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                    <?php $i += 1; ?>
                                                <?php } //Cierro foreach proceso ?>
                                            </div>
                                            <!-- /.col -->
                                        </div>
                                        <div class="pie_de_parte">
                                            <div class="incidencia">Ha habido alguna incidencia?</div>
                                            <div class="revisado">Revisado por:</div>
                                            <div class="total_buenas"><b>TOTALES BUENAS:</b>
                                                <div class="total">&nbsp;&nbsp;&nbsp;&nbsp;</div>
                                            </div>
                                        </div> <!-- Cierro el pie del parte -->
                                        <?php if (!empty($mas_de_una_linea)) { ?>
                                            <div class="pedido-completo-pie">
                                                <h3><b>Atenci&oacute;n!</b></h3> Este parte forma parte de un pedido con otros partes de trabajo. Comprueba si hay que utilizar el material sobrante con otro parte de trabajo:<br>
                                                <?php foreach ($mas_de_una_linea as $mas) { ?>
                                                    <?php if ($mas->id_lineapedido != $l->id_lineapedido) { ?>
                                                        - <?php echo $mas->n_piezas; ?> <?php echo $mas->nombre_producto; ?> - Nom. base: <B><?php echo $mas->nom_base; ?></B><br>
                                                    <?php } ?>
                                                <?php } ?>
                                            </div>
                                        <?php } ?>
                                        <div class="detalles-pie"> 
                                            <small>L.P: <strong><?php echo $l->id_lineapedido; ?></strong> | User: <strong><?php echo $nombre_usuario; ?> <?php echo $apellidos_usuario; ?></strong> | Impresi&oacute;n: <?php echo ' '. date('d-m-Y') ."\n"; ?></small><br>	
                                        </div>
                                    </div>
                                    <!-- /#Printable area -->
                                </div> <!-- Fondo -->
                            <?php } ?>
                        <?php } else { echo "<div class='alert alert-warning'>No hay clientes disponibles.</div>"; } ?>
                    <?php } ?>
                <?php } else { echo "<div class='alert alert-warning'>No hay pedidos disponibles.</div>"; } ?>
            <?php } ?>
        <?php } else { echo "<div class='alert alert-warning'>No hay productos disponibles.</div>"; } ?>
    <?php } ?>
<?php } else { echo "<div class='alert alert-warning'>No hay líneas disponibles.</div>"; } ?>
