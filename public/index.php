<?php
require_once '../app/controller/AuthController.php';
require_once '../app/controller/MundialController.php'; 

$action = $_GET['action'] ?? 'mundiales'; 

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
}