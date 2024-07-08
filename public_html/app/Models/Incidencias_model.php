<?php

namespace App\Models;

use CodeIgniter\Model;

class Incidencias_model extends Model
{
    public function index() {		
        $data = array();
		$db      = \Config\Database::connect();
		$builder = $db->table('fichajes');
		$builder->select('fichajes.id, entrada, incidencia, id_usuario, extras, nombre_usuario');
		$builder->join('users', 'users.id = fichajes.id_usuario','right outer');
		$condicion="(fichajes.incidencia!='') OR (fichajes.extras!='')";
		$builder->where($condicion);
		$builder->orderby("fichajes.entrada", "desc");	
		$query = $builder->get();
		$data['incidencias']=$query->getResult();
		echo view('incidencias_home', $data);
    }

}


