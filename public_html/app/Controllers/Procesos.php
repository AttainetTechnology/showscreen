<?php
namespace App\Controllers;

use App\Models\Proceso;
use CodeIgniter\Controller;

class Procesos extends BaseControllerGC
{
    public function index()
    {
        $data = datos_user();
        $db = db_connect($data['new_db']);
        $procesoModel = new Proceso($db);
        $procesos = $procesoModel->findAll();
        return view('procesos', ['procesos' => $procesos]);
    }

    public function add()
    {
        return view('add_procesos');
    }

    public function create()
    {
        $data = datos_user();
        $db = db_connect($data['new_db']);
        $procesoModel = new Proceso($db);

        $nombre_proceso = $this->request->getPost('nombre_proceso');
        $estado_proceso = $this->request->getPost('estado_proceso');

        $procesoModel->insert([
            'nombre_proceso' => $nombre_proceso,
            'estado_proceso' => $estado_proceso,
            'restriccion' => null 
        ]);

        return redirect()->to(base_url('procesos'));
    }


    public function restriccion($primaryKey)
    {
        $data = datos_user();
        $db = db_connect($data['new_db']);
        $procesoModel = new Proceso($db);
        $procesos = $procesoModel->where('id_proceso !=', $primaryKey)->findAll();
        $proceso_principal = $procesoModel->find($primaryKey);

        if ($this->request->is('post')) {
            // Obtiene los datos del formulario
            $nombre_proceso = $this->request->getPost('nombre_proceso');
            $estado_proceso = $this->request->getPost('estado_proceso');
            $restricciones = $this->request->getPost('restricciones');

            // Actualiza el proceso en la base de datos
            $restricciones_string = $restricciones ? implode(',', $restricciones) : '';
            $procesoModel->update($primaryKey, [
                'nombre_proceso' => $nombre_proceso,
                'estado_proceso' => $estado_proceso,
                'restriccion' => $restricciones_string
            ]);

            // Redirige a la lista de procesos
            return redirect()->to(base_url('procesos'));
        }
        $data = [
            'proceso_principal' => $proceso_principal,
            'procesos' => $procesos,
            'primaryKey' => $primaryKey
        ];
        return view('edit_procesos', $data);
    }
    public function delete($id)
    {
        $data = datos_user();
        $db = db_connect($data['new_db']);
        $procesoModel = new Proceso($db);
        $procesoModel->delete($id);
        return redirect()->to(base_url('procesos'));
    }
}
