<?Php 
namespace App\Models;

use CodeIgniter\Model;

class presentes extends Model{

    protected $table = 'fichajes-activos';
    protected $primaryKey='id_empleado';
    protected $allowedFields= ['id_empleado', 'entrada', 'extras'];
    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;
}