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

    public static function crear($data){
        try{
            $idPadre = (!empty($data['idComentarioPadre'])) ? $data['idComentarioPadre'] : null;
            $db = Database::connect();
            $stmt = $db->prepare("CALL sp_RegistrarComentario(?, ?, ?, ?)");
            
            $stmt->execute([
                $data['idPublicacion'],
                $_SESSION['user']['id'],
                $idPadre,
                $data['comentario']
            ]
            );
            return [
                'success' => true
            ];

        }catch(Exception $e){
            return [
                'error' => true,
                'mensaje' => $e->getMessage()
            ];
        }

    }
}