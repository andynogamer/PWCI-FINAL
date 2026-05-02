<?php


require_once '../app/controller/AuthController.php';
require_once '../app/controller/MundialController.php';
require_once '../app/controller/ApiController.php'; 
require_once '../app/controller/PublicacionController.php'; 
require_once '../app/middleware/AuthMiddleware.php'; 
require_once '../app/controller/UsuarioController.php';
require_once '../app/controller/ComentarioController.php'; 
require_once '../app/controller/LikeController.php'; 


$action = $_GET['action'] ?? 'mundiales';

AuthMiddleware::verificar($action);


switch ($action) {

    case 'perfil':
        (new UsuarioController())->perfil();
        break;

    case 'foro':
        (new MundialController())->foro();
        break;    
    
    case 'mundiales':
       
        (new MundialController())->index();
        break;

    case 'login':
        (new AuthController())->login();
        break;

    case 'register':
        (new AuthController())->register();
        break;

    case 'logout':
        (new AuthController())->logout();
        break;

    case 'admin_mundiales':
        (new MundialController())->adminMundiales();
        break;

    case 'admin_categorias':
        (new MundialController())->adminCategorias();
        break;

    case 'crear_publicacion':
        (new PublicacionController())->crearPublicacion();
        break;

    case 'admin_publicaciones':
        (new PublicacionController())->publicacionesPendientes();
        break;
        
    case 'admin_modificar_mundial':
        (new MundialController())->mundialPorid();
        break;
    case 'admin_post_modificar_mundial':
        (new MundialController())->modificarMundial();
        break;

    case 'publicacion':
        (new PublicacionController())->publicacionDetalle();
        break;

    case 'crear_comentario':
        (new ComentarioController())->crearComentario();
        break;
    
    case 'crear_like':
        (new LikeController())->crearLike();
        break;

    case 'modificar_perfil':
        (new UsuarioController())->modificarPerfil();
        break;

    

    //--API--
    case 'api_get_mundiales':
        
        (new ApiController())->getMundiales();
        break;

    case 'api_get_categorias':
        
        (new ApiController())->getCategorias();
        break;

    case 'api_get_publicaciones':

        (new ApiController())->getPublicacionesMundial();
        break;

    case 'api_get_publicaciones_usuario':
        (new ApiController())->getPublicacionesUsuario();
        break;

    case 'api_get_publicaciones_pendientes':
        (new ApiController())->getPublicacionesPendientes();
        break;

    case 'api_get_likes':
        (new ApiController())->getLikes();
        break;

    case 'api_update_publicacion_aprobada':
        (new ApiController())->updateToAprovePublicacion();
        break;
    case 'api_update_avatar':
        (new ApiController())->updateFotoPerfil();
        break;

    case 'api_post_like':
        (new ApiController())->postLike();
        break;

    case 'api_delete_mundial':
        (new ApiController())->deleteMundial();
        break;
    case 'api_delete_publicacion':
        (new ApiController())->deleteToAprovePublicacion();
        break;
        
    case 'api_delete_comentario':
        (new ApiController())->deleteComentario();
        break;

    case 'api_update_usuario':
        (new ApiController())->updatePerfil();
        break;

    case 'api_post_comentario':
        (new ApiController())->postComentario();
        break;

    case 'api_get_comentarios':
        (new ApiController())->getComentariosPorPublicacion();
        break;

    

    
    default:
        header("Location: index.php?action=mundiales");
        break;
}