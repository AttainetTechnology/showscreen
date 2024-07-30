<?php

namespace App\Models;

use CodeIgniter\Model;

class Incidencias_model extends Model
{
    protected $table = 'fichajes';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'entrada',
        'salida',
        'incidencia',
        'justificacion',
        'id_usuario',
        'extras'
    ];

    public function index() {		
        $data = array();
		$db      = \Config\Database::connect();
		$builder = $db->table($this->table);
		$builder->select('fichajes.id, entrada, incidencia, justificacion, id_usuario, extras, nombre_usuario');
		$builder->join('users', 'users.id = fichajes.id_usuario', 'right outer');
		$condicion = "(fichajes.incidencia != '') OR (fichajes.extras != '')";
		$builder->where($condicion);
		$builder->orderby("fichajes.entrada", "desc");	
		$query = $builder->get();
		$data['incidencias'] = $query->getResult();
		echo view('incidencias_home', $data);
    }
}
