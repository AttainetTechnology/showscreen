<?php
namespace App\Controllers;
use \Gumlet\ImageResize;

//El CrudUsuarios conecta con la BDD principal
class Mi_perfil extends BaseControllerGC
{ 
  
public function index()
{
		$data=datos_user();
		$id_empresa = $data['empresa'];
		$id = $data['id_user'];
		//Fin Control de Login
		
		$crud = $this->_getClientDatabase();
	
		$crud->setSubject('Miperfil','Mi perfil');

		$crud->setTable('users');
		$crud->columns(['nombre_usuario','apellidos_usuario','userfoto']);
		$crud->callbackColumn('userfoto', array($this, 'userfoto'));
		$crud->where([
				'id' => $id
			]);
		$crud->unsetOperations();
		$crud->unsetSettings();
		$crud->unsetPrint();
		$crud->unsetExport();
		$crud->unsetExportExcel();
		$crud->unsetFilters();
		$crud->unsetSearchColumns(['nombre_usuario','apellidos_usuario','userfoto']);
		$crud->setEdit();	
		$crud->editFields(['id','nombre_usuario','apellidos_usuario','userfoto']);


		$crud->callbackBeforeUpdate(function ($stateParameters) {
			$data = $stateParameters->data;
			$primaryKeyValue = $stateParameters->primaryKeyValue;
		
			// Si el id no ha sido modificado, establecerlo al valor original
			if (empty($data['id'])) {
				$data['id'] = $primaryKeyValue;
			}
		
			$stateParameters->data = $data;
			return $stateParameters;
		});
		

		        //Definimos la ruta de subida de archivos
				$globalUploadPath =  'public/assets/uploads/files/' . $this->data['id_empresa'] . '/usuarios/';
				if (!is_dir($globalUploadPath)) {
					mkdir($globalUploadPath, 0777, true);
				}
				// Configuración de validaciones para la carga de archivos
				$uploadValidations = [
					'maxUploadSize' => '7M', // 20 Mega Bytes
					'minUploadSize' => '1K', // 1 Kilo Byte
					'allowedFileTypes' => [
						'gif', 'jpeg', 'jpg', 'png', 'tiff'
					]
				];
				
			   //Formatos permitidos para 'userfoto'
				$crud->setFieldUpload('userfoto', $globalUploadPath, $globalUploadPath, $uploadValidations);
		
				// Callback para mostrar la imagen del usuario en la vista columnas
				$id_empresa= $this->data['id_empresa'];
				$crud->callbackColumn('userfoto', function ($value, $row) use ($id_empresa) {
					if ($value === null || $value === '') {
						return '';
					} else {
						$specificPath = "public/assets/uploads/files/" . $id_empresa . "/usuarios/";
						return "<img src='" . base_url($specificPath . $value) . "' height='60' class='foto_user'>";
					}
				});
						
				//Elimina al usuario de la BDD1 y la carpeta de img del id que hemos eliminado de bbdd cliente
				$crud->callbackBeforeDelete(function ($stateParameters) use ($globalUploadPath) {
					$userId = $stateParameters->primaryKeyValue;           
					$db = db_connect(); 
					$db->table('users')->delete(['id' => $userId]);           
					$userFolder = $globalUploadPath . $userId;
					if (is_dir($userFolder)) {
						array_map('unlink', glob("$userFolder/*.*"));
						rmdir($userFolder);
					}         
					return $stateParameters;
				});
		
				  // Creamos el directorio para subir el archivo
				  $crud->callbackBeforeUpload(function ($stateParameters) use ($globalUploadPath) {
					// Obtenemos la id del usuario
					$userId = $_POST['pk_value'] ?? null;                 
					 // Crea un directorio para el usuario si no existe.
					$uploadPath = $globalUploadPath . $userId . '/';
					 if (!is_dir($uploadPath) && !mkdir($uploadPath, 0755, true)) {
						return false;
					}        
					// Busca todas las imágenes existentes en el directorio del usuario.
					$existingImages = glob($uploadPath . "*.{jpg,jpeg,png}", GLOB_BRACE);
					// Elimina todas las imágenes existentes en el directorio del usuario.
					foreach ($existingImages as $image) {
						unlink($image);
					}
					// Establece la ruta de subida en los parámetros del estado.
					$stateParameters->uploadPath = $uploadPath;
					return $stateParameters;
				});
				

				// Callback para que la ruta de las fotos incluyan la id del user
				$crud->callbackAfterUpload(function ($result) {
					// Si isSuccess no está definido o es falso, establece un valor predeterminado
					$isSuccess = isset($result->isSuccess) ? $result->isSuccess : true;

					if ($isSuccess && is_string($result->uploadResult)) {
						$fileName = $result->uploadResult;
						$id_user = $_SESSION['logged_in']['id_user'] ?? ''; // Obtener el id_user de la sesión
						$Newname = $id_user . "/" . $fileName;
						$result->uploadResult = $Newname;

						// Redimensión de archivos
						$fullPath = $result->stateParameters->uploadPath . $fileName;

						if (file_exists($fullPath)) {
							// Crea una nueva instancia de ImageResize y cambia tamaño img
							$image = new ImageResize($fullPath);
							$image->resizeToBestFit(400, 200);

							// Guarda la imagen redimensionada en la misma ruta
							$image->save($fullPath);
						}
					}

					return $result;
				});

		$crud->callbackEditField('id',function ($fieldValue, $primaryKeyValue, $rowData) {
			helper('controlacceso');
			$nivel=control_login();
			$data=datos_user();
			$id = $data['id_user'];
			//No permito cambiar passwords a niveles inferiores ni a trabajadores de planta
			if (($nivel>'8')OR($id==$fieldValue)OR(($nivel>'4')AND($rowData->nivel_acceso=='1'))){
				return '<div class="botones_user"><a href="'.base_url().'Password_miperfil#/edit/'. $fieldValue.'" class="btn btn-warning btn-sm"><i class="fa fa-user fa-fw"></i> Cambiar password</a></div>';
				}
		});
		$crud->setLangString('modal_save', 'Guardar');
		
		//DISPLAY AS
		$crud->displayAs('id',' ');
		$crud->DisplayAs('nombre_usuario','Nombre');
		$crud->DisplayAs('apellidos_usuario','Apellidos');
		$crud->DisplayAs('userfoto','Foto');
	
		$output = $crud->render();
		if ($output->isJSONResponse) {
			header('Content-Type: application/json; charset=utf-8');
			echo $output->output;
			exit;
		}	
	
        return $this->_GC_output('layouts/main', $output);
}
	

function userfoto ($valor){
		if ($valor==""){
			return '';
		}
		if ($valor!=""){
			return '<div class="userfoto"><img src="'.base_url().'/public/assets/uploads/usuarios/'.$valor.'" height="45px" width="auto"></div>';
		}
	}
}

