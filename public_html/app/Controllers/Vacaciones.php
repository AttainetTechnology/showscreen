<?php
namespace App\Controllers;

class Vacaciones extends BaseControllerGC
{
    public function index()
    {
        $crud = $this->_getClientDatabase();
        $crud->setTable('vacaciones');
        $crud->setSubject('Vacaciones', 'Vacaciones');
       // $crud->fieldType('user_id', 'dropdown_search');
	    //$crud->displayAs('user_id','Nombre');
        $crud->setRelation('user_id', 'users', 'nombre_usuario', ['user_activo' => '1']); 
	    $crud->unsetSearchColumns(['desde', 'hasta','observaciones']);
	    $crud->setLangString('modal_save', 'Guardar Vacaciones');
        $crud->unsetRead();
        $output = $crud->render();
        return $this->_GC_output('layouts/main', $output); 
    }
    public function nombre_usuario($id_user)
	{
		$nombre_usuario = model('Usuarios_model', true, $db);
		//$nombre_usuario = new Usuarios_model();
		$nombre=$nombre_usuario->where('id', $id_user)
				->findAll();
		return $nombre[0]['nombre_usuario'];
		
	}
	function usuarios_activos(){
	 $datos = model('Usuarios_model', true, $db);
	 helper('controlacceso');
	 $data=usuario_sesion();
	 $id_empresa=$data['id_empresa'];
	 $array = ['user_activo' => '1', 'id_empresa' => $id_empresa];
	 $users= $datos->where($array)->findAll();
	 $users_activos= array();
		if ($users){
			   foreach ($users as $row) {
			   $users_activos+=  array($row['id'] => $row['nombre_usuario']." ".$row['apellidos_usuario']);
			}
		  return $users_activos;
		}
	}
}