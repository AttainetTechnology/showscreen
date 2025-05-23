<?php

namespace App\Controllers;

use App\Models\Productos_model;
use App\Models\ProductosNecesidadModel;
use App\Models\FamiliaProveedorModel;


use \Gumlet\ImageResize;

class Productos_necesidad extends BaseController
{

    public function index()
    {

        helper('controlacceso');
<<<<<<< HEAD
=======
        $redirect = check_access_level();
        $redirectUrl = session()->getFlashdata('redirect');
        if ($redirect && is_string($redirectUrl)) {
            return redirect()->to($redirectUrl);
        }
>>>>>>> 0c4bc0213a73e7eae133885471457832782be967
        $this->addBreadcrumb('Inicio', base_url());
        $this->addBreadcrumb('Productos Necesidad', base_url('productos_necesidad'));

        return view('productos_necesidad', [
            'amiga' => $this->getBreadcrumbs()
        ]);
    }
    public function getProductos()
    {
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        $productosModel = new ProductosNecesidadModel($db);
        $productosProveedorModel = new \App\Models\ProductosProveedorModel($db);
        $productosVentaModel = new \App\Models\Productos_model($db);

        $productos = $productosModel->findAll();
        foreach ($productos as &$producto) {
            // Obtener la familia
            $familia = (new FamiliaProveedorModel($db))->find($producto['id_familia']);
            $producto['nombre_familia'] = $familia ? $familia['nombre'] : 'Sin familia';

            // Imagen
            $producto['imagen'] = $this->getImageUrl($producto['imagen'], $data['id_empresa'], $producto['id_producto']);

            // Acciones
            $producto['acciones'] = [
                'precio' => base_url("comparadorproductos/{$producto['id_producto']}"),
                'verProductos' => base_url("productos_necesidad/verProductos/{$producto['id_producto']}"),
                'editar' => base_url("productos_necesidad/edit/{$producto['id_producto']}"),
                'eliminar' => base_url("productos_necesidad/delete/{$producto['id_producto']}")
            ];

            // Precio de compra y nombre del proveedor
            if (!empty($producto['mejor'])) {
                $precioProveedor = $productosProveedorModel
                    ->where('id_producto_necesidad', $producto['id_producto'])
                    ->where('id', $producto['mejor'])
                    ->select('precio, id_proveedor')
                    ->get()
                    ->getRow();
            } else {
                $precioProveedor = $productosProveedorModel
                    ->where('id_producto_necesidad', $producto['id_producto'])
                    ->select('precio, id_proveedor')
                    ->get()
                    ->getRow();
            }

            if ($precioProveedor) {
                $producto['precio'] = $precioProveedor->precio;
                // Obtener el nombre del proveedor
                $proveedor = (new \App\Models\ProveedoresModel($db))->find($precioProveedor->id_proveedor);
                $producto['nombre_proveedor'] = $proveedor ? $proveedor['nombre_proveedor'] : ' ';
            } else {
                $producto['precio'] = ' ';
                $producto['nombre_proveedor'] = ' ';
            }

            // Precio de venta
            if (!empty($producto['id_producto_venta'])) {
                $productoVenta = $productosVentaModel->find($producto['id_producto_venta']);
                $producto['precio_venta'] = $productoVenta ? $productoVenta['precio'] : ' ';
            } else {
                $producto['precio_venta'] = ' ';
            }
        }
        return $this->response->setJSON($productos);
    }

    private function getImageUrl($imageName, $idEmpresa)
    {
        $path = "public/assets/uploads/files/{$idEmpresa}/productos/";
        return $imageName ? base_url($path . $imageName) : '';
    }



    public function verProductos($id_producto)
    {
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        $model = new Productos_model($db);
        $familiaModel = new \App\Models\FamiliaProveedorModel($db);

        $productos = $model->orderBy('nombre_producto', 'ASC')->findAll();
        $familias = $familiaModel->orderBy('nombre', 'ASC')->findAll();

        $productosNecesidadModel = new ProductosNecesidadModel($db);
        $productoNecesidad = $productosNecesidadModel->find($id_producto);
        $idProductoVentaSeleccionado = $productoNecesidad['id_producto_venta'];

        // Mover el producto seleccionado al principio de la lista
        if ($idProductoVentaSeleccionado) {
            usort($productos, function ($a, $b) use ($idProductoVentaSeleccionado) {
                if ($a['id_producto'] == $idProductoVentaSeleccionado) {
                    return -1; // El producto seleccionado va primero
                } elseif ($b['id_producto'] == $idProductoVentaSeleccionado) {
                    return 1;
                }
                return 0;
            });
        }

        return view('selectProducto', [
            'productos' => $productos,
            'familias' => $familias,
            'id_producto' => $id_producto,
            'id_producto_venta' => $idProductoVentaSeleccionado
        ]);
    }


