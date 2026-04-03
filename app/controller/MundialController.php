<?php
require_once __DIR__ . '/../model/Mundial.php';
require_once __DIR__ . '/../model/Categoria.php';

class MundialController {
    public function index() {
        // Obtenemos la lista de mundiales desde el modelo
        $mundiales = Mundial::listarActivos();
        
        // Cargamos la vista de la landing page
        require_once __DIR__ . '/../view/mundiales.php';
    }

    public function adminCategorias() {
        // El middleware ya protege esta ruta, así que aquí solo operamos
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['categoria'])) {
            Categoria::crear($_POST['categoria']);
            header("Location: index.php?action=admin_categorias");
            exit;
        }

        $categorias = Categoria::listar();
        require_once __DIR__ . '/../view/admin/categorias.php';
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