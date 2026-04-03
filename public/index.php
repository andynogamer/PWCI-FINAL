<?php
require_once '../app/controller/AuthController.php';
require_once '../app/controller/MundialController.php';
require_once '../app/middleware/AuthMiddleware.php'; // IMPORTANTE

$action = $_GET['action'] ?? 'mundiales';

// EJECUTAMOS EL MIDDLEWARE ANTES DEL SWITCH
AuthMiddleware::verificar($action);

switch ($action) {
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
    case 'admin_mundiales': // Nueva ruta protegida
        (new MundialController())->adminMundiales();
        break;
}