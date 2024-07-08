<?php

namespace App\Controllers;

class Select_empresa extends CrudAcceso
{
    public function index()
    {
        helper('controlacceso');
        $nivel = control_login();
        $data = datos_user();

        if ($nivel < '9') {
            header('Location: ' . base_url());
            exit();
        } else {
            $crud = $this->_getGroceryCrudEnterprise();

            $crud->setSubject('Empresa', 'Selecciona la Empresa');
            $crud->setTable('dbconnections');
            $crud->columns(['id', 'nombre_empresa']);
            $crud->unsetDelete();
            $crud->unsetSettings();
            $crud->unsetPrint();
            $crud->unsetExport();
            $crud->unsetExportExcel();
            $crud->unsetFilters();
            $crud->fieldType('id', 'hidden');
            $crud->unsetSearchColumns(['id', 'nombre_empresa']);
            $crud->setActionButton('Acceder', '', function ($row) {
                return  base_url('/Acceso/') . $row->id; 
            }, false);

            $globalUploadPath = 'public/assets/uploads/files/';
            $crud->addFields(['id', 'nombre_empresa', 'db_name', 'db_user', 'db_password']);
            $crud->editFields(['id', 'nombre_empresa', 'db_name', 'db_user', 'db_password', 'logo_empresa', 'favicon', 'logo_fichajes']);

            $crud->setFieldUpload('logo_empresa', $globalUploadPath, $globalUploadPath);
            $crud->setFieldUpload('logo_fichajes', $globalUploadPath, $globalUploadPath);
            $crud->setFieldUpload('favicon', $globalUploadPath, $globalUploadPath);

            $crud->displayAs('nombre_empresa', 'Empresa');
            $crud->displayAs('id', ' ');

            $crud->callbackEditField('id', function ($fieldValue, $primaryKeyValue, $rowData) {
                $_SESSION['empresa_actual'] = $fieldValue;
                return '<input name="id" value="' . $fieldValue . '" type="hidden"/>';
            });

            $crud->callbackBeforeUpload(function ($uploadData) {
                $empresa = preg_replace('/[^a-zA-Z0-9_\-]/', '', $_SESSION['empresa_actual'] ?? '');
                $Path = $uploadData->uploadPath;

                $NewPath = $Path . $empresa . "/logos/";
                if (!is_dir($NewPath)) {
                    mkdir($NewPath, 0755, true);
                }
                $uploadData->uploadPath = $NewPath;
                return $uploadData;
            });

            $crud->callbackAfterUpload(function ($result) {
                $fileName = $result->uploadResult;
                $empresa = preg_replace('/[^a-zA-Z0-9_\-]/', '', $_SESSION['empresa_actual'] ?? '');
                $Newname = $empresa . "/logos/" . $fileName;
                $result->uploadResult = $Newname;
                return $result;
            });

            $output = $crud->render();

            if ($output->isJSONResponse) {
                header('Content-Type: application/json; charset=utf-8');
                echo $output->output;
                exit();
            }

            echo view('selectempresa', (array)$output);
        }
    }
}
