<?php

namespace App\Models;
use CodeIgniter\Model;

class Usuarios1_Model extends Model
{
    protected $table      ='users';
    protected $primaryKey = 'id';
    protected $allowedFields = ['email','username','password','nivel_acceso','id_empresa'];


    public function get_nombre_usuario($id)
    {  
        return $this->where('id', $id)->first()['nombre_usuario'];
  
    }

}


