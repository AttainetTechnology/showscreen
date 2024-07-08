<?php

namespace App\Controllers;

class Pedido_print_controller extends BaseControllerGC
{
    
    function __construct() 
    { 
                $validation =  \Config\Services::validation();
                $session = session();
                if(empty($session->get('logged_in')))
               { 
                   return redirect()->to('Login');
               }
        $database=\Config\Services::database();
    } 

   Public function pedido_print($id_pedido)
   {
       
       //Saco los detalles del pedido

        $Pedidos_model = model('App\Models\Pedidos_model');
       $data['pedido'] = $Pedidos_model->obtener_datos_pedido($id_pedido);
        $Lineaspedido_model = model('App\Models\Lineaspedido_model');
       $data['lineas'] = $Lineaspedido_model->obtener_lineas_pedido($id_pedido);
       
       echo view('header_partes');
       echo view('pedidos',(array)$data);
       echo view('footer');

   }

}

