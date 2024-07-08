<?php

namespace App\Models;
use CodeIgniter\Model;


class Lineaspedido_model extends Model
{
    public function index() {}

    // Función para obtener las líneas de un pedido concreto 
    
        public function obtener_lineas_pedido($id_pedido)
            {
                //Conecto la BDD
                    $data=datos_user(); 
                    $db = db_connect($data['new_db']);
                    $builder = $db->table('linea_pedidos');
                    $builder->select('*');
                    $builder->join('Productos', 'Productos.id_producto = Linea_pedidos.id_producto','left');
                    $builder->where('id_pedido', $id_pedido);
                    $query = $builder->get();
                    return $query->getResult();
            }
    
    // Función que obtiene líneas de pedido en un estado concreto
      
        public function obtener_lineas_estado ($estado)
            {
                    $data=datos_user(); 
                    $db = db_connect($data['new_db']);
                    $builder = $db->table('linea_pedidos');
                    $builder->select('*');
                    $builder->join('Productos', 'Productos.id_producto = Linea_pedidos.id_producto','left');
                    $builder->join('Pedidos', 'Pedidos.id_pedido = Linea_pedidos.id_pedido','left');
                    $builder->where('estado', $estado);
                    $query = $builder->get();
                    return $query->getResult();
            }
    
    
    // Función que actualiza el estado de líneas de pedido a través de su id lineapedido
    
            public function actualiza_estado_lineas ($id_lineapedido)
            {
                $data=datos_user(); 
                $db = db_connect($data['new_db']);
                $builder = $db->table('linea_pedidos');
                $builder->select('*');
                $builder->where('id_lineapedido', $id_lineapedido);
                $query = $builder->get();

                foreach ($query->getResult() as $row)
                {
                    
                    // Obtenemos la id_pedido de una línea de pedido concreta 
                    
                    $elpedido = $row->id_pedido;
                    
                    // Obtenemos query2 que nos devuelve todas las líneas de un pedido

                    $builder = $db->table('linea_pedidos');
                    $builder->select('*');
                    $builder->where('id_pedido', $elpedido);
                    $query2 = $builder->get();
                
                    /*
                     Creo una variable para definir el estado final del pedido que será 
                     el estado menor del total de líneas del pedido 
                     7 es el mayor de los estados posibles
                     */
                     
                    $estado_menor='7';
                    foreach ($query2->getResult() as $row)
                    {	
                        $estado_actual=$row->estado;
                        if ($estado_actual<=$estado_menor){ 
                            $estado_menor= $estado_actual;
                        } 					
                    }
                }
    
                //Actualizo la línea de pedido y le pongo el estado_total
                    
                $data2 = array('estado' => $estado_menor);
                $builder = $db->table('pedidos');
                $builder->set($data2);
                $builder->where('id_pedido', $elpedido);
                $builder->update();

                return true; 
    }
    
    /* Esta función actualiza el estado del pedido en función del estado de sus líneas pero proviene de la id pedido */
    
            public function actualiza_estado_pedido ($id_pedido)
            {
                        
                // Variables del actualizador de estados
                $estado_menor='7';
                $data=datos_user(); 
                $db = db_connect($data['new_db']);
                $builder = $db->table('linea_pedidos');
                $builder->select('*');
                $builder->where('id_pedido', $id_pedido);
                $query2 = $builder->get();                
                //$query = $this->db->get_where('Linea_pedidos', array('id_pedido' => $id_pedido));
                foreach ($query->result() as $row)
                {
                
                    $estado_actual=$row->estado;
                    if ($estado_actual<=$estado_menor){ 
                        $estado_menor= $estado_actual;
                        } 					
                }
                    //Actualizo la línea de pedido y le pongo el estado_total
                    $data2 = array('estado' => $estado_menor);

                    //$db      = \Config\Database::connect();
                    $builder = $db->table('Pedidos');
                    $builder->set($data2);
                    $builder->where('id_pedido', $id_pedido);
                    $builder->update();
                        
            return true; 
            if($error){ 	
                return FALSE; }
            }
    
                public function entrega_lineas ($id_pedido)
                {
                                //Si se marca el pedido como entregado se entregan todas las lineas
                                $data = array('estado' => '5');	
                                helper('controlacceso');
                                $data2= usuario_sesion(); 
                                $db = db_connect($data2['new_db']);
                                $builder = $db->table('Linea_pedidos');
                                $builder->set($data);
                                $builder->where('id_pedido', $id_pedido);
                                $builder->update();

                                $builder = $db->table('Pedidos');
                                $builder->set($data);
                                $builder->where('id_pedido', $id_pedido);
                                return $builder->update();
                }
    /*
        Función termina_linea: que marca una línea de pedido como terminada 
    */
                public function actualiza_linea ($id_lineapedido,$estado)
                {
                                $data = array('estado' => $estado);
                                helper('controlacceso');
                                $data2= usuario_sesion(); 
                                $db = db_connect($data2['new_db']);
                                $builder = $db->table('Linea_pedidos');
                                $builder->where('id_lineapedido', $id_lineapedido);
                                $builder->update($data);		
                }
    		
                public function anular_lineas ($id_pedido)
                {
                                //Si se marca el pedido como anulado se anulan todas las lineas
                                $data = array('estado' => '6');

                                helper('controlacceso');
                                $data2= usuario_sesion(); 
                                $db = db_connect($data2['new_db']);
                                $builder = $db->table('Linea_pedidos');
                                $builder->set($data);
                                $builder->where('id_pedido', $id_pedido);
                                $builder->update();

                                $builder = $db->table('Pedidos');
                                $builder->set($data);
                                $builder->where('id_pedido', $id_pedido);
                                return $builder->update();
                
                }
}


