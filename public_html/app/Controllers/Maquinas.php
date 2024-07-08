<?php

namespace App\Controllers;



class Maquinas extends BaseControllerGC
{

public function index()
{

$crud = $this->_getClientDatabase();

$crud->setSubject('Maquina', 'Máquinas');
$crud->setTable('maquinas');
$crud->requiredFields(['nombre']);
$crud->unsetRead();
$crud->setLangString('modal_save', 'Crear Máquina');

// Callbacks para registrar las acciones realizadas en LOG
$crud->callbackAfterInsert(function ($stateParameters) {
    $this->logAction('Máquinas', 'Añade máquina', $stateParameters);
    return $stateParameters;
});
$crud->callbackAfterUpdate(function ($stateParameters) {
    $this->logAction('Máquinas', 'Edita máquina, ID: ' . $stateParameters->primaryKeyValue, $stateParameters);
    return $stateParameters;
});
$crud->callbackAfterDelete(function ($stateParameters) {
    $this->logAction('Máquinas', 'Elimina máquina, ID: ' . $stateParameters->primaryKeyValue, $stateParameters);
    return $stateParameters;
});


$output = $crud->render();



return $this->_GC_output("layouts/main", $output);

}

}

