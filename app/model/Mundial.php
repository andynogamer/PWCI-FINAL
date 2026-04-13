<?php
require_once __DIR__ . '/../config/database.php';

class Mundial {
    public static function listarActivos() {
        try{
            $db = Database::connect();
            $stmt = $db->query("CALL sp_ConsultarMundialesActivos()");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        }catch(Exception $e){
            return [
                'error' => true,
                'mensaje' => $e->getMessage()
            ];
        }
        
    }
    public static function crear($data) {
        try{
            $db = Database::connect();
            $stmt = $db->prepare("CALL sp_RegistrarMundial(?, ?, ?, ?, ?, ?)");
            return $stmt->execute([
                $data['nombre'],
                $data['fecha'],
                $data['sede'],
                $data['logo'], 
                $data['banner'], 
                $data['descripcion']
            ]);
        }catch(Exception $e){
            return [
                'error' => true,
                'mensaje' => $e->getMessage()
            ];
        }
    }
}