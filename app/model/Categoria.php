<?php
require_once __DIR__ . '/../config/database.php';

class Categoria {
    public static function listar() {
        try{
            $db = Database::connect();
            $stmt = $db->query("CALL sp_ConsultaCategorias()");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }catch(Exception $e){
            return [
                'error' => true,
                'mensaje' => $e->getMessage()
            ];

        }
    }

    public static function crear($nombre) {
    
        try{
            $db = Database::connect();
            $stmt = $db->prepare("CALL sp_RegistrarCategoria(?)");
            return $stmt->execute([$nombre]);
        }catch(PDOException $e){
            return [
                'error' => true,
                'mensaje' => $e->getMessage()
            ];

        }    
    }
}