<?php
// Incluimos la conexión a la base de datos
require_once __DIR__ . '/app/config/database.php';

try {
    $db = Database::connect();

    // Datos del administrador
    $nombre = "Admin";
    $apellido = "Sistema";
    $fechaNacimiento = "1990-01-01";
    $genero = "M";
    $pais = "México";
    $nacionalidad = "Mexicana";
    $correo = "admin@mundial.com";
    $password_plana = "admin123"; // Esta es la que usarás para loguearte
    $tipoUsuario = 2; // Tipo 2 para Administrador

    // Hasheamos la contraseña para que sea compatible con password_verify
    $password_hash = password_hash($password_plana, PASSWORD_DEFAULT);

    // Preparamos la inserción según tu tabla usuario
    $stmt = $db->prepare("
        INSERT INTO usuario 
        (tipoUsuario, nombre, apellido, fechaNacimiento, genero, paisNacimiento, nacionalidad, correoElectronico, contrasena)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");

    $stmt->execute([
        $tipoUsuario,
        $nombre,
        $apellido,
        $fechaNacimiento,
        $genero,
        $pais,
        $nacionalidad,
        $correo,
        $password_hash
    ]);

    echo "✅ Usuario Administrador creado con éxito.<br>";
    echo "📧 Correo: " . $correo . "<br>";
    echo "🔑 Contraseña: " . $password_plana;

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage();
}