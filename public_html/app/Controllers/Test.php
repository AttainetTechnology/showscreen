<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class Test extends BaseFichar
{
    public function index()
    {
        return "Este es el controlador de prueba.";
    }

    public function otraFuncion()
    {
        return "Otra función en el controlador de prueba.";
    }
}
