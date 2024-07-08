<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\Presentes;
use App\Models\Ausentes;
use App\Models\Maquinas;
use App\Models\Fichajes;
use App\Models\Festivos;
use App\Models\Usuarios_model;
use App\Models\Hoy;
use App\Models\Vacaciones_model;
use CodeIgniter\I18n\Time;
use DateTime;
use CodeIgniter\Model;


class Fichar extends BaseFichar
{
	public function index()
	{
		helper('controlacceso');
		
		/* Comprobamos si hemos cambiado de día y activamos las comprobaciones */
		$this -> CompruebaDia();
	
		/* Listamos todos los fichajes de hoy*/ 
		$presentes = new Presentes($this->db);
		$a1=$presentes
			->orderBy('entrada','ASC')
			->findAll();
		$datos['presentes']=array();
		$i=0;
		// De cada fichaje saco sus datos de usuario y creamos la variable datos[presentes]

		foreach ($a1 as $usera1){
			$a2= model('Usuarios1_Model', true, $this->db)->where('id', $usera1['id_empleado'])->findAll();
			$datos['presentes'][$i] = array_merge($a2[0], $usera1);
			$i+=1;
		}

		$datos['cabecera']= view('template/cabecera');
		$datos['menu']= view('template/menu-navegacion');
		$datos['pie']= view('template/pie');
		$datos['recarga_hora']= view('template/recarga_hora');

		  return view('presentes', $datos);
	}
	
	public function CompruebaDia(){            
		$aviso="";
		$hoy= date('Y-m-d');
		$dia= date('j');
		
		$datos = new Hoy($this->db);
		$diahoy= $datos->where('id',1)->findAll();

		if ($diahoy){
			$fechaguardada=  $diahoy[0]['hoy'];
			if ($hoy==$fechaguardada){
				$aviso.= "Hoy es " .$dia. "</br>";
				$session= session();
				$session->setFlashdata('exito', $aviso);
				} else {
				$aviso.= 'Hemos cambiado de día</br>';
				$this->CerrarFichajesAbiertos($aviso);
				}       
		}
	} 

public function CerrarFichajesAbiertos($aviso){

	// En database connection 

	$presentes = new Presentes($this->db);
  	$hoy= date('Y-m-d');
  	$datos['presentes']=$presentes
	  	->select ('id_empleado,entrada,extras')
	  	->orderBy('entrada','ASC')
	  	->findAll(); 
  	if ($datos){
		  foreach ($datos as $row) {
			  $i=0;
			  foreach ($row as $key) {
			  //print_r($key);
			  $empleado=  $key['id_empleado'];
			  $entrada=   $key['entrada'];
			  $extras=    $key['extras'];
			  $data = [
				  'id_usuario'    => $empleado,
				  'entrada'       => $entrada,
				  'extras'        => $extras,
				  'incidencia'    => 'sin cerrar'
			  ];
			//   $fichajes = model('Fichajes', true, $this->db);
			  $fichajes = new Fichajes($this->db);
			  $fichajes->insert($data);
			  $exito= $fichajes->affectedRows();
				  if($exito>0){
				  $i=$i+1;
				  $presentes->delete($empleado);
				  $session= session();
				  $aviso.= $i ." fichajes cerrados del día anterior.<br>";
				  } else {
				  $session= session();
				  $aviso.="No se han registrado fichajes.<br>";
				  }
			  }
		  }
	  }
  $this->Compruebalaborable($aviso);  
  }