    public function actualizarProductoVenta()
    {
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        $model = new \App\Models\ProductosNecesidadModel($db);

        $idProductoNecesidad = $this->request->getPost('id_producto_necesidad');
        $idProductoVenta = $this->request->getPost('id_producto_venta');
        if ($idProductoVenta === null || $idProductoVenta === '') {
            $model->update($idProductoNecesidad, [
                'id_producto_venta' => null
            ]);
            $log = "Deselección de producto ID: " . $idProductoNecesidad;
            $seccion = "Deselección de Producto";
        } else {
            $model->update($idProductoNecesidad, [
                'id_producto_venta' => $idProductoVenta
            ]);
            $log = "Actualización producto: " . $idProductoNecesidad . ", nuevo producto: " . $idProductoVenta;
            $seccion = "Seleccion de producto";
        }
        $this->logAction($seccion, $log, $data);
        return $this->response->setJSON(['success' => true]);
    }
    public function add()
    {

        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        $familiaModel = new FamiliaProveedorModel($db);
        $familias = $familiaModel->findAll();

        return view('addProductoProveedor', ['familias' => $familias]);
    }

    public function save()
    {

        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        $productosModel = new ProductosNecesidadModel($db);
        $validation = \Config\Services::validation();
        $validation->setRules([
            'nombre_producto' => 'required',
            'id_familia' => 'required',
            'estado_producto' => 'required'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }
        $productosModel->save([
            'nombre_producto' => $this->request->getPost('nombre_producto'),
            'id_familia' => $this->request->getPost('id_familia'),
            'unidad' => $this->request->getPost('unidad'),
            'estado_producto' => $this->request->getPost('estado_producto')
        ]);

        return redirect()->to(base_url('productos_necesidad'))->with('success', 'Producto añadido correctamente.');
    }

    public function edit($id_producto)
    {
        $this->addBreadcrumb('Inicio', base_url('/'));
        $this->addBreadcrumb('Productos Necesidad', base_url('/productos_necesidad'));
        $this->addBreadcrumb('Editar Producto');

        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        $productosModel = new ProductosNecesidadModel($db);
        $familiaModel = new FamiliaProveedorModel($db);
        $producto = $productosModel->find($id_producto);
        $familias = $familiaModel->findAll();
        $productoVentaNombre = $this->obtenerNombreProductoVenta($id_producto);
        $data['amiga'] = $this->getBreadcrumbs();

        $gallery = new \App\Controllers\Gallery();
        $currentDirectory = $gallery->buildDirectoryPath('productos');

        [$folders, $images] = $gallery->scanDirectory($currentDirectory, 'productos');


        if (empty($images)) {
            log_message('error', 'No se encontraron imágenes en la galería.');
        }


        return view('editProductoProveedor', [
            'producto' => $producto,
            'familias' => $familias,
            'productoVentaNombre' => $productoVentaNombre,
            'id_empresa' => $data['id_empresa'],
            'amiga' => $data['amiga'],
<<<<<<< HEAD
            'images' => $images, // Pasar las imágenes
=======
            'images' => $images,
>>>>>>> 0c4bc0213a73e7eae133885471457832782be967
        ]);

    }
    public function update($id_producto)
<<<<<<< HEAD
{
    $data = usuario_sesion();
    $db = db_connect($data['new_db']);
    $productosModel = new ProductosNecesidadModel($db);

    // Validaciones
    $validation = \Config\Services::validation();
    $validation->setRules([
        'nombre_producto' => 'required',
        'id_familia' => 'required',
        'estado_producto' => 'required|in_list[Activo,Inactivo]'
    ]);

    if (!$validation->withRequest($this->request)->run()) {
        return redirect()->back()->withInput()->with('errors', $validation->getErrors());
    }

    // Obtener la imagen actual del producto
    $producto = $productosModel->find($id_producto);
    $imagenActual = $producto['imagen']; // Imagen actual en la base de datos

    // Manejo de la nueva imagen
    $image = $this->request->getFile('imagen');
    $productFolder = "public/assets/uploads/files/{$data['id_empresa']}/productos/";

    // Mantener el nombre de imagen actual como valor predeterminado
    $imageName = $imagenActual;

    if ($image && $image->isValid() && !$image->hasMoved()) {
        // Crear la carpeta si no existe
        if (!is_dir($productFolder)) {
            mkdir($productFolder, 0777, true);
        }

        // Obtener la extensión y generar el nuevo nombre
        $userSesionId = $data['id_user'] ?? 'unknown';
        $nombreBase = pathinfo($image->getName(), PATHINFO_FILENAME);
        $extension = $image->getExtension();
        $nuevoNombre = "{$nombreBase}_IDUser{$userSesionId}.{$extension}";

        // Verificar si el archivo ya existe en la carpeta
        if (file_exists($productFolder . $nuevoNombre)) {
            // Si la imagen ya existe, simplemente almacena el nombre en la base de datos
            $imageName = $nuevoNombre;
        } else {
            // Si no existe, mueve la imagen y actualiza el nombre
            $image->move($productFolder, $nuevoNombre);
            $imageName = $nuevoNombre;
        }
    }

    // Actualizar la base de datos con la nueva imagen (o mantener la existente)
    $productosModel->update($id_producto, [
        'nombre_producto' => $this->request->getPost('nombre_producto'),
        'id_familia' => $this->request->getPost('id_familia'),
        'imagen' => $imageName, // Guardar la nueva imagen o mantener la existente
        'unidad' => $this->request->getPost('unidad'),
        'estado_producto' => $this->request->getPost('estado_producto')
    ]);

    return redirect()->to(base_url('productos_necesidad/edit/' . $id_producto))->with('success', 'Producto actualizado correctamente.');
}
=======
    {
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        $productosModel = new ProductosNecesidadModel($db);

        // Validaciones
        $validation = \Config\Services::validation();
        $validation->setRules([
            'nombre_producto' => 'required',
            'id_familia' => 'required',
            'estado_producto' => 'required|in_list[Activo,Inactivo]'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        // Obtener la imagen actual del producto
        $producto = $productosModel->find($id_producto);
        $imagenActual = $producto['imagen']; 

        // Manejo de la nueva imagen
        $image = $this->request->getFile('imagen');
        $productFolder = "public/assets/uploads/files/{$data['id_empresa']}/productos/";

        $imageName = $imagenActual;

        if ($image && $image->isValid() && !$image->hasMoved()) {
            // Crear la carpeta si no existe
            if (!is_dir($productFolder)) {
                mkdir($productFolder, 0777, true);
            }

            // Obtener la extensión y generar el nuevo nombre
            $userSesionId = $data['id_user'] ?? 'unknown';
            $nombreBase = pathinfo($image->getName(), PATHINFO_FILENAME);
            $extension = $image->getExtension();
            $nuevoNombre = "{$nombreBase}_IDUser{$userSesionId}.{$extension}";

            // Verificar si el archivo ya existe en la carpeta
            if (file_exists($productFolder . $nuevoNombre)) {
                // Si la imagen ya existe, simplemente almacena el nombre en la base de datos
                $imageName = $nuevoNombre;
            } else {
                // Si no existe, mueve la imagen y actualiza el nombre
                $image->move($productFolder, $nuevoNombre);
                $imageName = $nuevoNombre;
            }
        }

        // Actualizar la base de datos con la nueva imagen (o mantener la existente)
        $productosModel->update($id_producto, [
            'nombre_producto' => $this->request->getPost('nombre_producto'),
            'id_familia' => $this->request->getPost('id_familia'),
            'imagen' => $imageName, // Guardar la nueva imagen o mantener la existente
            'unidad' => $this->request->getPost('unidad'),
            'estado_producto' => $this->request->getPost('estado_producto')
        ]);

        return redirect()->to(base_url('productos_necesidad/edit/' . $id_producto))->with('success', 'Producto actualizado correctamente.');
    }
>>>>>>> 0c4bc0213a73e7eae133885471457832782be967
    public function delete($id_producto)
    {
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        $productosModel = new ProductosNecesidadModel($db);
        if ($productosModel->find($id_producto)) {
            $productosModel->delete($id_producto);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Producto no encontrado.']);
        }
    }
    public function eliminarImagen($id)
    {
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        $productosModel = new ProductosNecesidadModel($db);

        // Buscar el producto por ID
        $producto = $productosModel->find($id);
        if (!$producto || !$producto['imagen']) {
            return $this->response->setJSON(['success' => false, 'message' => 'Imagen no encontrada.']);
        }

        // Desasociar la imagen del producto (establecer el campo 'imagen' en NULL)
        $productosModel->update($id, ['imagen' => null]);
    }


    public function asociarImagen()
    {
        $id_producto = $this->request->getPost('id_producto');
        $imagen = $this->request->getPost('imagen');

        if (!$id_producto || !$imagen) {
            return $this->response->setJSON(['success' => false, 'message' => 'Faltan datos.']);
        }

        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        $productosModel = new ProductosNecesidadModel($db);

        $producto = $productosModel->find($id_producto);

        if (!$producto) {
            return $this->response->setJSON(['success' => false, 'message' => 'Producto no encontrado.']);
        }

        $productosModel->update($id_producto, ['imagen' => $imagen]);

    }

    private function obtenerNombreProductoVenta($id_producto_necesidad)
    {
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        $builder = $db->table('productos_necesidad');
        $builder->select('id_producto_venta');
        $builder->where('id_producto', $id_producto_necesidad);
        $query = $builder->get();

        if ($query->getNumRows() > 0) {
            $id_producto_venta = $query->getRow()->id_producto_venta;
            if ($id_producto_venta) {
                $builder_productos = $db->table('productos');
                $builder_productos->select('nombre_producto');
                $builder_productos->where('id_producto', $id_producto_venta);
                $query_productos = $builder_productos->get();

                if ($query_productos->getNumRows() > 0) {
                    return $query_productos->getRow()->nombre_producto;
                }
            }
        }
        return 'No hay producto de venta seleccionado';
    }
}
