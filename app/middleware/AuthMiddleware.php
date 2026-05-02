<?php
class AuthMiddleware {
    public static function verificar($action) {
        if (session_status() === PHP_SESSION_NONE) { session_start(); }

        // Detectamos si es una petición de API
        $esApi = (strpos($action, 'api_') === 0);
        $rutasPublicas = ['update_contrasena','cambiar_contrasena','api_update_vistas','api_delete_publicacion','api_get_comentarios','api_post_comentario','api_update_usuario', 'modificar_perfil','api_update_avatar','api_post_like', 'api_get_likes' ,'login', 'crear_comentario', 'register', 'mundiales', 'api_get_mundiales', 'foro', 'api_get_publicaciones', 'api_get_categorias', 'perfil', 'publicacion' ];
        $rutasAdmin = ['api_delete_comentario','api_delete_mundial','admin_post_modificar_mundial','admin_modificar_mundial','admin_mundiales', 'admin_categorias', 'api_crear_categoria', 'api_get_publicaciones_pendientes', 'admin_publicaciones', 'api_update_publicacion_aprobada'];

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