	  /* Comprobamos si ayer fue sábado o domingo */ 
		public function Compruebalaborable($aviso){
		$ayer= date('d.m.Y',strtotime("-1 days"));
		$date = new DateTime($ayer); 
		$day =  $date->format("w");
		if($day == 6 || $day == 0){
		$session= session();
		$aviso.= 'Ayer fue fin de semana, no genero ausencias.</br>';
		$this->CambiaDeDia($aviso);
		}else{
		$session= session();
		$aviso.= 'Ayer fue laborable.</br>';
		$this->Compruebafestivo($aviso);
		}
	  }
	  //Comprobamos si ayer fue festivo
	  public function Compruebafestivo($aviso){
		$ayer= date('Y-m-d', strtotime('-1 days'));

		
		$compruebafestivo = new Festivos($this->db);
		$datos['festivo']=$compruebafestivo->where('fecha', $ayer)->findAll(); 
		if ($datos){
		  if ($datos['festivo']){
			foreach ($datos as $row) {
			  foreach ($row as $key) {
				$fecha=$key['fecha'];  
				if ($ayer==$fecha){
				  $session= session();
				  $aviso.= "Ayer fue festivo: ".$fecha."</br>";
				  $this->CambiaDeDia($aviso);
				} 
			  }
			}
		  } else {
			$session= session();
			$aviso.= "Ayer no fue festivo.<br>";
			$this->ComprobarAusencias($aviso);;
		  }
		} 
	  }
		/* Sacamos todos los users con permiso para fichar */
		public function ComprobarAusencias($aviso){		
			$ausentes = new Ausentes($this->db);
			$fichan['ausentes'] = $ausentes
			->where('user_ficha','1')
			->select ('id')
			->orderBy('id','ASC')
			->findAll(); 
			  foreach ($fichan as $row) {
				foreach ($row as $key) {
				  //Comprobamos los fichajes de ayer
					$empleado= $key['id'];
					$this->CompruebaFichajesAyer ($aviso, $empleado);
				} 
			  }
		  //Cambiamos la fecha de Hoy en la BDD
		  $this->CambiaDeDia($aviso);
	  }  
	 public function CompruebaFichajesAyer ($aviso,$empleado){
    //De cada empleado que ficha miramos si fichó ayer
    // echo "Fichajes del emplado: <b>".$empleado."</b><br>";
    $ayer= date('Y-m-d',strtotime("-1 days"));
    $hoy= date('Y-m-d');

    // Load the Fichajes model
    $fichajes = model('Fichajes' );

    if (isset($fichajes)){
        $fichajesayer['fichajes']=$fichajes
            ->where('id_usuario',$empleado)
            ->where('entrada >',$ayer)
            ->where('entrada <',$hoy)
            ->select ('entrada')
            ->findAll();
		  //->where('entrada',$ayer)
		  ;
			foreach ($fichajesayer as $fila) {
			  foreach ($fila as $clave) {
			  $entrada = $clave['entrada'];
			} 
			if ($entrada!=""){
			  //echo "El usuario".$empleado." fichó. No tengo que hacer nada.<br>";
			} else {
			  //echo "El usuario".$empleado." <b>NO</b> fichó. Compruebo si tenía vacaciones.<br>";
			  //Comprobamos si el usuario tenía vacaciones
			  $this->CompruebaVacaciones($empleado);
			}
		  }
		  $session= session();
		  $session->setFlashdata('exito', $aviso);
		}  
	  }
   public function CompruebaVacaciones($empleado)
	  { 
		$ayer= date('Y-m-d 00:00',strtotime("-1 days"));
		$vacaciones = new Vacaciones_model($this->db);
		$vacacionessayer['vacaciones']=$vacaciones
		->where('user_id',$empleado)
		->where('desde <=',$ayer)
		->where('hasta >=',$ayer)
		->select ('desde')
		->findAll();
		;
		$aviso = '';
		foreach ($vacacionessayer as $fila) {
		  foreach ($fila as $clave) {
		  $desde = $clave['desde'];
		  } 
		  if ($desde!=""){
			echo "El usuario".$empleado." está de vacas.<br>";
		  } else {
			echo "El usuario".$empleado." no fichó, genero Ausencia.<br>";
			$datos = [
				'id_usuario'    => $empleado,
				'entrada'       => $ayer,
				'incidencia'    => 'Ausencia'
			];
			$fichajes = model('Fichajes_model', true,$this->db);
			$fichajes->insert($datos);
			$exito= $fichajes->affectedRows();
			if($exito>0){
				$session= session();
				$aviso.="Genero ausencia para el usuario: ".$empleado."<br>";
			} else
			{
				$session= session();
				$aviso.="Error al generar ausencia para el usuario: ".$empleado."<br>";
			}
		  }
		}
	  }
		public function Sal($id=null)
		{      

 
		  $presentes = model('Presentes', true, $this->db);
		  $data1['presentes']= $presentes->where('id_empleado',$id)->first();

		  $fechaentrada   = array_column  ($data1, 'entrada'); 
		  $fichaextras    = array_column  ($data1, 'extras');
		  $fichajes = model('Fichajes', true, $this->db);

		  $ahora= date('Y-m-d H:i:s');
		  $date1 = date_create_from_format('Y-m-d H:i:s', $fechaentrada[0]);
		  $date2 = date_create_from_format('Y-m-d H:i:s', $ahora);

		  $diff = (array) date_diff($date1, $date2);
		  //print_r($diff);
		  $horas= $diff['h']*60;
		  $minutos= $diff['i'];
		  //Guardamos el total del tiempo en minutos
		  $total=($horas+$minutos);
		  //Si hace 15 minutos menos de las 8 horas
		  if (($total<'465') AND ($fichaextras[0]!='1')){
			  //$incidencia="no8"; //Desactivo la incidencia de no8 hasta arreglarla
			  $incidencia="";
		  } else {
			  $incidencia="";
		  }
		  //echo $total;
		  $data = [
			  'id_usuario' => $id,
			  'entrada' => $fechaentrada,
			  'salida' => $ahora,
			  'incidencia' => $incidencia,
			  'extras' =>$fichaextras,
			  'total' =>$total
				  ];
		  $fichajes->insert($data);
		  $exito= $fichajes->affectedRows();
		  if($exito>0){
			  $presentes->delete($id);
			  $session= session();
			  $resultado="Fichaje de salida realizado correctamente.";
			  $session->setFlashdata('exito', $resultado);
			  return redirect()->to( base_url(). '/Fichar');
		  } else
		  {
			  $resultado="Error al fichar la salida.";
			  $session= session();
			  $session->setFlashdata('error', $resultado);
			  return redirect()->back();
		  } ;
		}
	
