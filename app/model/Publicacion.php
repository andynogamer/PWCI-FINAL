<?php
require_once __DIR__ . '/../config/database.php';

class Publicacion {
    public static function listarPorMundial($idMundial) {
        $db = Database::connect();
        $stmt = $db->prepare("
                            SELECT idPublicacion, idMundial, nombreMundial, fechaMundial, idUsuario, nombreUsuario, apellidoUsuario, fotoUsuario,
                            idCategoria, nombreCategoria, paisMencionado, descripcion, multimedia, estatus, fechaCreacion, fechaAprobacion, vistas, 
                            tipoPublicacion
                            FROM vw_PublicacionesInfo WHERE idMundial = ? AND estatus = true ORDER BY fechaCreacion DESC");
        $stmt->execute([$idMundial]);
        $publicaciones = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($publicaciones as &$p) {
            $p['likes'] = self::obtenerLikes($p['idPublicacion']);
            $p['comentarios'] = self::obtenerTotalComentarios($p['idPublicacion']);
            if ($p['multimedia']) $p['multimedia'] = base64_encode($p['multimedia']);
            if ($p['fotoUsuario']) $p['fotoUsuario'] = base64_encode($p['fotoUsuario']);
        }
        return $publicaciones;
    }

    public static function listarPorMundialByLikes($idMundial) {
        try{
            $db = Database::connect();
            $stmt = $db->prepare("
                                SELECT p.idPublicacion, p.idMundial, p.nombreMundial, p.fechaMundial, p.idUsuario, p.nombreUsuario, p.apellidoUsuario, p.fotoUsuario,
                                p.idCategoria, p.nombreCategoria, p.paisMencionado, p.descripcion, p.multimedia, p.estatus, p.fechaCreacion, p.fechaAprobacion, p.vistas, 
                                p.tipoPublicacion, 
                                (SELECT COUNT(l.idUsuario) 
                                FROM likepublicacion l
                                WHERE l.idPublicacion = p.idPublicacion) AS likes,
                                (SELECT COUNT(c.id)
                                FROM comentario c
                                WHERE c.idPublicacion = p.idPublicacion AND c.estatus = true) AS comentarios
                                FROM vw_PublicacionesInfo p WHERE idMundial = ? AND estatus = true ORDER BY likes DESC");
            $stmt->execute([$idMundial]);
            $publicaciones = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($publicaciones as &$p) {
                
                if ($p['multimedia']) $p['multimedia'] = base64_encode($p['multimedia']);
                if ($p['fotoUsuario']) $p['fotoUsuario'] = base64_encode($p['fotoUsuario']);
            }
            return [
                'success' => true,
                'data' => $publicaciones
                ];
        }catch(PDOException $e){
            return[
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    public static function listarPorMundialByComentarios($idMundial) {
        try{
            $db = Database::connect();
            $stmt = $db->prepare("
                                SELECT p.idPublicacion, p.idMundial, p.nombreMundial, p.fechaMundial, p.idUsuario, p.nombreUsuario, p.apellidoUsuario, p.fotoUsuario,
                                p.idCategoria, p.nombreCategoria, p.paisMencionado, p.descripcion, p.multimedia, p.estatus, p.fechaCreacion, p.fechaAprobacion, p.vistas, 
                                p.tipoPublicacion, 
                                (SELECT COUNT(l.idUsuario) 
                                FROM likepublicacion l
                                WHERE l.idPublicacion = p.idPublicacion) AS likes,
                                (SELECT COUNT(c.id)
                                FROM comentario c
                                WHERE c.idPublicacion = p.idPublicacion AND c.estatus = true) AS comentarios
                                FROM vw_PublicacionesInfo p WHERE idMundial = ? AND estatus = true ORDER BY comentarios DESC");
            $stmt->execute([$idMundial]);
            $publicaciones = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($publicaciones as &$p) {
                
                if ($p['multimedia']) $p['multimedia'] = base64_encode($p['multimedia']);
                if ($p['fotoUsuario']) $p['fotoUsuario'] = base64_encode($p['fotoUsuario']);
            }
            return [
                'success' => true,
                'data' => $publicaciones
            ];
        }catch(PDOException $e){
            return[
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    public static function listarPorMundialByPais($idMundial, $pais) {
        try{
            $db = Database::connect();
            $stmt = $db->prepare("
                                SELECT p.idPublicacion, p.idMundial, p.nombreMundial, p.fechaMundial, p.idUsuario, p.nombreUsuario, p.apellidoUsuario, p.fotoUsuario,
                                p.idCategoria, p.nombreCategoria, p.paisMencionado, p.descripcion, p.multimedia, p.estatus, p.fechaCreacion, p.fechaAprobacion, p.vistas, 
                                p.tipoPublicacion, 
                                (SELECT COUNT(l.idUsuario) 
                                FROM likepublicacion l
                                WHERE l.idPublicacion = p.idPublicacion) AS likes,
                                (SELECT COUNT(c.id)
                                FROM comentario c
                                WHERE c.idPublicacion = p.idPublicacion AND c.estatus = true) AS comentarios
                                FROM vw_PublicacionesInfo p WHERE idMundial = ? AND paisMencionado = ? AND estatus = true ORDER BY fechaCreacion DESC");
            $stmt->execute([$idMundial, $pais]);
            $publicaciones = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($publicaciones as &$p) {
                
                if ($p['multimedia']) $p['multimedia'] = base64_encode($p['multimedia']);
                if ($p['fotoUsuario']) $p['fotoUsuario'] = base64_encode($p['fotoUsuario']);
            }
            return [
                'success' => true,
                'data' => $publicaciones
            ];
        }catch(PDOException $e){
            return[
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    public static function listarPorUsuario($idUsuario) {


        $db = Database::connect();
        
        $stmt = $db->prepare("
                            SELECT idPublicacion, idMundial, nombreMundial, fechaMundial, idUsuario, nombreUsuario, apellidoUsuario, fotoUsuario,
                            idCategoria, nombreCategoria, paisMencionado, descripcion, multimedia, estatus, fechaCreacion, fechaAprobacion, vistas, 
                            tipoPublicacion
                            FROM vw_PublicacionesInfo WHERE idUsuario = ? AND estatus = true ORDER BY fechaCreacion DESC
                            ");
        $stmt->execute([$idUsuario]);
        $publicaciones = $stmt->fetchAll(PDO::FETCH_ASSOC);

        
        foreach ($publicaciones as &$p) {
            $p['likes'] = self::obtenerLikes($p['idPublicacion']);
            $p['comentarios'] = self::obtenerTotalComentarios($p['idPublicacion']);
            if ($p['multimedia']) $p['multimedia'] = base64_encode($p['multimedia']);
            if ($p['fotoUsuario']) $p['fotoUsuario'] = base64_encode($p['fotoUsuario']);
        }
        return $publicaciones;
    }

    public static function listarPorPendientes() {
        

        $db = Database::connect();
        
        $stmt = $db->query("
                        SELECT idPublicacion, idMundial, nombreMundial, fechaMundial, idUsuario, nombreUsuario, apellidoUsuario, fotoUsuario,
                        idCategoria, nombreCategoria, paisMencionado, descripcion, multimedia, estatus, fechaCreacion, fechaAprobacion, vistas, 
                        tipoPublicacion
                        FROM vw_PublicacionesInfo  WHERE estatus = false ORDER BY fechaCreacion DESC");
        $publicaciones = $stmt->fetchAll(PDO::FETCH_ASSOC);

        
        foreach ($publicaciones as &$p) {
            $p['likes'] = self::obtenerLikes($p['idPublicacion']);
            $p['comentarios'] = self::obtenerTotalComentarios($p['idPublicacion']);
            if ($p['multimedia']) $p['multimedia'] = base64_encode($p['multimedia']);
            if ($p['fotoUsuario']) $p['fotoUsuario'] = base64_encode($p['fotoUsuario']);
        }
            
        return $publicaciones;
    }

    public static function listarPaises($idMundial){
        try{
            $db = Database::connect();
            $stmt = $db->prepare("CALL sp_ConsultarPaisesPublicacionPorMundial(?)");
            $stmt->execute([
                $idMundial
            ]);
            $paises = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return[
                'success' => true,
                'data' => $paises
            ];

        }catch(PDOException $e){
            return[
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    private static function obtenerLikes($id) {
        $db = Database::connect();
        $stmt = $db->prepare("CALL sp_ConsultaLikePorPublicacion(?)");
        $stmt->execute([$id]);
        $res = $stmt->fetch();
        return $res['totalLikes'] ?? 0;
    }

    private static function obtenerTotalComentarios($id) {
        $db = Database::connect();
        $stmt = $db->prepare("CALL sp_ContarComentariosPorPublicacion(?)");
        $stmt->execute([$id]);
        $res = $stmt->fetch();
        return $res['totalComentarios'] ?? 0;
    }

    public static function obtenerPublicacionPorId($id){
        try{
            $db = Database::connect();

            $stmt = $db->prepare("
                CALL sp_ConsultarPublicacionPorId(?)
            ");

            $stmt->execute([$id]);
            $publicacion = $stmt->fetch();

            if(!$publicacion){
                return [
                    'error' => true,
                    'mensaje' => 'No existe esta publicación'
                ];
            }
            if($publicacion['estatus'] === 1 ){
                $publicacion['likes'] = self::obtenerLikes($id);
                $publicacion['comentarios'] = self::obtenerTotalComentarios($id); 
                return $publicacion;
            }
            else{
                return [
                    'error' => true,
                    'mensaje' => 'Permisos insuficientes'
                ];
            }
            
        }catch(PDOException $e){
            return [
                'error' => true,
                'mensaje' => $e->getMessage()
            ];
        }
    }

    public static function crear($data) {
        $db = Database::connect();
        
        $stmt = $db->prepare("CALL sp_RegistrarPublicacion(?, ?, ?, ?, ?, ?, ?)");
        
        return $stmt->execute([
            $data['idMundial'],
            $data['idUsuario'],
            $data['idCategoria'],
            $data['pais'] ?? '', 
            $data['descripcion'], 
            $data['multimedia'],
            $data['tipoPublicacion']
        ]);
    }

    public static function aprobar($id){
        try {
            $db = Database::connect();
            $stmt = $db->prepare("CALL sp_AprobarPublicacion(?)");
            $stmt->execute([$id]);

            return true;

        } catch (PDOException $e) {
            return [
                'error' => true,
                'mensaje' => $e->getMessage()
            ];
        }
    }

    public static function eliminar($id){
        try {
            $db = Database::connect();
            $stmt = $db->prepare("CALL sp_EliminarPublicacion(?)");
            $stmt->execute([$id]);

            return true;

        } catch (PDOException $e) {
            return [
                'error' => true,
                'mensaje' => $e->getMessage()
            ];
        }
    }

    public static function agregarVista($id){
        try{
            $db = Database::connect();
            $stmt = $db->prepare("CALL sp_NuevaVista(?)");
            $stmt->execute([$id]);

            return true;
        } catch (PDOException $e){
            return [
                'error' => true,
                'message' => $e->getMessage()
            ];
        }
    }

}