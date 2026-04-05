<?php
class AuthMiddleware {
    public static function verificar($action) {
        if (session_status() === PHP_SESSION_NONE) { session_start(); }

        // Detectamos si es una petición de API
        $esApi = (strpos($action, 'api_') === 0);
        $rutasPublicas = ['login', 'register', 'mundiales', 'api_get_mundiales', 'foro', 'api_get_publicaciones', 'api_get_categorias', 'perfil' ];
        $rutasAdmin = ['admin_mundiales', 'admin_categorias', 'api_crear_categoria'];

        // 1. Validación de Sesión
        if (!in_array($action, $rutasPublicas) && !isset($_SESSION['user'])) {
            if ($esApi) {
                header('Content-Type: application/json');
                http_response_code(401);
                echo json_encode(['error' => 'No autorizado']);
                exit;
            }
            header("Location: index.php?action=login");
            exit;
        }

        // 2. Validación de Admin
        if (in_array($action, $rutasAdmin)) {
            if (!isset($_SESSION['user']) || $_SESSION['user']['tipoUsuario'] != 2) {
                if ($esApi) {
                    header('Content-Type: application/json');
                    http_response_code(403);
                    echo json_encode(['error' => 'Permisos insuficientes']);
                    exit;
                }
                header("Location: index.php?action=mundiales");
                exit;
            }
        }
    }
}