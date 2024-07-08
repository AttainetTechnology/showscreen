<?php
/** JLGB: Extensión de BaseController para encapsular inicialización de Grocerycrud versión Premium*/
namespace App\Controllers;

use App\Models\MenuModel;
use App\Models\Usuarios2_Model;
use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use Config\GroceryCrudEnterprise as ConfigGroceryCrud;
use GroceryCrud\Core\GroceryCrud;
use Config\Database as ConfigDatabase;

class CrudAcceso extends BaseController{
    
    protected function _getDbData() {
        $db = (new \Config\Database())->default;
        return [
        'adapter' => [
        'driver' => 'Pdo_Mysql',
        'host'     => $db['hostname'],
        'database' => $db['database'],
        'username' => $db['username'],
        'password' => $db['password'],
        'charset' => 'utf8'
        ]
        ];
        }

protected function _getGroceryCrudEnterprise($bootstrap = true, $jquery = true) {
    $db = $this->_getDbData();
    $config = (new \Config\GroceryCrudEnterprise())->getDefaultConfig();
    $groceryCrud = new \GroceryCrud\Core\GroceryCrud($config, $db);

    return $groceryCrud;
}

}