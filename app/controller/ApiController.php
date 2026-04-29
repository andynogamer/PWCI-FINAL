<?php
require_once __DIR__ . '/../model/Mundial.php';
require_once __DIR__ . '/../model/Categoria.php';
require_once __DIR__ . '/../model/Publicacion.php';
require_once __DIR__ . '/../model/Like.php';

class ApiController {
    
private function renderJSON($data) {
    header('Content-Type: application/json; charset=utf-8');
    
    
    
    $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PARTIAL_OUTPUT_ON_ERROR);
    
    if ($json === false) {
        echo json_encode(["error" => "Error de codificación: " . json_last_error_msg()]);
    } else {
        echo $json;
    }
    exit;
}

    public function getMundiales() {
        $mundiales = Mundial::listarActivos();
        
        if($mundiales != null && !isset($mundiales['error'])){
            foreach ($mundiales as &$m) {
                if ($m['logo']) $m['logo'] = base64_encode($m['logo']);
                if ($m['banner']) $m['banner'] = base64_encode($m['banner']);
            }
        }
        
        
        $this->renderJSON($mundiales);
    }

    public function getCategorias() {
        $categorias = Categoria::listar();
        $this->renderJSON($categorias);
    }

    public function getPublicacionesMundial() {
        $id = $_GET['idMundial'] ?? null;
        $publicaciones = Publicacion::listarPorMundial($id);
        header('Content-Type: application/json');
        $this->renderJSON($publicaciones);
        exit;
    }

    public function getPublicacionesUsuario() {
        $idRequest = $_GET['idUsuario'] ?? null;
        $idSession = $_SESSION['user']['id'];
        if ($idRequest != $idSession) {
            http_response_code(403);
            echo json_encode(['error' => 'Acceso prohibido']);
            exit;
        }
        $publicaciones = Publicacion::listarPorUsuario($idSession);
        header('Content-Type: application/json');
        $this->renderJSON($publicaciones);
        exit;
    }

    public function getPublicacionesPendientes(){
        
        $publicaciones = Publicacion::listarPorPendientes();
        $this->renderJSON($publicaciones);

        
    }

    public function updateFotoPerfil(){
        header('Content:Type: application/json');
        $fotoBinaria = null;
        if (isset($_FILES['fotoPerfil']) && $_FILES['fotoPerfil']['error'] === UPLOAD_ERR_OK){
            $fotoBinaria = file_get_contents($_FILES['fotoPerfil']['tmp_name']);
        }
       
        
        if(!$fotoBinaria){
            http_response_code(400);
            echo json_encode([
                'error' => true,
                'mensaje' => 'Foto requerida'
            ]);
            exit;
        }else if(!isset($_SESSION['user'])){
            http_response_code(403);
            echo json_encode([
                'error' => true,
                'mensaje' => 'Acceso prohibido'
            ]);
            exit;
        }
        $resultado = Usuario::modificarFoto($fotoBinaria);
        if(isset($resultado['success'])){
            $_SESSION['user']['foto'] = $fotoBinaria;
            echo json_encode($resultado);

        }else{
            http_response_code(500);
            echo json_encode($resultado);
        }

    }

    public function updateToAprovePublicacion(){
        header('Content-Type: application/json');

        $input = json_decode(file_get_contents("php://input"), true);
        $id = $input['id'] ?? null;

        if (!$id) {
            http_response_code(400);
            echo json_encode([
                'error' => true,
                'mensaje' => 'ID requerido'
            ]);
            exit;
        }

        $resultado = Publicacion::aprobar($id);

        if ($resultado === true) {
            echo json_encode([
                'success' => true
            ]);
        } else {
            http_response_code(500);
            echo json_encode($resultado);
        }

        exit;
    }

    public function postLike(){
        header('Content-Type: application/json');
        $input = json_decode(file_get_contents("php://input"), true);
        $id = $input['id'] ?? null;
        if(!$id){
            http_response_code(400);
            echo json_encode([
                'error' => 'ID requerido'

            ]);
            exit;
        }
        $resultado = Like::crear($id);

        

        if(isset($resultado['success'])){
            echo json_encode([
                'success' => true
            ]);
        } else{
            if($resultado['message'] === 'Permisos insuficientes'){
                http_response_code(403);
                echo json_encode(['error' => 'Es necesario iniciar sesión']);
                exit;
            }
            http_response_code(500);
            echo json_encode($resultado);
        }


    }

    public function getLikes(){
        header('Content-Type: application/json');
        $input = json_decode(file_get_contents("php://input"), true);
        $id = $input['id'] ?? null;
        
        
        if(!$id){
            http_response_code(400);
            echo json_encode([
                'error' => true,
                'mensaje' => 'ID requerido'
            ]);
        }
        
        $resultado = Like::obtenerLikes($id);
        if(isset($resultado['success'])){
            echo json_encode($resultado);

        }else{
            http_response_code(500);
            echo json_encode($resultado);
        }
    }

    public function deleteMundial(){
        header('Content:Type: application/json');
        $input = json_decode(file_get_contents("php://input"), true);
        $id = $input['id'] ?? null;
        
        if(!$id){
            http_response_code(400);
            echo json_encode([
                'error' => true,
                'mensaje' => 'ID requerido'
            ]);
            exit;
        }
        $resultado = Mundial::eliminar($id);
        if(isset($resultado['success'])){
            echo json_encode($resultado);

        }else{
            http_response_code(500);
            echo json_encode($resultado);
        }
    }
        
}