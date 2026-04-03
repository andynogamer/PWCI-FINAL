<?php
require_once __DIR__ . '/../config/database.php';

class Mundial {
    public static function listarActivos() {
        $db = Database::connect();
        
        $stmt = $db->query("CALL sp_ConsultarMundialesActivos()");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public static function crear($data) {
    $db = Database::connect();
    
    $stmt = $db->prepare("CALL sp_RegistrarMundial(?, ?, ?, ?, ?, ?)");
    
    return $stmt->execute([
        $data['nombre'],
        $data['fecha'],
        $data['sede'],
        $data['logo'], // Binario del Logo
        $data['banner'], // Binario del Banner
        $data['descripcion']
    ]);
}
}