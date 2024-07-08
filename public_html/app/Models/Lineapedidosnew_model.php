<?
	namespace App\Models;
	
	use CodeIgniter\Model;
	
	class Lineapedidosnew_model extends Model{
		
		protected $table = 'linea_pedidos';
		protected $primaryKey='id_lineapedido';
		protected $allowedFields= ['id_pedido'];
		protected $validationRules    = [];
		protected $validationMessages = [];
		protected $skipValidation     = false;
	
		}
