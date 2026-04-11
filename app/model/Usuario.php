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

    public static function validarUsuario($data){

        // 🔹 Validar existencia de campos obligatorios
        $camposObligatorios = [
            'nombre', 'apellido', 'fechaNacimiento',
            'genero', 'correo', 'contrasena', 'paisNacimiento', 'nacionalidad'
        ];

        foreach ($camposObligatorios as $campo) {
            if (!isset($data[$campo]) || empty(trim($data[$campo]))) {
                return ['error', "El campo $campo es obligatorio"];
            }
        }

        
        $nombre = mb_strtoupper(trim($data['nombre']));
        $apellido = mb_strtoupper(trim($data['apellido']));
        $correo = strtolower(trim($data['correo']));
        $contrasena = $data['contrasena'];
        $fechaNacimiento = $data['fechaNacimiento'];
        $genero = strtoupper(trim($data['genero']));
        $paisNacimiento = mb_strtoupper(trim($data['paisNacimiento']));
        $nacionalidad = mb_strtoupper(trim($data['nacionalidad']));

        
        if (!preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/u", $nombre)) {
            return ['error', 'El nombre no debe contener números'];
        }

        if (!preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/u", $apellido)) {
            return ['error', 'El apellido no debe contener números'];
        }

        
        if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            return ['error', 'Correo electrónico inválido'];
        }

        
        if (!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/", $contrasena)) {
            return ['error', 'La contraseña debe tener mínimo 8 caracteres, mayúscula, minúscula, número y símbolo'];
        }
        if (!in_array($genero, ['M', 'F'])) {
            return ['error', 'El género debe ser Masculino o Femenino'];
        }

        
        try {
            $fecha = new DateTime($fechaNacimiento);
            $hoy = new DateTime();
            $edad = $hoy->diff($fecha)->y;

            if ($edad < 12) {
                return ['error', 'Debes tener al menos 12 años'];
            }

        } catch (Exception $e) {
            return ['error', 'Fecha de nacimiento inválida'];
        }

        
        if (!preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/u", $paisNacimiento)) {
            return ['error', 'País de nacimiento inválido'];
        }

        if (!preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/u", $nacionalidad)) {
            return ['error', 'Nacionalidad inválida'];
        }

        return ['success', 'Registro válido'];
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