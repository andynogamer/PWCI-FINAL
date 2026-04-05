<?php


require_once '../app/controller/AuthController.php';
require_once '../app/controller/MundialController.php';
require_once '../app/controller/ApiController.php'; 
require_once '../app/controller/PublicacionController.php'; 
require_once '../app/middleware/AuthMiddleware.php'; 
require_once '../app/controller/UsuarioController.php';


$action = $_GET['action'] ?? 'mundiales';

AuthMiddleware::verificar($action);


switch ($action) {

    case 'perfil':
        (new UsuarioController())->perfil();

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

    
    default:
        header("Location: index.php?action=mundiales");
        break;
}