<?php
require '../app/controller/AuthController.php';

$action = $_GET['action'] ?? 'login';

switch ($action) {

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