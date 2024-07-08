<?php
namespace App\Controllers;

class Log extends BaseControllerGC
{
    public function index()
    {
        $crud = $this->_getClientDatabase();
        $crud->setTable('log');
        $crud->setSubject('Logs', 'Logs');
        $crud->displayAs('id_usuario', 'Nombre Usuario');
        //No se pueden editar 
        $crud->unsetEdit();
        $crud->callbackAfterDelete(function ($stateParameters) {
            $this->logAction('LOG', 'Elimina LOG', $stateParameters);
            return $stateParameters;
        });
        $output = $crud->render();
        return $this->_GC_output('layouts/main', $output); 
    }
}