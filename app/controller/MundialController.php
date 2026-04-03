<?php
require_once __DIR__ . '/../model/Mundial.php';

class MundialController {
    public function index() {
        // Obtenemos la lista de mundiales desde el modelo
        $mundiales = Mundial::listarActivos();
        
        // Cargamos la vista de la landing page
        require_once __DIR__ . '/../view/mundiales.php';
    }
}