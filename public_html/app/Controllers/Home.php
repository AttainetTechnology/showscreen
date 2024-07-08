<?php

namespace App\Controllers;

class Home extends BaseController
{
//     function index()
//     {
//    // $validation =  \Config\Services::validation();
//    $session = session();
//     if(!empty($session->get('logged_in')))
//     {
//         $session_data = $session->get('logged_in');
//         $data['username'] = $session_data['username'];
//         $nivel = $session_data['nivel_acceso'];
//             if ($nivel<'2') 
//                 {
//                 //echo "Este es el nivel". $nivel;
//                 //return redirect()->to('Rutas_transporte/rutas');
//                 header('Location: '.base_url().'/Rutas_transporte/rutas');
//                 exit;
//                 } else {
// 				   header('Location: '.base_url().'/Welcome');
//                exit;
//                 }
//     }
//     else
//     {
//         //If no session, redirect to login page
//         header('Location: '.base_url().'/login');
//         exit;
//     }
//     }
    
    function logout()
    {
    $session = session();
    $session->removeTempdata('logged_in');
    $session->destroy();
    //If no session, redirect to login page
      header('Location: '.base_url());
      exit;
    }
}

