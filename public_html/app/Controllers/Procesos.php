<?php

namespace App\Controllers;

class Procesos extends BaseControllerGC
{
 public function index()
{
    $crud = $this->_getClientDatabase();

$crud->setSubject('Proceso','Procesos');
$crud->setTable('procesos');
$crud->columns(['nombre_proceso']);
$crud->fields(['nombre_proceso', 'estado_proceso']); 
$crud->fieldType('estado_proceso', 'dropdown', ['1' => 'Activo', '0' => 'Inactivo']); // Define el campo como un desplegable con las opciones 1 y 0
$crud->setRule('estado_proceso', 'Estado del Proceso', 'required|in_list[1,0]'); // Asegura que solo se acepten los valores 1 y 0
$crud->requiredFields (['nombre_proceso']);
$crud->unsetRead();
$crud->setLangString('modal_save', 'Guardar Proceso');

// Callbacks para registrar las acciones realizadas en LOG
$crud->callbackAfterInsert(function ($stateParameters) {
    $this->logAction('Procesos', 'Añade proceso', $stateParameters);
    return $stateParameters;
});
$crud->callbackAfterUpdate(function ($stateParameters) {
    $this->logAction('Procesos', 'Edita proceso, ID: ' . $stateParameters->primaryKeyValue, $stateParameters);
    return $stateParameters;
});
$crud->callbackAfterDelete(function ($stateParameters) {
    $this->logAction('Procesos', 'Elimina proceso, ID: ' . $stateParameters->primaryKeyValue, $stateParameters);
    return $stateParameters;
});

$output = $crud->render();


return $this->_GC_output("layouts/main", $output);   

}

}

