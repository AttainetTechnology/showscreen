<?php
namespace App\Models;

use CodeIgniter\Model;

class NivelAccesoModel extends Model
{
    protected $table = 'niveles_acceso';
    protected $primaryKey = 'id_nivel';
    protected $allowedFields = ['nombre_nivel'];
}


