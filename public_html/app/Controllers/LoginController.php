<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class LoginController extends Controller
{
    protected $request;
    protected $helpers = [];

    // Declara explícitamente las propiedades
    protected $session;
    protected $database;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        // Inicializa las propiedades aquí
        $this->session = \Config\Services::session();
        $this->database = \Config\Database::connect();
    }


}