<?php
require_once __DIR__ . '/../config/database.php';

class Usuario {

    public static function crear($data) {
        $db = Database::connect();

        $stmt = $db->prepare("
            INSERT INTO usuario 
            (tipoUsuario, nombre, apellido, fechaNacimiento, foto, genero,
             paisNacimiento, nacionalidad, correoElectronico, contrasena)
            VALUES (1, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");

        return $stmt->execute([
            $data['nombre'],
            $data['apellido'],
            $data['fechaNacimiento'],
            $data['foto'],
            $data['genero'],
            $data['paisNacimiento'],
            $data['nacionalidad'],
            $data['correo'],
            password_hash($data['contrasena'], PASSWORD_DEFAULT)
        ]);
    }

    public static function login($correo) {
        $db = Database::connect();

        $stmt = $db->prepare("
            SELECT * FROM usuario WHERE correoElectronico = ?
        ");

        $stmt->execute([$correo]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    
}