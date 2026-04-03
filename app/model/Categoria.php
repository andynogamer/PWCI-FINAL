<?php
require_once __DIR__ . '/../config/database.php';

class Categoria {
    public static function listar() {
        $db = Database::connect();
        $stmt = $db->query("CALL sp_ConsultaCategorias()");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function crear($nombre) {
        $db = Database::connect();
        $stmt = $db->prepare("CALL sp_RegistrarCategoria(?)");
        return $stmt->execute([$nombre]);
    }
}