<?php
class AuthMiddleware {
    /**
     * Verifica si el usuario tiene permiso para realizar la acción.
     * @param string $action La acción solicitada en el index.php
     */
    public static function verificar($action) {
        if (session_status() === PHP_SESSION_NONE) { session_start(); }

        // Definimos qué acciones son para quién
        $rutasPublicas = ['login', 'register', 'mundiales'];
        $rutasAdmin = ['admin_mundiales', 'admin_categorias']; // Agregaremos más después

        // 1. Si la ruta NO es pública y el usuario no ha iniciado sesión
        if (!in_array($action, $rutasPublicas) && !isset($_SESSION['user'])) {
            header("Location: index.php?action=login");
            exit;
        }

        // 2. Si la ruta es exclusiva de ADMIN y el usuario no tiene nivel 2
        if (in_array($action, $rutasAdmin)) {
            if (!isset($_SESSION['user']) || $_SESSION['user']['tipoUsuario'] != 2) {
                // Redirigir a la landing si no tiene permisos
                header("Location: index.php?action=mundiales");
                exit;
            }
        }
    }
}