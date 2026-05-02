<?php
require_once __DIR__ . '/../model/Usuario.php';


class UsuarioController{

    public function perfil() {
            
        if(!isset($_SESSION['user'])){
            header("Location: index.php?action=login"); exit;
        }


        require_once __DIR__ . '/../view/perfil.php';
    }

    public function modificarPerfil(){
        if(!isset($_SESSION['user'])){
            header("Location: index.php?action=login"); exit;
        }
        $errormsg = null;
        $user = null;

        $response = Usuario::getUsuarioById();
        if($response['success']){
            $user = $response['data'];
        }else{
            $errormsg = $response['message'];
        }
        if($errormsg == null){
            require_once __DIR__ . '/../view/perfil_update.php';
        }
        
    }

    public function modificarContrasena(){
        if(!isset($_SESSION['user']) && !isset($_GET['id']) && $_GET['id'] != $_SESSION['user']['id']){
            header("Location: index.php?action=login"); exit;
        }
        
        require_once __DIR__ . '/../view/contrasena_form.php';
    }

    public function newContrasena(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            
            
            header('Content-Type: application/json');
            
            if(!isset($_SESSION['user'])){
                echo json_encode(['success' => false, 'error' => 'Acceso no autorizado'], JSON_UNESCAPED_UNICODE);
                exit;
            }
            if(!password_verify($_POST['old_password'], $_SESSION['user']['contrasena'])){
                echo json_encode(['success' => false, 'error' => 'Tu contraseña anterior no coincide con la que ingresaste'], JSON_UNESCAPED_UNICODE);
                exit;
            }
            if($_POST['new_password'] != $_POST['new_password_repeat']){
                echo json_encode(['success' => false, 'error' => 'Las contraseñas nuevas no coinciden'], JSON_UNESCAPED_UNICODE);
                exit;
            }

            $contrasena = $_POST['new_password'];

            if (!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/", $contrasena)) {
                echo json_encode(['success' => false, 'error' => 'La contraseña debe tener mínimo 8 caracteres, mayúscula, minúscula, número y símbolo'], JSON_UNESCAPED_UNICODE);
                exit;
            }
            $resultado = Usuario::updateContrasena($contrasena);

            

            
            
            if ($resultado['success']) {
                echo json_encode(['success' => true], JSON_UNESCAPED_UNICODE);
                exit;
            } else {
                echo json_encode(['success' => false, 'error' => "Error interno al registrar usuario en la base de datos." . $resultado['message']], JSON_UNESCAPED_UNICODE);
                exit;
            }
        }
        require_once __DIR__ . '/../view/contrasena_form.php';
    }

}

