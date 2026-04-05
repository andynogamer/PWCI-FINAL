<?php
require_once __DIR__ . '/../model/Usuario.php';


class UsuarioController{

    public function perfil() {
            
        if(!isset($_SESSION['user'])){
            header("Location: index.php?action=login"); exit;
        }


        require_once __DIR__ . '/../view/perfil.php';
    }

}

