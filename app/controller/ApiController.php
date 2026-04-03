<?php
require_once __DIR__ . '/../model/Mundial.php';
require_once __DIR__ . '/../model/Categoria.php';
require_once __DIR__ . '/../model/Publicacion.php';

class ApiController {
    private function renderJSON($data) {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    public function getMundiales() {
        $mundiales = Mundial::listarActivos();
        
        
        foreach ($mundiales as &$m) {
            if ($m['logo']) $m['logo'] = base64_encode($m['logo']);
            if ($m['banner']) $m['banner'] = base64_encode($m['banner']);
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
        echo json_encode($publicaciones);
        exit;
    }
}