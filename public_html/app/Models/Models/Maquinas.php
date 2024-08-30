<?Php 
namespace App\Models;

use CodeIgniter\Model;

class maquinas extends Model{
    protected $table = 'maquinas';
    protected $primaryKey='id_maquina';
    protected $allowedFields= ['id_maquina','nombre'];
    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;
}