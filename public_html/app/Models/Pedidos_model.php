<?php

namespace App\Models;

use CodeIgniter\Model;

class Pedidos_model extends Model
{
    public function obtener_datos_pedido($id_pedido)
    {
        helper('controlacceso');
        $data = datos_user();     
        $db = db_connect($data['new_db']);
        
        if (!$db->connID) {
            // Conexión fallida
            throw new \Exception('Conexión a la base de datos fallida: ' . $db->error());
        }

        $builder = $db->table('pedidos');
        $builder->select('*');
        $builder->join('clientes', 'clientes.id_cliente = pedidos.id_cliente', 'left');
        $builder->where('id_pedido', $id_pedido);
        $query = $builder->get();

        if (!$query) {
            // Consulta fallida
            throw new \Exception('Consulta fallida: ' . $db->getLastQuery() . ' - Error: ' . $db->error());
        }

        return $query->getResult();
    }
}
