<?php

require_once __DIR__ . '/../model/Comentario.php';

class ComentarioController{
    public function crearComentario(){
        $resultado = null;
        if($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['comentario'])){
            
            $resultado = Comentario::crear($_POST);
            
            
            if(is_array($resultado) && isset($resultado['success'])){
                
                
                header("Location: index.php?action=publicacion&id=" . $_POST['idPublicacion']);
                exit;
            }
        }

        require_once __DIR__ . '/../view/publicacion.php';

    }


}