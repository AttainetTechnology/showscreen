<?php
namespace App\Controllers;

use App\Models\Proceso; // Asegúrate de que este modelo exista
use CodeIgniter\Controller;

class Procesos extends BaseControllerGC
{
    public function index()
    {
        $data = datos_user();
        $db = db_connect($data['new_db']);
        $procesoModel = new Proceso($db);

        $crud = $this->_getClientDatabase();
        $crud->setSubject('Proceso', 'Procesos');
        $crud->setTable('procesos');
        $crud->columns(['nombre_proceso']);
        $crud->fields(['nombre_proceso', 'estado_proceso']);
        $crud->fieldType('estado_proceso', 'dropdown', ['1' => 'Activo', '0' => 'Inactivo']);
        $crud->requiredFields(['nombre_proceso']);
        $crud->unsetRead();
        $crud->setLangString('modal_save', 'Guardar Proceso');

        $crud->callbackColumn('nombre_proceso', function ($value, $row) {
            $button = '<a href="' . base_url('procesos/restriccion/' . $row->id_proceso) . '" class="btn btn-warning" style="float: right;">Restricción</a>';
            return '<div style="display: flex; justify-content: space-between; align-items: center;">' . $value . $button . '</div>';
        });

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

    public function restriccion($primaryKey)
    {
        $data = datos_user();
        $db = db_connect($data['new_db']);
        $procesoModel = new Proceso($db);
        $procesos = $procesoModel->where('id_proceso !=', $primaryKey)->findAll();
        $proceso_principal = $procesoModel->find($primaryKey);

        $data = [
            'proceso_principal' => $proceso_principal,
            'procesos' => $procesos,
            'primaryKey' => $primaryKey
        ];

        return view('edit_procesos', $data);
    }

    public function guardarRestriccion()
    {
        $data = datos_user();
        $db = db_connect($data['new_db']);
        $procesoModel = new Proceso($db);

        $primaryKey = $this->request->getPost('primaryKey');
        $restricciones = $this->request->getPost('restricciones');

        $restricciones_string = implode(',', $restricciones);

        $procesoModel->update($primaryKey, ['restriccion' => $restricciones_string]);

        return redirect()->to(base_url('procesos'));
    }
}
