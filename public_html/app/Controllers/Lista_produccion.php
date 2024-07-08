<?php

namespace App\Controllers;

class Lista_produccion extends BaseControllerGC
{
    protected $Menu_familias_model;
	/* 
		Las siguientes funciones diferencian cada una de las páginas o urls cambiando el estado de la select entre comillas definimos los parametros:
		$coge_estado, $where_estado y selectfamilia 
	*/
	
	public function pendientes($selectfamilia = 0) { $this->todos('estado=','0', $selectfamilia, 'pendientes'); }
	public function enmarcha($selectfamilia)	{	$this->todos('estado=','2', $selectfamilia, 'en cola'); 	}
	public function enmaquina($selectfamilia)	{	$this->todos('estado=','3', $selectfamilia, 'en maquina');	}
	public function terminados($selectfamilia = 0) { $this->todos('estado=','4', $selectfamilia, 'terminados'); }
	public function entregados($selectfamilia)	{	$this->todos('estado=','5', $selectfamilia, 'entregados');	}
	public function todoslospartes()			{	$this->todos('estado<','7', '0', '(todos)');	}
	
	/* Comienza la función todos que cambian según el estado que se le pasa */	
	public function todos( $coge_estado, $where_estado, $selectfamilia, $situacion){

	//Control de login	
		helper('controlacceso');
		$nivel=control_login();
		//Fin Control de Login	
				
	// Comienza Grocery CRUD a montar la tabla	
	$crud = $this->_getClientDatabase();
// Definimos el número de columnas

	$crud->columns(['fecha_entrega','id_pedido','n_piezas','id_producto','nom_base','med_inicial','med_final','estado']);
    $crud->setTable('linea_pedidos');
	$crud->where ( $coge_estado . $where_estado);
	
	//Definimos el título de la tabla
	//Sacamos la fecha de hoy
	$ahora= date('d-m-y');
	//Cargo el modelo donde sacamos el nombre de la familia a través de su id $selectfamilia

if ($selectfamilia != '0') {
    $this->Menu_familias_model = model('App\Models\Menu_familias_model');
    $nombre_familia = $this->Menu_familias_model->familia_nombre($selectfamilia);
    // Creo una variable para definir el título de la página
    $titulo_pagina = $nombre_familia . " " . $situacion . " - fecha: " . $ahora;
} else {
    $titulo_pagina = "Partes " . $situacion . " - fecha: " . $ahora;
}

	$crud->setSubject($titulo_pagina, $titulo_pagina);
	
	// Hacemos que la columna id_producto muestre el nombre de la familia
	
	if ($selectfamilia!='0'){
		$crud->setRelation('id_producto','productos','nombre_producto',['id_familia'=> $selectfamilia]);
	}
	else{
		$crud->setRelation('id_producto','productos','nombre_producto');
	}
	if ($where_estado=='0'){
		$crud->setActionButton('Parte', 'fa fa-print', function ($row) {
			$uri = service('uri');
			$uri = current_url(true);
			$pg2=$uri;
			return base_url('partes/print/').'/'.$row->id_lineapedido.'?volver='.$pg2;
		}, false);
	}
	//Si el estado es 2, en marcha le permito pasar la línea a máquina
	
	if ($where_estado=='2'){
		$crud->setActionButton('A máquinas', 'fa fa-arrow-right', function ($row) {
			$uri = service('uri');
			$uri = current_url(true);
			$pg2=$uri;
			return base_url('/lista_produccion/actualiza_linea/').'/'.$row->id_lineapedido.'/3/?volver='.$pg2;
		}, false);
	}
	//Si el estado es 3, en maquinas, le permito sacar a la línea de máquina
	
	if ($where_estado=='3'){
		
		$crud->setActionButton('Quitar de máquinas', 'fa fa-arrow-left', function ($row) {
			$uri = service('uri');
			$uri = current_url(true);
			$pg2=$uri;
			return base_url('/lista_produccion/actualiza_linea/').'/'.$row->id_lineapedido.'/2/?volver='.$pg2;
		}, false);
		$crud->setActionButton('Terminar', 'fa fa-check', function ($row) {
			$uri = service('uri');
			$uri = current_url(true);
			$pg2=$uri;
			return base_url('/lista_produccion/actualiza_linea/').'/'.$row->id_lineapedido.'/4/?volver='.$pg2;
		}, false);	
	}
	if ($where_estado=='4'){
		
		$crud->setActionButton('Entregar', 'fa fa-truck', function ($row) {
			$uri = service('uri');
			$uri = current_url(true);
			$pg2=$uri;
			return base_url('/lista_produccion/actualiza_linea/').'/'.$row->id_lineapedido.'/5/?volver='.$pg2;
		}, false);	
	}
	$crud->callbackColumn('estado',array($this,'_cambia_color_lineas'));
	$crud->unsetEdit();
	$crud->unsetDelete();
	$crud->unsetAdd();
	$crud->unsetRead();

	$crud->callbackColumn('id_pedido', array($this,'nombre_cliente'));
	
	
	$output = $crud->render();

	
	if ($output->isJSONResponse) {
		header('Content-Type: application/json; charset=utf-8');
		echo $output->output;
		exit;
		}
		
	echo view('layouts/main',(array)$output);        
	}
	
	function _cambia_color_lineas ($estado){
		$nombre_estado="";
		if ($estado=='0'){ $nombre_estado="0. Pendiente de material";}
		if ($estado=='1'){ $nombre_estado="1. Falta material";}
		if ($estado=='2'){ $nombre_estado="2. Material recibido";}
		if ($estado=='3'){ $nombre_estado="3. En m&aacute;quinas";}
		if ($estado=='4'){ $nombre_estado="4. Terminado";}
		if ($estado=='5'){ $nombre_estado="5. Entregado";}
		return "<div class='estado estado".(($estado)?:'error')."'>$nombre_estado</div>";
	}
	function nombre_cliente ($id_pedido){
		$Pedidos_model = model('App\Models\Pedidos_model');
		$pedido =$Pedidos_model->obtener_datos_pedido($id_pedido);
		foreach ($pedido as $row)
			{
			$cliente=$row->nombre_cliente;
			return "<b><a href=".base_url()."/Pedidos2/enmarcha#/edit/".$id_pedido." target='_blank'>".$id_pedido." - ".$cliente."</a></b>";
			}
	}
	public function actualiza_linea($id_lineapedido,$estado)
	{
	
		$Lineaspedido_model = model('App\Models\Lineaspedido_model');
		$Lineaspedido_model->actualiza_linea($id_lineapedido,$estado);

		if (isset($_GET['volver'])){
			$volver=$_GET['volver'];
		 }
		 helper('url');
		 return redirect()->to($volver); 
	}

}

