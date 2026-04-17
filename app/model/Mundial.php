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

    public static function mundiaPorId($id){
        try{
            

            $db = Database::connect();
            $stmt = $db->prepare("CALL sp_ConsultarMundialPorId(?)");
            $stmt->execute([
                $id
            ]);
            return [
                'success' => true,
                'data' => $stmt->fetch(PDO::FETCH_ASSOC)
            ];

        }catch(Exception $e){
            return [
                'error' => true,
                'mensaje' => $e->getMessage()
            ];
        }
    }

    public static function updateMundial($data){
        try{
            $db = Database::connect();
            $stmt = $db->prepare("CALL sp_ModificarMundial(?,?,?,?,?,?,?)");
            $stmt->execute([
                $data['id'],
                $data['nombre'],
                $data['fecha'],
                $data['sede'],
                $data['logo'], 
                $data['banner'], 
                $data['descripcion']

            ]);
            return [
                'success' => true,
                'mensaje' => 'Se modificó correctamente'
            ];

        }catch(Exception $e){
            return[
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

    public static function eliminar($id){
        try{
            $db = Database::connect();
            $stmt = $db->prepare("CALL sp_BajaMundial(?)");
            $stmt->execute([
                $id
            ]);
            return [
                'success' => true,
                'mensaje' => 'Se eliminó correctamente'
            ];
        }catch(Exception $e){
            return [
                'error' => true,
                'mensaje' => $e->getMessage()
            ];
        }
    }
}