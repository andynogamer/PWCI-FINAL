<?php   
require_once __DIR__ . '/../config/database.php';

class Like{
    public static function crear($data){
        
        
        try{
            $db = Database::connect();
            $stmt = $db->prepare("CALL sp_RegistrarLike(?, ?)");
            $stmt->execute([
                $_SESSION['user']['id'],
                $data

            ]);
            return[
                'success' => true
            ];
            

        }catch(Exception $e){
            return[
                'error' => true,
                'message' => $e->getMessage()
            ];
        }

    }

    public static function obtenerLikes($id) {
        try{
            $db = Database::connect();
            $stmt = $db->prepare("CALL sp_ConsultaLikePorPublicacion(?)");
            $stmt->execute([$id]);
            $res = $stmt->fetch();
            return [
                'success' => true,
                'response' => $res['totalLikes'] ?? 0
            ];
        }catch(Exception $e){
            return[
                'error' => true,
                'message' => $e->getMessage()
            ];
        }
        
    }
}