<?Php 
namespace App\Models;

use CodeIgniter\Model;

class hoy extends Model{
    protected $table = 'hoy';
    protected $primaryKey='id';
    protected $allowedFields= ['id','hoy'];
    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;
}