<?php

require_once __DIR__ . '/../model/Like.php';

class LikeController{
    public function crearLike(){
        $resultado = null;
        
        if($_SERVER['REQUEST_METHOD'] == 'GET' && !empty($_GET['idPublicacion'])){

            
            
            if(!isset($_SESSION['user'])){
                header("Location: index.php?action=login");
                exit;
            }
            
            
            
            $resultado = Like::crear($_GET);
            
            
            
            if(is_array($resultado) && isset($resultado['success'])){
                
                
                header("Location: index.php?action=publicacion&id=" . $_GET['idPublicacion']);
                exit;
            }
        }

        require_once __DIR__ . '/../view/publicacion.php';

    }


}