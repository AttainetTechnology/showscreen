<?php
    function logo(){
    $url_model=model ('Models\Config_model');
    $urllogo= $url_model->find(1);
            if ($urllogo!=""){
                    $logo_url=$urllogo['url_logo'];
                    $logo=base_url('/assets/uploads/files')."/".$logo_url;
                    return $logo;
            } else {
                 $logo="no_logo.png";
                 return $logo;   
            }
    }