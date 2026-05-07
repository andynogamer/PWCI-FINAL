<?php
require_once __DIR__ . '/../model/Mundial.php';
require_once __DIR__ . '/../model/Categoria.php';

class MundialController {
    public function index() {
        
        
        
        
        require_once __DIR__ . '/../view/mundiales.php';
    }

    public function adminCategorias() {
        $resultado = null;
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['categoria'])) {
            $resultado = Categoria::crear(mb_strtoupper(trim($_POST['categoria'])));
            
            if(is_array($resultado) && isset($resultado['success'])){
                header("Location: index.php?action=admin_categorias");
                exit;
            }
            
        }
        

        
        require_once __DIR__ . '/../view/admin/categorias.php';
    }

    public function mundialPorid(){
        $errormsg = null;
        $isSuccess = null;
        $mundial = null;
        
        
        $id = $_GET['id'] ?? null;
        if (!$id) { header("Location: index.php"); exit; }

        $isSuccess = Mundial::mundiaPorId($id);
        if(is_array($isSuccess) && isset($isSuccess['success'])){
            $mundial = $isSuccess['data'];
        }else if(is_array($isSuccess) && isset($isSuccess['error'])){
            $errormsg = $isSuccess['mensaje'];
        }else{
            $errormsg = "Algo salió mal...";
        }

        require_once __DIR__ . '/../view/admin/edit_mundial_form.php';

        
    }

    public function modificarMundial() {
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php");
            exit;
        }

        $id = $_POST['id'] ?? null;
        $nombre = $_POST['nombre'] ?? null;
        $fecha = $_POST['fecha'] ?? null;
        $sede = $_POST['sede'] ?? null;
        $descripcion = $_POST['descripcion'] ?? null;

        if (empty($id) || empty($nombre) || empty($fecha) || empty($sede) || empty($descripcion)) {
            
            $_SESSION['error_mensaje'] = "Todos los campos de texto son obligatorios.";
            header("Location: index.php?action=admin_modificar_mundial&id=$id");
            exit;
        }

        $mundialActual = Mundial::mundiaPorId($id);

        $logo = null;
        $banner = null;
        
        if (isset($_FILES['logo'])  && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {       
            $logo = file_get_contents($_FILES['logo']['tmp_name']);
        } else {
            if(isset($mundialActual['success'])){$logo = $mundialActual['data']['logo'];} 
        }

        // 3. Procesamos el Banner
        if (isset($_FILES['banner']) && $_FILES['banner']['error'] === UPLOAD_ERR_OK) {
            $banner = file_get_contents($_FILES['banner']['tmp_name']);
        } else {
            if(isset($mundialActual['success'])){$banner = $mundialActual['data']['banner'];}
        }

        
        

        
        $data = [
            'id'          => $id,
            'nombre'      => $nombre,
            'fecha'       => $fecha,
            'sede'        => $sede,
            'logo'        => $logo,
            'banner'      => $banner,
            'descripcion' => $descripcion
        ];

        
        $resultado = Mundial::updateMundial($data);

        if (isset($resultado['success'])) {
            header("Location: index.php?action=mundiales");
        } else {
            $_SESSION['error_mensaje'] = "Error en BD: " . $resultado['mensaje'];
            header("Location: index.php?action=admin_modificar_mundial&id=$id");
        }
        exit;
    }

    public function foro() {
        $id = $_GET['id'] ?? null;
        if (!$id) { header("Location: index.php"); exit; }
        
        
        $db = Database::connect();
        $stmt = $db->prepare("CALL sp_ConsultarMundialPorId(?)");
        $stmt->execute([$id]);
        $mundial = $stmt->fetch();

        require_once __DIR__ . '/../view/foro.php';
    }

    public function adminMundiales() {
        
        if (!isset($_SESSION['user']) || $_SESSION['user']['tipoUsuario'] != 2) {
            header("Location: index.php?action=mundiales");
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $datos = $_POST;
            
            // Procesamiento de imágenes BLOB
            $datos['logo'] = (isset($_FILES['logo']) && $_FILES['logo']['error'] == 0) 
                ? file_get_contents($_FILES['logo']['tmp_name']) : null;
                
            $datos['banner'] = (isset($_FILES['banner']) && $_FILES['banner']['error'] == 0) 
                ? file_get_contents($_FILES['banner']['tmp_name']) : null;

            if (Mundial::crear($datos)) {
                header("Location: index.php?action=mundiales");
                exit;
            }
        }
        require_once __DIR__ . '/../view/admin/mundial_form.php';
    }
}