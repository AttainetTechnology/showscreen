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

        $previous_proceso_id = $this->getPreviousProceso($primaryKey);
        $next_proceso_id = $this->getNextProceso($primaryKey);

        if ($this->request->is('post')) {
            $nombre_proceso = $this->request->getPost('nombre_proceso');
            $estado_proceso = $this->request->getPost('estado_proceso');
            $restricciones = $this->request->getPost('restricciones');
            $redirect_url = $this->request->getPost('redirect_url');

            $restricciones_string = $restricciones ? implode(',', $restricciones) : '';
            $procesoModel->update($primaryKey, [
                'nombre_proceso' => $nombre_proceso,
                'estado_proceso' => $estado_proceso,
                'restriccion' => $restricciones_string
            ]);

            // Redirigimos a la URL capturada
            return redirect()->to($redirect_url);
        }

        $data = [
            'proceso_principal' => $proceso_principal,
            'procesos' => $procesos,
            'primaryKey' => $primaryKey,
            'previous_proceso_id' => $previous_proceso_id,
            'next_proceso_id' => $next_proceso_id
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
    public function getPreviousProceso($id)
    {
        $data = datos_user();
        $db = db_connect($data['new_db']);
        $procesoModel = new Proceso($db);
        // Obtener el proceso anterior
        $proceso = $procesoModel->where('id_proceso <', $id)->orderBy('id_proceso', 'DESC')->first();
        if ($proceso) {
            return $proceso['id_proceso'];
        } else {
            return $id;
        }
    }

    public function getNextProceso($id)
    {
        $data = datos_user();
        $db = db_connect($data['new_db']);
        $procesoModel = new Proceso($db);
        // Obtener el siguiente proceso
        $proceso = $procesoModel->where('id_proceso >', $id)->orderBy('id_proceso', 'ASC')->first();
        if ($proceso) {
            return $proceso['id_proceso'];
        } else {
            return $id;
        }
    }
}
