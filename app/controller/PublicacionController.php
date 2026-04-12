<?php
require_once __DIR__ . '/../model/Publicacion.php';
require_once __DIR__ . '/../model/Comentario.php';
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

    public function publicacionDetalle(){
        $id = $_GET['id'] ?? null;
        if (!$id) { header("Location: index.php"); exit; }
        $publicacion = Publicacion::obtenerPublicacionPorId($id);
        $comentarios = null;
        
        if(isset($publicacion['idPublicacion'])){
            
            if ($publicacion['fotoUsuario']) $publicacion['fotoUsuario'] = base64_encode($publicacion['fotoUsuario']);
            if ($publicacion['multimedia']) $publicacion['multimedia'] = base64_encode($publicacion['multimedia']);
            
            
            
            $comentarios = Comentario::listarPorPublicacion($publicacion['idPublicacion']);
            
            

            if($comentarios){
                foreach ($comentarios as &$c) {
                    if ($c['fotoUsuario']) $c['fotoUsuario'] = base64_encode($c['fotoUsuario']);
                    
                }
            }
            

        }
 
        
        require_once __DIR__ . '/../view/publicacion.php';
    }

    public function publicacionesPendientes() {
        

        require_once __DIR__ . '/../view/admin/publicaciones_pendientes.php';
    }

    

}