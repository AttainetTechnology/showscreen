<?php

namespace App\Models;
use CodeIgniter\Model;

class Contactos extends Model
{
    protected $table      ='contactos';
    protected $primaryKey = 'id_contacto';
    protected $allowedFields = ['nombre','apellidos'];
    
}


