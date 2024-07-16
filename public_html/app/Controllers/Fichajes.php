<?php

namespace App\Controllers;
// use App\Libraries\GroceryCrud;

class Fichajes extends BaseControllerGC
{   

 public function index()
{
	//Control de login	
	helper('controlacceso');
	$nivel=control_login();
	//Fin Control de Login
$this->SacaFichajes('Fichaje',
					'Fichajes',
					'',
					'');
}
 public function incidencias()
{
$this->SacaFichajes('Incidencia',
					'Incidencias',
					'fichajes.incidencia NOT LIKE ?',
					'');
}

 public function extras()
{
$this->SacaFichajes('Extra',
					'Extras',
					'fichajes.extras',
					'1');
}
public function SacaFichajes($titulo,$titulos,$comparador,$valor)
{

    $crud = $this->_getClientDatabase();

$crud->setSubject($titulo,$titulos);
$crud->setTable('fichajes');
$crud->columns(['id_usuario','entrada','salida', 'total', 'incidencia','extras']);
$this->groceryCRUDAddExtraColumn($crud, 'fecha');
if ($comparador!=''){
$crud->where([
	$comparador => $valor
]);
}
$crud->setRelation('id_usuario','users','nombre_usuario');
$crud->displayAs('total','Horas');
$crud->displayAs('id_usuario','Nombre');
//$crud->displayAs('id','Fecha');
$crud->fieldType('extras', 'dropdown', [
	'1' => 'Sí',
	'0' => ' '
]);
$crud->fieldType('incidencia', 'dropdown', [
	' '	=> '--',
	'no8' => 'No 8 horas',
	'sin cerrar' => 'Sin cerrar',
	'Ausencia' => 'Ausencia'
]);
$crud->callbackColumn('total',array($this,'Pasa_a_Horas'));
$crud->unsetRead();
$crud->unsetSearchColumns(['entrada', 'salida','total']);
$crud->setLangString('modal_save', 'Guardar Fichaje');
$output = $crud->render();

	if ($output->isJSONResponse) {
		header('Content-Type: application/json; charset=utf-8');
		echo $output->output;
		exit;
	}
//Paso las fechas de búsqueda
// $output->data['inicio'] = "01/01/2022";
// $output->data['fin'] = "02/01/2022";

echo view('layouts/main', (array)$output);  
}

function groceryCRUDAddExtraColumn($crud, $columnName) {
	$crud->fieldTypeColumn($columnName, 'varchar');
	$crud->mapColumn($columnName, 'id');
}



function Pasa_a_Horas ($total){
	$totalhoras = intval($total / 60);
	$minutos = $total % 60;
	if ($totalhoras == 0){
		return $minutos . " minutos";
	} else {
		return $totalhoras . " horas y " . $minutos . " minutos";
	}
}
}

