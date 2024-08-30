<?php
namespace App\Models;

use CodeIgniter\Model;

class ProcesosProductos extends Model {
    protected $table = 'procesos_productos';
    protected $primaryKey = 'id_relacion';

    protected $allowedFields = ['id_producto', 'id_proceso', 'orden'];

    public function __construct($db = null, $validation = null) {
        parent::__construct($db, $validation);
    }

    public function obtenerProcesosProductos() {
        return $this->findAll();
    }
}