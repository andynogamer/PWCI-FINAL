<?php
require_once __DIR__ . '/../config/database.php';

class Publicacion {
    public static function listarPorMundial($idMundial) {
        $db = Database::connect();
        // Usamos la vista vw_PublicacionesInfo para traer datos de usuario y categoría
        $stmt = $db->prepare("SELECT * FROM vw_PublicacionesInfo WHERE idMundial = ? AND estatus = true ORDER BY fechaCreacion DESC");
        $stmt->execute([$idMundial]);
        $publicaciones = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Agregamos likes y comentarios llamando a los SP existentes
        foreach ($publicaciones as &$p) {
            $p['likes'] = self::obtenerLikes($p['idPublicacion']);
            $p['comentarios'] = self::obtenerTotalComentarios($p['idPublicacion']);
            if ($p['multimedia']) $p['multimedia'] = base64_encode($p['multimedia']);
        }
        return $publicaciones;
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
}