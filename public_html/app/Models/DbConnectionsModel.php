<?php namespace App\Models;

use CodeIgniter\Model;

class DbConnectionsModel extends Model
{
    protected $table = 'dbconnections';

    public function getNIF($id_empresa)
    {
        return $this->where('id', $id_empresa)->first()['NIF'];
    }
}