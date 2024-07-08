<?php

namespace App\Controllers;
// use App\Libraries\GroceryCrud;
use CodeIgniter\Model;

class Informes extends BaseControllerGC
{
    
public function index()
{
//Control de login	
helper('controlacceso');
$nivel=control_login();
//Fin Control de Login

$crud = $this->_getClientDatabase();
$crud->setSubject('Informe', 'Informes');
$crud->setTable('informes');
$crud->requiredFields(['titulo','desde','hasta']);
$crud->columns(['titulo','desde','hasta']);
$crud->unsetRead();
$crud->setLangString('modal_save', 'Guardar Informe');
$crud->fieldType('extras', 'dropdown', [
	'0' => 'No',
	'1' => 'Sí',
]);
$crud->fieldType('vacaciones', 'dropdown', [
	'0' => 'No',
	'1' => 'Sí',
]);
$crud->fieldType('incidencias', 'dropdown', [
	'0' => 'No',
	'1' => 'Sí',
]);
$crud->fieldType('ausencias', 'dropdown', [
	'0' => 'No',
	'1' => 'Sí',
]);
$crud->setActionButton('Abrir', 'fa fa-doc', function ($row) {
	return base_url().'/informe_detalle/' . $row->id_informe;
}, true);
$crud->unsetSearchColumns(['vacaciones','incidencias','extras','desde','hasta','ausencias']);
$output = $crud->render();
if ($output->isJSONResponse) {
	header('Content-Type: application/json; charset=utf-8');
	echo $output->output;
	exit;
}
echo view('layouts/main', (array)$output);  

}

}