	public function CambiaDeDia($aviso)
		{    
		$ahora= date('Y-m-d');   

		$hoy = model('Hoy', true, $this->db);
		$data = [
			'hoy' =>$ahora,
				];
		$hoy->update('1',$data);
		$exito= $hoy->affectedRows();
		$resultado = null; 
		if($exito>0){
			$session= session();
			$aviso.="Hemos Guardado el nuevo día.<br>";
			$session->setFlashdata('exito', $aviso);
			return redirect()->to( base_url(). '/Fichar');
			} else
			{
			$aviso="No se ha guardado el cambio de día.";
			$session= session();
			$session->setFlashdata('error', $resultado);
			return redirect()->back();
			} 
		}
		
	
	public function Ausentes()
	{

		$ausentesModel = new Ausentes($this->db);
		$ausentes = $ausentesModel
			->where('user_ficha','1')
			->where('user_activo','1')
			->orderBy('nombre_usuario','ASC')
			->findAll(); 

		/* Sacamos todos los fichajes activos para quitarlos de la lista de ausentes */
		$presentesModel = model('Presentes', true,$this->db);
		$presentes = $presentesModel->orderBy('id_empleado','ASC')->findAll(); 

		$datos['presentes']=array();
		$i=0;
		// De cada fichaje saco sus datos de usuario y creamos la variable datos[presentes]
		foreach ($presentes as $usera1){
			$a2= model('Usuarios1_Model', true, $this->db)->where('id', $usera1['id_empleado'])->findAll();
			$datos['presentes'][$i] = array_merge($a2[0], $usera1);
			$i+=1;
		}

		/* Plantillas */
		$datos['cabecera']= view('template/cabecera');
		$datos['menu']= view('template/menu-navegacion');
		$datos['pie']= view('template/pie');
		$datos['recarga']= view('template/recarga');
		$datos['ausentes'] = $ausentes; // Add this line
		return view('ausentes', $datos);
	
	}
	
	public function Entrar($id=null)
	{	

	$ausentes = model('Ausentes', true, $this->db);
		$datos['ausentes']= $ausentes->where('id',$id)->first(); 
		$datos['cabecera']= view('template/cabecera');
		$datos['menu']= view('template/menu-navegacion');
		$datos['pie']= view('template/pie');
		$datos['recarga']= view('template/recarga');
		return view('entrar', $datos);
	}
	
	public function Entra($id=null)
	{

	
	$presentes = model('Presentes', true,$this->db);
	$ahora= date('Y-m-d H:i:s');
	$data = [
		'id_empleado' => $id,
		'entrada' => $ahora,
	];
	$presentes->insert($data);
	//$redirectResponse = new \GroceryCrud\Core\Redirect\RedirectResponse();
	return redirect()->to( base_url(). '/Fichar');
	}
	
	public function Entraextras($id=null)
	{

	$presentes = model('Presentes', true,$this->db);
	$ahora= date('Y-m-d H:i:s');
	$data = [
		'id_empleado' => $id,
		'entrada' => $ahora,
		'extras' => 1,
			];
	$presentes->insert($data);
	return redirect()->to( base_url(). '/Fichar');
	}
	
	public function Salir($id=null)
	{

	$ausentes = model('Ausentes', true, $this->db);
	$datos['ausentes']= $ausentes->where('id',$id)->first(); 
	$datos['cabecera']= view('template/cabecera');
	$datos['menu']= view('template/menu-navegacion');
	$datos['pie']= view('template/pie');
	$datos['recarga']= view('template/recarga');
	return view('salir', $datos);
	}

}
