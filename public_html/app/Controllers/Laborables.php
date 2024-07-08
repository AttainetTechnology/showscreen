<?php

namespace App\Controllers;
// use App\Libraries\GroceryCrud;
use CodeIgniter\Model;
setlocale(LC_ALL, 'spanish');
class Laborables extends BaseControllerGC
{
	
public function index()
{
//Control de login	
helper('controlacceso');
$nivel=control_login();
//Fin Control de Log

$crud = $this->_getClientDatabase();
$crud->setLanguage('Spanish');
$crud->setSubject('Laborables', 'Laborables');
$crud->setTable('laborables');
$crud->unsetOperations();
$crud->setEdit();
$crud->setLangString('modal_save', 'Guardar laborable');
$crud->fieldType('lunes', 		'dropdown', [
	'1' => 'Laborable',
	'' => 'No laborable'
]);
$crud->fieldType('martes', 		'dropdown', [
	'2' => 'Laborable',
	'' => 'No laborable'
]);
$crud->fieldType('miercoles', 	'dropdown', [
	'3' => 'Laborable',
	'' => 'No laborable'
]);
$crud->fieldType('jueves', 		'dropdown', [
	'4' => 'Laborable',
	'' => 'No laborable'
]);
$crud->fieldType('viernes', 	'dropdown', [
	'5' => 'Laborable',
	'' => 'No laborable'
]);
$crud->fieldType('sabado', 		'dropdown', [
	'6' => 'Laborable',
	'' => 'No laborable'
]);
$crud->fieldType('domingo', 	'dropdown', [
	'7' => 'Laborable',
	'' => 'No laborable'
]);
$crud->Where('id',1);
$output = $crud->render();

if ($output->isJSONResponse) {
	header('Content-Type: application/json; charset=utf-8');
	echo $output->output;
	exit;
}
echo view('layouts/main', (array)$output);  

}

}