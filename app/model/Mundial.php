<?php
require_once __DIR__ . '/../config/database.php';

class Mundial {
    public static function listarActivos() {
        $db = Database::connect();
        
        $stmt = $db->query("CALL sp_ConsultarMundialesActivos()");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}