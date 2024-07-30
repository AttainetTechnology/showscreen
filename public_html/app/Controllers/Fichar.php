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
		foreach ($a1 as $usera1) {
			$a2 = model('Usuarios1_Model', true, $this->db)->where('id', $usera1['id_empleado'])->findAll();
			if (!empty($a2)) {
				$datos['presentes'][$i] = array_merge($a2[0], $usera1);
				$i += 1;
			}
		}

		$datos['cabecera']= view('template/cabecera');
		$datos['menu']= view('template/menu-navegacion');
		$datos['pie']= view('template/pie');
		$datos['recarga_hora']= view('template/recarga_hora');

		return view('presentes', $datos);
	}
	
	public function CompruebaDia()
	{            
		$aviso = "";
		$hoy = date('Y-m-d');
		$dia = date('j');
		
		$datos = new Hoy($this->db);
		$diahoy = $datos->where('id', 1)->findAll();

		if ($diahoy) {
			$fechaguardada = $diahoy[0]['hoy'];
			if ($hoy == $fechaguardada) {
				$aviso .= "Hoy es " . $dia . "</br>";
				$session = session();
				$session->setFlashdata('exito', $aviso);
			} else {
				$aviso .= 'Hemos cambiado de día</br>';
				$this->CerrarFichajesAbiertos($aviso);
			}       
		}
	} 

	public function CerrarFichajesAbiertos($aviso)
	{
		$presentes = new Presentes($this->db);
		$hoy = date('Y-m-d');
		$datos['presentes'] = $presentes
			->select('id_empleado,entrada,extras')
			->orderBy('entrada', 'ASC')
			->findAll(); 
		if ($datos['presentes']) {
			$i = 0;
			foreach ($datos['presentes'] as $key) {
				$empleado = $key['id_empleado'];
				$entrada = $key['entrada'];
				$extras = $key['extras'];
				$data = [
					'id_usuario'    => $empleado,
					'entrada'       => $entrada,
					'extras'        => $extras,
					'incidencia'    => 'sin cerrar'
				];
				$fichajes = new Fichajes($this->db);
				$fichajes->insert($data);
				$exito = $fichajes->affectedRows();
				if ($exito > 0) {
					$i++;
					$presentes->delete($empleado);
					$session = session();
					$aviso .= $i . " fichajes cerrados del día anterior.<br>";
				} else {
					$session = session();
					$aviso .= "No se han registrado fichajes.<br>";
				}
			}
		}
		$this->Compruebalaborable($aviso);  
	}

	public function Compruebalaborable($aviso)
	{
		$ayer = date('d.m.Y', strtotime("-1 days"));
		$date = new DateTime($ayer); 
		$day = $date->format("w");
		$session = session();
		if ($day == 6 || $day == 0) {
			$aviso .= 'Ayer fue fin de semana, no genero ausencias.</br>';
			$this->CambiaDeDia($aviso);
		} else {
			$aviso .= 'Ayer fue laborable.</br>';
			$this->Compruebafestivo($aviso);
		}
	}

	public function Compruebafestivo($aviso)
	{
		$ayer = date('Y-m-d', strtotime('-1 days'));
		$compruebafestivo = new Festivos($this->db);
		$datos['festivo'] = $compruebafestivo->where('fecha', $ayer)->findAll(); 
		$session = session();
		if ($datos['festivo']) {
			foreach ($datos['festivo'] as $key) {
				$fecha = $key['fecha'];  
				if ($ayer == $fecha) {
					$aviso .= "Ayer fue festivo: " . $fecha . "</br>";
					$this->CambiaDeDia($aviso);
					return;
				}
			}
		} else {
			$aviso .= "Ayer no fue festivo.<br>";
			$this->ComprobarAusencias($aviso);
		}
	}

	public function ComprobarAusencias($aviso)
	{		
		$ausentes = new Ausentes($this->db);
		$fichan['ausentes'] = $ausentes
			->where('user_ficha', '1')
			->select('id')
			->orderBy('id', 'ASC')
			->findAll(); 
		foreach ($fichan['ausentes'] as $key) {
			$empleado = $key['id'];
			$this->CompruebaFichajesAyer($aviso, $empleado);
		}
		$this->CambiaDeDia($aviso);
	}  

	public function CompruebaFichajesAyer($aviso, $empleado)
	{
		$ayer = date('Y-m-d', strtotime("-1 days"));
		$hoy = date('Y-m-d');
		$fichajes = new Fichajes($this->db);

		$fichajesayer['fichajes'] = $fichajes
			->where('id_usuario', $empleado)
			->where('entrada >', $ayer)
			->where('entrada <', $hoy)
			->select('entrada')
			->findAll();

		$session = session();
		foreach ($fichajesayer['fichajes'] as $fila) {
			foreach ($fila as $clave) {
				$entrada = $clave['entrada'];
			} 
			if (isset($entrada) && $entrada != "") {
				// El usuario fichó. No tengo que hacer nada.
			} else {
				// El usuario NO fichó. Compruebo si tenía vacaciones.
				$this->CompruebaVacaciones($empleado);
			}
		}
		$session->setFlashdata('exito', $aviso);
	}

	public function CompruebaVacaciones($empleado)
	{ 
		$ayer = date('Y-m-d 00:00', strtotime("-1 days"));
		$vacaciones = new Vacaciones_model($this->db);
		$vacacionessayer['vacaciones'] = $vacaciones
			->where('user_id', $empleado)
			->where('desde <=', $ayer)
			->where('hasta >=', $ayer)
			->select('desde')
			->findAll();

		$session = session();
		$aviso = '';
		foreach ($vacacionessayer['vacaciones'] as $fila) {
			foreach ($fila as $clave) {
				$desde = $clave['desde'];
			} 
			if (isset($desde) && $desde != "") {
				// El usuario está de vacaciones.
			} else {
				$datos = [
					'id_usuario'    => $empleado,
					'entrada'       => $ayer,
					'incidencia'    => 'Ausencia'
				];
				$fichajes = new Fichajes($this->db);
				$fichajes->insert($datos);
				$exito = $fichajes->affectedRows();
				if ($exito > 0) {
					$aviso .= "Genero ausencia para el usuario: " . $empleado . "<br>";
				} else {
					$aviso .= "Error al generar ausencia para el usuario: " . $empleado . "<br>";
				}
			}
		}
		$session->setFlashdata('exito', $aviso);
	}

	public function Sal($id = null)
	{
		$presentes = model('Presentes', true, $this->db);
		$data1 = $presentes->where('id_empleado', $id)->first();
	
		$fechaentrada = $data1['entrada']; 
		$fichaextras = $data1['extras'];
		$fichajes = model('Fichajes', true, $this->db);
	
		$ahora = date('Y-m-d H:i:s');
		$date1 = date_create_from_format('Y-m-d H:i:s', $fechaentrada);
		$date2 = date_create_from_format('Y-m-d H:i:s', $ahora);
	
		$diff = date_diff($date1, $date2);
		$totalHoras = $diff->h + ($diff->days * 24); // Asegurarse de sumar días convertidos a horas
		$totalMinutos = $diff->i;
		$totalTrabajado = ($totalHoras * 60) + $totalMinutos; // Total trabajado en minutos
	
		// Inicializar la incidencia
		$incidencia = "";
	
		if ($totalTrabajado > (8 * 60 + 30)) { 
			$incidencia = "Sin cerrar";
		} elseif ($totalTrabajado < (8 * 60)) {
			$incidencia = "Menos de 8H";
		}
	
		// Inserta el fichaje con la incidencia calculada
		$data = [
			'id_usuario' => $id,
			'entrada' => $fechaentrada,
			'salida' => $ahora,
			'incidencia' => $incidencia,
			'extras' => $fichaextras,
			'total' => $totalTrabajado
		];
		$fichajes->insert($data);
		$exito = $fichajes->affectedRows();
		$session = session();
		if ($exito > 0) {
			$presentes->delete($id);
			$resultado = "Fichaje de salida realizado correctamente.";
			$session->setFlashdata('exito', $resultado);
			return redirect()->to(base_url() . '/Fichar');
		} else {
			$resultado = "Error al fichar la salida.";
			$session->setFlashdata('error', $resultado);
			return redirect()->back();
		}
	}
	
	
	public function CambiaDeDia($aviso)
	{    
		$ahora = date('Y-m-d');   
		$hoy = model('Hoy', true, $this->db);
		$data = ['hoy' => $ahora];
		$hoy->update(1, $data);
		$exito = $hoy->affectedRows();
		$session = session();
		if ($exito > 0) {
			$aviso .= "Hemos Guardado el nuevo día.<br>";
			$session->setFlashdata('exito', $aviso);
			return redirect()->to(base_url(). '/Fichar');
		} else {
			$aviso .= "No se ha guardado el cambio de día.";
			$session->setFlashdata('error', $aviso);
			return redirect()->back();
		}
	}
		
	public function Ausentes()
	{
		$ausentesModel = new Ausentes($this->db);
		$ausentes = $ausentesModel
			->where('user_ficha', '1')
			->where('user_activo', '1')
			->orderBy('nombre_usuario', 'ASC')
			->findAll(); 

		$presentesModel = model('Presentes', true, $this->db);
		$presentes = $presentesModel->orderBy('id_empleado', 'ASC')->findAll(); 

		$datos['presentes'] = array();
		$i = 0;
		foreach ($presentes as $usera1) {
			$a2 = model('Usuarios1_Model', true, $this->db)->where('id', $usera1['id_empleado'])->findAll();
			if (!empty($a2)) {
				$datos['presentes'][$i] = array_merge($a2[0], $usera1);
				$i += 1;
			}
		}

		$datos['cabecera'] = view('template/cabecera');
		$datos['menu'] = view('template/menu-navegacion');
		$datos['pie'] = view('template/pie');
		$datos['recarga'] = view('template/recarga');
		$datos['ausentes'] = $ausentes;
		return view('ausentes', $datos);
	}
	
	public function Entrar($id = null)
	{	
		$ausentes = model('Ausentes', true, $this->db);
		$datos['ausentes'] = $ausentes->where('id', $id)->first(); 
		$datos['cabecera'] = view('template/cabecera');
		$datos['menu'] = view('template/menu-navegacion');
		$datos['pie'] = view('template/pie');
		$datos['recarga'] = view('template/recarga');
		return view('entrar', $datos);
	}
	
	public function Entra($id = null)
	{
		$presentes = model('Presentes', true, $this->db);
		$ahora = date('Y-m-d H:i:s');
		$data = [
			'id_empleado' => $id,
			'entrada' => $ahora,
		];
		$presentes->insert($data);
		return redirect()->to(base_url(). '/Fichar');
	}
	
	public function Entraextras($id = null)
	{
		$presentes = model('Presentes', true, $this->db);
		$ahora = date('Y-m-d H:i:s');
		$data = [
			'id_empleado' => $id,
			'entrada' => $ahora,
			'extras' => 1,
		];
		$presentes->insert($data);
		return redirect()->to(base_url(). '/Fichar');
	}
	
	public function Salir($id = null)
	{
		$ausentes = model('Ausentes', true, $this->db);
		$datos['ausentes'] = $ausentes->where('id', $id)->first(); 
		$datos['cabecera'] = view('template/cabecera');
		$datos['menu'] = view('template/menu-navegacion');
		$datos['pie'] = view('template/pie');
		$datos['recarga'] = view('template/recarga');
		return view('salir', $datos);
	}
}
