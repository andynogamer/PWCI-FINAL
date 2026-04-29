<?php
require_once __DIR__ . '/../model/Usuario.php';

class AuthController {

    public function login() {
        $errormsg = null;

        if ($_POST) {
            $user = Usuario::login($_POST['correo']);
            

            if(is_array($user) && isset($user['error'])){
                $errormsg = $user['mensaje'];
            }else{
                if ($user && password_verify($_POST['contrasena'], $user['contrasena'])) {

                    session_start();
                    $_SESSION['user'] = $user;

                    header("Location: index.php?action=mundiales");
                    exit;
                } else {
                    $errormsg = 'Las credenciales que ingresaste no son correctas';
                }
            }

            
        }

        
        require_once __DIR__ . '/../view/login.php';
    }

    public function register() {
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            
            
            header('Content-Type: application/json');
            
            $datos = $_POST;
            $resultado = Usuario::validarUsuario($datos);

            
            if ($resultado[0] === 'error') {
                echo json_encode(['success' => false, 'error' => $resultado[1]], JSON_UNESCAPED_UNICODE);
                exit; 
            } 
            
            
            $fotoBinaria = null;
            if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
                $fotoBinaria = file_get_contents($_FILES['foto']['tmp_name']);
            }

            $datos['foto'] = $fotoBinaria;

            $response = Usuario::crear($datos);
            if ($response['success']) {
                echo json_encode(['success' => true], JSON_UNESCAPED_UNICODE);
                exit;
            } else {
                echo json_encode(['success' => false, 'error' => "Error interno al registrar usuario en la base de datos." . $response['message']], JSON_UNESCAPED_UNICODE);
                exit;
            }
        }

        
        require_once __DIR__ . '/../view/register.php';
    }

    

    public function logout() {
        session_start();
        session_destroy();
        header("Location: index.php?action=login");
    }
}