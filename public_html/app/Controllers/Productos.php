<?php

namespace App\Controllers;

use \Gumlet\ImageResize;
class Productos extends BaseControllerGC

{
public function index()
{
	
// $crud = new GroceryCrud();
$crud = $this->_getClientDatabase();

$crud->setSubject('Producto','Productos');
$crud->setTable('productos');
$crud->setRelation('id_familia','familia_productos','nombre');
$crud->columns(['nombre_producto','id_familia', 'precio','unidad','estado_producto','imagen']);
$crud->setRelation('unidad','unidades','nombre_unidad');
$crud->displayAs('id_familia','Familia');
$crud->displayAs('nombre_producto','Nombre');
$crud->requiredFields(['nombre_producto','id_familia','unidad']);
$crud->setRelationNtoN ('procesos', 'procesos_productos', 'procesos', 'id_producto', 'id_proceso', '{nombre_proceso}');
$crud->setLangString('modal_save', 'Crear Producto');
$crud->addFields(['nombre_producto','id_familia', 'precio','unidad','estado_producto', 'procesos']);
$crud->editFields(['id_producto','nombre_producto', 'id_familia', 'imagen', 'precio', 'unidad', 'estado_producto']);
$crud->unsetRead();
// $crud->unsetDelete();
$crud->fieldType('estado_producto', 'dropdown_search', [
	'1' => 'Activo',
	'0' => 'Inactivo'
]);	


$crud->callbackEditField('id_producto', function ($fieldValue, $primaryKeyValue, $rowData) {
    $_SESSION['procesos'] = $fieldValue;
    $id = $fieldValue; // Aquí se obtiene el id_producto

    helper('controlacceso');
    $data= usuario_sesion(); 
    $db = db_connect($data['new_db']);

    $builder = $db->table('procesos_productos');
    $builder->select('*');
    $builder->where(array("id_producto"=>$id));
    $builder->join('procesos', 'procesos.id_proceso=procesos_productos.id_proceso', 'left');
    $builder->orderby('nombre_proceso','asc');         
    $query = $builder->get();

    // Get the ordered processes
    $orderedProcesses = $query->getResult();

    return '<input type="hidden" name="id" value="' . $fieldValue . '">' .
        '<div class="botones_user"><a href="' . base_url('productos/' . $fieldValue) . '" class="btn btn-warning btn-sm"><i class="fa fa-box fa-fw"></i>Procesos del producto</a></div>';
});


// Callbacks para registrar las acciones realizadas en LOG
$crud->callbackAfterInsert(function ($stateParameters) {
    $this->logAction('Productos', 'Añade producto', $stateParameters);
    return $stateParameters;
});
$crud->callbackAfterUpdate(function ($stateParameters) {
    $this->logAction('Productos', 'Edita producto, ID: ' . $stateParameters->primaryKeyValue, $stateParameters);
    return $stateParameters;
});
$crud->callbackAfterDelete(function ($stateParameters) {
    $this->logAction('Productos', 'Elimina producto, ID: ' . $stateParameters->primaryKeyValue, $stateParameters);
    return $stateParameters;
});

    //Definimos la ruta de subida de archivos
    $globalUploadPath =  'assets/uploads/files/' . $this->data['id_empresa'] . '/productos/';
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

     //Formatos permitidos para 'imagen producto'
     $crud->setFieldUpload('imagen', $globalUploadPath, $globalUploadPath, $uploadValidations);

     // Callback para mostrar la imagen del producto en la vista columnas
     $id_empresa= $this->data['id_empresa'];
     $crud->callbackColumn('imagen', function ($value, $row) use ($id_empresa) {
         if ($value === null || $value === '') {
             return '';
         } else {
             $specificPath = "assets/uploads/files/" . $id_empresa . "/productos/";
             return "<img src='" . base_url($specificPath . $value) . "' height='60' class='img_produco'>";
         }
     });

        //Elimina al producto de la BDD1 y la carpeta de img del id que hemos eliminado de bbdd cliente
        $crud->callbackBeforeDelete(function ($stateParameters) use ($globalUploadPath) {
            $productoId = $stateParameters->primaryKeyValue;           
            $productoFolder = $globalUploadPath . $productoId;
            if (is_dir($productoFolder)) {
                array_map('unlink', glob("$productoFolder/*.*"));
                rmdir($productoFolder);
            }         
            return $stateParameters;
        });

      // Creamos el directorio para subir el archivo
      $crud->callbackBeforeUpload(function ($stateParameters) use ($globalUploadPath) {
        // Obtenemos la id del producto
        $productoId = $_POST['pk_value'] ?? null;                 
         // Crea un directorio para el producto si no existe.
        $uploadPath = $globalUploadPath . $productoId . '/';
         if (!is_dir($uploadPath) && !mkdir($uploadPath, 0755, true)) {
            return false;
        }        
        // Busca todas las imágenes existentes en el directorio del producto.
        $existingImages = glob($uploadPath . "*.{jpg,jpeg,png}", GLOB_BRACE);
        // Elimina todas las imágenes existentes en el directorio del producto.
        foreach ($existingImages as $image) {
            unlink($image);
        }
        // Establece la ruta de subida en los parámetros del estado.
        $stateParameters->uploadPath = $uploadPath;
        return $stateParameters;
    });

     // Callback para que la ruta de las fotos incluyan la id del producto
     $crud->callbackAfterUpload(function ($result) {
        // Si isSuccess no está definido o es falso, establece un valor predeterminado
        $isSuccess = isset($result->isSuccess) ? $result->isSuccess : true;
    
        if ($isSuccess && is_string($result->uploadResult)) {
            $fileName = $result->uploadResult;
            $producto = preg_replace('/[^a-zA-Z0-9_\-]/', '', $_SESSION['producto_actual'] ?? '');
            $idProducto = $_POST['pk_value'] ?? ''; // Obtiene el id del producto
            $Newname = $producto . $idProducto . "/" . $fileName; // Añade el id del producto delante del nombre del archivo
            $result->uploadResult = $Newname;
            // Redimensión de archivos
            $fullPath = $result->stateParameters->uploadPath .$fileName;

            if (file_exists($fullPath)) {
                // Crea una nueva instancia de ImageResize y cambia tamaño img
                $image = new ImageResize($fullPath);
                $image->resizeToBestFit(300, 300);

                // Guarda la imagen redimensionada en la misma ruta
                $image->save($fullPath);
            }
        }

        return $result;
    });


$output = $crud->render();

return $this->_GC_output("layouts/main", $output); 
}

public function show($id)
{
    // Conexión a la base de datos del cliente
    helper('controlacceso');
    $data= usuario_sesion(); 
    $dbClient = db_connect($data['new_db']);

    // Buscar el producto en la base de datos del cliente
    $query = $dbClient->table('productos')->getWhere(['id_producto' => $id]);
    $producto = $query->getRow();

    // Buscar todos los procesos en la base de datos del cliente
    $query = $dbClient->table('procesos')->get();
    $allProcesses = $query->getResult();

    // Buscar los procesos del producto en la base de datos del cliente
    $builder = $dbClient->table('procesos_productos');
    $builder->select('*');
    $builder->where(array("id_producto"=>$id));
    $builder->join('procesos', 'procesos.id_proceso=procesos_productos.id_proceso', 'left');
    $builder->orderby('orden','asc');         
    $query = $builder->get();


    $orderedProcesses = $query->getResult();

    // Pasar los procesos, todos los procesos y el producto a la vista
    return view('procesos_view', ['producto' => $producto, 'procesos' => $orderedProcesses, 'allProcesses' => $allProcesses]);
}


public function updateOrder() {
    // Conexión a la base de datos del cliente
    helper('controlacceso');
    $data= usuario_sesion(); 
    $dbClient = db_connect($data['new_db']);
    //Maneja la peticion POST
    $postData = $this->request->getPost();
    if (!isset($postData['data']) || !is_string($postData['data'])) {
        // Manejar el caso en que 'data' no se envió en la petición o no es una cadena
        return $this->response->setStatusCode(400, 'Bad Request: Missing or invalid data parameter');
    }
    $newOrder = json_decode($postData['data'], true);
 
    // Borrar las relaciones existentes para este producto
    $id_producto = $newOrder[0]['id_producto']; // Obtener el id_producto del primer elemento
    $dbClient->table('procesos_productos')->where('id_producto', $id_producto)->delete();

    // Insertar cada proceso con su orden correspondiente en la tabla 'procesos_productos'
    foreach($newOrder as $item) {
        $dbClient->table('procesos_productos')->insert([
            'id_producto' => $item['id_producto'],
            'id_proceso' => $item['id_proceso'],
            'orden' => $item['orden']
        ]);
    }

    // Devolver una respuesta al cliente
    return $this->response->setStatusCode(200, 'Order updated successfully');
}

}