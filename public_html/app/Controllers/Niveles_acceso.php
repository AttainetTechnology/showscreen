<?php
namespace App\Controllers;

class Niveles_acceso extends BaseControllerGC
{
    public function index()
    {
        $crud = $this->_getClientDatabase();
        $crud->setTable('niveles_acceso');
        $crud->setSubject('Niveles de acceso', 'Niveles de acceso');
        $crud->requiredFields(['id_nivel', 'nombre_nivel']);
        $crud->editFields(['id_nivel','nombre_nivel']);
        $crud->addFields(['id_nivel','nombre_nivel']);
        $crud->columns(['id_nivel','nombre_nivel']);
        $output = $crud->render();
        return $this->_GC_output('layouts/main', $output); 
    }
}