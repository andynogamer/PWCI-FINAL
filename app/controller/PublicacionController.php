<?php
require_once __DIR__ . '/../model/Publicacion.php';
class PublicacionController{
    public function crearPublicacion(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $datos = $_POST;
            $datos['multimedia'] = (isset($_FILES['multimedia']) && $_FILES['multimedia']['error'] == 0) 
                ? file_get_contents($_FILES['multimedia']['tmp_name']) : null;

            if(Publicacion::crear($datos)){
                header("Location: index.php?action=foro&id=" . $datos['idMundial']);
                exit;
            }
        }
        require_once __DIR__ . '/../view/foro.php';
    }

    public function publicacionesPendientes() {
        

        require_once __DIR__ . '/../view/admin/publicaciones_pendientes.php';
    }

    

}