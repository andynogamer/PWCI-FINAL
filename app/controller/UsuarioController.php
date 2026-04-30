<?php
require_once __DIR__ . '/../model/Usuario.php';


class UsuarioController{

    public function perfil() {
            
        if(!isset($_SESSION['user'])){
            header("Location: index.php?action=login"); exit;
        }


        require_once __DIR__ . '/../view/perfil.php';
    }

    public function modificarPerfil(){
        if(!isset($_SESSION['user'])){
            header("Location: index.php?action=login"); exit;
        }
        $errormsg = null;
        $user = null;

        $response = Usuario::getUsuarioById();
        if($response['success']){
            $user = $response['data'];
        }else{
            $errormsg = $response['message'];
        }
        if($errormsg == null){
            require_once __DIR__ . '/../view/perfil_update.php';
        }
        
    }

}

