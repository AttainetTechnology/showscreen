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

            // Llamar a la función para actualizar la tabla de procesos_productos
            $this->updateOrderAfterRestrictionChange($primaryKey, $restricciones_string);

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

    private function updateOrderAfterRestrictionChange($id_proceso, $restricciones)
    {
        // Aquí debes integrar la lógica para actualizar la tabla procesos_productos
        $data = datos_user();
        $dbClient = db_connect($data['new_db']);

        // Obtén los productos asociados al proceso modificado
        $productos = $dbClient->table('procesos_productos')->where('id_proceso', $id_proceso)->get()->getResultArray();

        foreach ($productos as $producto) {
            $id_producto = $producto['id_producto'];

            // Llama a la función de actualización
            $this->updateOrderLogic($dbClient, $id_producto);
        }
    }

    private function updateOrderLogic($dbClient, $id_producto)
    {
        $newOrder = $dbClient->table('procesos_productos')->where('id_producto', $id_producto)->orderBy('orden', 'ASC')->get()->getResultArray();
        $dbClient->table('procesos_productos')->where('id_producto', $id_producto)->delete();

        foreach ($newOrder as $item) {
            $id_proceso = $item['id_proceso'];
            $orden = $item['orden'];

            $proceso = $dbClient->table('procesos')->where('id_proceso', $id_proceso)->get()->getRow();
            $restricciones = $proceso->restriccion;

            if (!empty($restricciones)) {
                $restriccionesArray = explode(',', $restricciones);
                $builder = $dbClient->table('procesos_productos');
                $builder->select('id_proceso');
                $builder->where('id_producto', $id_producto);
                $query = $builder->get();
                $procesosProducto = $query->getResultArray();
                $procesosProductoIds = array_column($procesosProducto, 'id_proceso');
                $restriccionesFiltradas = array_intersect($restriccionesArray, $procesosProductoIds);
                $restriccionesFiltradasString = implode(',', $restriccionesFiltradas);
            } else {
                $restriccionesFiltradasString = '';
            }
            $dbClient->table('procesos_productos')->insert([
                'id_producto' => $id_producto,
                'id_proceso' => $id_proceso,
                'orden' => $orden,
                'restriccion' => $restriccionesFiltradasString
            ]);
        }
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
        $proceso = $procesoModel->where('id_proceso >', $id)->orderBy('id_proceso', 'ASC')->first();
        if ($proceso) {
            return $proceso['id_proceso'];
        } else {
            return $id;
        }
    }
}
