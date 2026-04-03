<?php
require_once __DIR__ . '/../model/Mundial.php';
require_once __DIR__ . '/../model/Categoria.php';

class MundialController {
    public function index() {
        
        $mundiales = Mundial::listarActivos();
        
        
        require_once __DIR__ . '/../view/mundiales.php';
    }

    public function adminCategorias() {
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['categoria'])) {
            Categoria::crear($_POST['categoria']);
            header("Location: index.php?action=admin_categorias");
            exit;
        }

        $categorias = Categoria::listar();
        require_once __DIR__ . '/../view/admin/categorias.php';
    }
    public function foro() {
        $id = $_GET['id'] ?? null;
        if (!$id) { header("Location: index.php"); exit; }
        
        // Obtenemos info del mundial para el encabezado
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