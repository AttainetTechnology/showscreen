<?php

namespace App\Models;

use CodeIgniter\Model;

class Menu_familias_model extends Model
{
    public function index() {
	
    }
//Devuelve un listado de todas las familias de productos y las carga con la vista Menú
Public function familias_listado(){
    
	helper('controlacceso');
    $data=datos_user();     
    $db = db_connect($data['new_db']);
	$builder = $db->table('familia_productos');
	
	$builder->select('*');
    $builder->orderby("orden", "asc");
    $builder->where('en_menu', '1');
    $builder->select('nombre', 'id_familia');
    
    $query = $builder->get();
	
    $data['familia']=$query->getResult();
    echo view('menu.php', $data);
}
//Devuelve un listado de todas las familias de productos para crear estadísticas
Public function familias_estadistica(){		
    $data = array();
	
	helper('controlacceso');
    $data=datos_user();     
    $db = db_connect($data['new_db']);
	$builder = $db->table('familia_productos');
	
	$builder->select('*');
    $builder->orderby("orden", "asc");
    $builder->where('en_menu', '1');
    $builder->select('nombre', 'id_familia');
    
    $query =$builder->get();
    return $query->getResult();

}

//Devuelve el nombre de la familia por su id

    Public function familia_nombre($id_familia){
    
    $data = array();
	helper('controlacceso');
    $data=datos_user();     
    $db = db_connect($data['new_db']);
    
	$builder = $db->table('familia_productos');
    $builder->where('id_familia', $id_familia);
    $builder->limit('1');
    $builder->select('nombre');
    $query = $builder->get();
    $nombre = $query->getResult();

    if (!empty($nombre)) {
        return $nombre[0]->nombre;
    } else {
        return null;
    }
}

    //Devuelve el nombre de la familia por la id del producto

    Public function familia_nombre_producto($id_producto){
    
    $data = array();
	
	helper('controlacceso');
    $data=datos_user();     
    $db = db_connect($data['new_db']);
	$builder = $db->table('productos');
	
	$builder->select('id_familia');
    $builder->where('id_producto', $id_producto);

    $query = $builder->get();
    return $query->getResult();
  }
}


