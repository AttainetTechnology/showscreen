<?php

namespace App\Models;
use CodeIgniter\Model;

class Home_model extends Model
{
    protected $table      ='dbconnections';
    protected $primaryKey = 'id';
    protected $allowedFields = ['db_name','db_user','db_password','nombre_empresa'];
}