<?php

namespace App\Controllers;

class Contactos extends BaseControllerGC
{
 
    public function index()
    {
        //Control de login	
            helper('controlacceso');
            $nivel=control_login();
        //Fin Control de Login
        
        $crud = $this->_getClientDatabase();
        $crud->setSubject('Contacto', 'Contactos');
        $crud->setTable('contactos');
        $crud->columns(['nombre','apellidos','telf','id_cliente','cargo']);
        $crud->displayAs('id_cliente','Empresa');
        $crud->setRelation('id_cliente','clientes','nombre_cliente');
        $crud->requiredFields(['nombre','id_cliente']);
        $crud->unsetRead();
        $crud->setLangString('modal_save', 'Guardar Contacto');

        // Callbacks para registrar las acciones realizadas en LOG
        $crud->callbackAfterInsert(function ($stateParameters)  {
            $this->logAction('Contactos', 'Añade contacto, ID: ' . $stateParameters->insertId , $stateParameters);
            return $stateParameters;
        });
        $crud->callbackAfterUpdate(function ($stateParameters)  {
            $this->logAction('Contactos', 'Edita contacto, ID: ' . $stateParameters->primaryKeyValue , $stateParameters);
            return $stateParameters;
        });
        $crud->callbackAfterDelete(function ($stateParameters)  {
            $this->logAction('Contactos', 'Elimina contacto, ID: ' . $stateParameters->primaryKeyValue , $stateParameters);
            return $stateParameters;
        });

            $output = $crud->render();
            return $this->_GC_output('layouts/main', $output);
        }

}

