<?php

namespace App\Controllers;
// use App\Libraries\GroceryCrud;

class Menu extends BaseControllerGC
{
public function index()
{
	//Control de login	
	$nivel=control_login();
	//Fin Control de Login
	//Si el usuario no tiene permisos lo tiro a la home
	if ($nivel<'9'){
	header('Location: '.base_url());
	exit(); 
	} else {
		 $crud = $this->_getClientDatabase();
	
		$crud->setSubject('Menú de la aplicación','Menú');
		$crud->setTable('menu');
		$crud->columns(['posicion','titulo','enlace','nivel','activo']);
		$crud->requiredFields(['posicion','titulo','nivel','activo']);
		$crud->editFields(['posicion','titulo','enlace','nivel','activo','estilo','url_especial', 'separador','nueva_pestana']);
		$crud->setRelation('nivel','niveles_acceso','nombre_nivel');
		$crud->setActionButton('Submenú', 'fa fa-list', function ($row) {
			return base_url('/Submenus/')."/".$row->id."?titulo_submenu=".$row->titulo;
		}, false);
		$crud->where([
			'dependencia' => '0'
		]);
		$crud->fieldType('nueva_pestana', 'dropdown_search', [
		'0' => 'No',
		'1' => 'Sí'
		]);
		$crud->displayAs('nueva_pestana', 'Abrir en nueva pestaña?');
		$crud->fieldType('activo', 'dropdown_search', [
		'0' => 'Desactivado',
		'1' => 'Activo'
		]);
		$crud->fieldType('url_especial', 'dropdown_search', [
		'0' => 'No, url genérica.',
		'1' => 'Sí, url personalizada.'
		]);
		$crud->fieldType('separador', 'dropdown_search', [
		'' => 'Ninguno',
		'arriba' => 'Arriba',
		'abajo' => 'Abajo'
		]);
		$crud->setLangString('modal_save', 'Guardar');

		// Callbacks tabla LOG
		$crud->callbackAfterInsert(function ($stateParameters) {
			$this->logAction('Menu', 'Añadir menu', $stateParameters);
			return $stateParameters;
		});
		$crud->callbackAfterUpdate(function ($stateParameters) {
			$this->logAction('Menu', 'Editar menu ' , $stateParameters);
			return $stateParameters;
		});
		$crud->callbackAfterDelete(function ($stateParameters) {
			$this->logAction('Menu', 'Eliminar menu ' , $stateParameters);
			return $stateParameters;
		});
		

		$output = $crud->render();
		if ($output->isJSONResponse) {
			header('Content-Type: application/json; charset=utf-8');
			echo $output->output;
			exit;
		}	
	
	echo view('layouts/main',(array)$output);
	}
}
	
}

