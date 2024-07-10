<?php
namespace App\Controllers;
use CodeIgniter\Model;

class Password_miperfil extends BaseControllerGC
{
    public function index($id = null)
    {
        //Control de login    
        helper('controlacceso');
        $nivel=control_login();
        $data=datos_user();
        $id = $id ?? $data['id_user']; // Si no se proporciona un ID en la URL, usamos el ID del usuario logueado
        //Fin Control de Login
        
        $crud = $this->_getDefaultDatabase();    
        $crud->setSubject('Password','Cambiar Password');
        $crud->setTable('users');
        $crud->unsetDelete();
        $crud->columns(['username', 'password']); 
        $crud->editFields(['username','password']); 
        $crud->where(['id' => $id]);
        $stateParameters = $crud->getStateInfo();
        $crud->callbackBeforeInsert([$this,'encrypt_password']);
        $crud->callbackBeforeUpdate([$this,'encrypt_password']);
        $crud->callbackEditField('id', [$this, 'volver']);
        $crud->callbackEditField('password', [$this, 'passwordField']);
        $crud->unsetSettings();
        $crud->unsetAdd();
        $crud->unsetPrint();
        $crud->unsetExport();
        $crud->unsetExportExcel();
        $crud->unsetFilters();
        $crud->unsetSearchColumns(['password']);
        $crud->unsetColumns(['password']); 
        
        // Redirigimos a la página tras cambiar el password
        $crud->callbackAfterInsert(function ($stateParameters) {
            $redirectResponse = new \GroceryCrud\Core\Redirect\RedirectResponse();
            return $redirectResponse->setUrl(base_url('/Password/#/edit/' . $stateParameters->insertId));
        });
        $crud->callbackAfterUpdate(function ($stateParameters) {
            $redirectResponse = new \GroceryCrud\Core\Redirect\RedirectResponse();
            return $redirectResponse->setUrl(base_url('/Mi_perfil/'));
        });
        
        $crud->setLangString('modal_save', 'Guardar Password');
        // DISPLAY AS
        $crud->displayAs('password','Nuevo Password');
        $crud->readOnlyFields(['password']); 
        $crud->displayAs('id',' ');
        
        $output = $crud->render();
        
        if ($output->isJSONResponse) {
            header('Content-Type: application/json; charset=utf-8');
            echo $output->output;
            exit;
        }    
        echo view('layouts/main', (array)$output); 
    }
    
    function encrypt_password($post_array, $primary_key = null)
    {
        if (!empty($post_array->data['password'])) {
            $post_array->data['password'] = md5($post_array->data['password']);
        } else {
            unset($post_array->data['password']);
        }
        return $post_array; 
    }

    function volver ($usuario){
        return '<div class="botones_user"><a href="'.base_url().'/Mi_perfil#/edit/'. $usuario .'" class="btn btn-info btn-sm"><i class="fa fa-arrow-left fa-fw"></i> Volver</a></div>';
    }

    function passwordField (){
        return '<input class="form-control" name="password" type="text" maxlength="100" value="">';
    }
}
