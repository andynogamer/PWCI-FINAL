<?php
require_once __DIR__ . '/../config/database.php';

class Comentario{
    public static function listarPorPublicacion($id){
        try{
            $db = Database::connect();
            $stmt = $db->prepare("CALL sp_ConsultarComentariosPorPublicacion(?)");
            $stmt->execute([$id]);
            $comentarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $comentarios;
        }catch (PDOException $e) {
            return [
                'error' => true,
                'mensaje' => $e->getMessage()
            ];
        }
        
    }
}