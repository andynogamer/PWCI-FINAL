<?php
require_once __DIR__ . '/../model/Usuario.php';

class AuthController {

    public function login() {

        if ($_POST) {
            $user = Usuario::login($_POST['correo']);

            if ($user && password_verify($_POST['contrasena'], $user['contrasena'])) {

                session_start();
                $_SESSION['user'] = $user;

                header("Location: index.php?action=mundiales");
                exit;
            } else {
                echo "Credenciales incorrectas";
            }
        }

        
        require_once __DIR__ . '/../view/login.php';
    }

    public function register() {
        $error = null;

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $datos = $_POST;

            $resultado = Usuario::validarUsuario($datos);

            if ($resultado[0] === 'error') {
                $error = $resultado[1]; // guardas el mensaje
            } else {

                $fotoBinaria = null;

                if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
                    $fotoBinaria = file_get_contents($_FILES['foto']['tmp_name']);
                }

                $datos['foto'] = $fotoBinaria;

                if (Usuario::crear($datos)) {
                    header("Location: index.php?action=login");
                    exit;
                } else {
                    $error = "Error al registrar usuario";
                }
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