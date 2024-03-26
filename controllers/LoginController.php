<?php 

namespace Controllers;

use Classes\Email;
use Model\Usuario;
use MVC\Router;

class LoginController {

    public static function login( Router $router ) {
        $alertas = [];

        if($_SERVER["REQUEST_METHOD"]=== "POST") {
            $usuario = new Usuario($_POST);
            $alertas = $usuario->validarLogin();

            if(empty($alertas)) {
                // Validar que el usuario exista 
                $usuario = Usuario::where("email", $usuario->email);
                
                if(!$usuario || !$usuario->confirmado) {
                    Usuario::setAlerta("error", "El Usuario No Existe o no estas confirmado");
                }else {
                    // El usuario existe
                    if( password_verify($_POST["password"], $usuario->password) ) {

                        // Iniciar Sesion
                        session_start();
                        $_SESSION["id"] = $usuario->id;
                        $_SESSION["nombre"] = $usuario->nombre;
                        $_SESSION["email"] = $usuario->email;
                        $_SESSION["login"] = true;
                        
                        header("location: /dashboard");

                    }else {
                        Usuario::setAlerta("error", "Contraseña es Incorrecta");
                    }
                }               
            }
        }

        $alertas = Usuario::getAlertas();
        // Render a la vista
        $router->render("auth/login", [
            "titulo" => "Iniciar Sesión",
            "alertas" => $alertas
        ]);
    }

    public static function logout() {
        session_start();
        $_SESSION = [];
        header("location: /");
    }

    public static function crear( Router $router ) {
        $alertas = [];
        $usuario = new Usuario;

        if($_SERVER["REQUEST_METHOD"]=== "POST") {          
            $usuario->sincronizar($_POST);
            $alertas = $usuario->validarNuevaCuenta();

            if(!empty($alertas)) {
                $existeUsuario = Usuario::where("email", $usuario->email);
                
                if($existeUsuario) {
                    Usuario::setAlerta("error","El Usuario ya esta registrado");
                    $alertas = Usuario::getAlertas();
                }
            } else {
                // Hashear password
                $usuario->hashPassword();

                // Eliminar password2
                unset($usuario->password2);

                // Generar Token
                $usuario->crearToken();
                
                // Guardar usuario
                $resultado = $usuario->guardar();

                // Enviar email
                $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                $email->enviarConfirmacion();

                if($resultado) {
                    header("location: /mensaje");
                }
            }
        }

          // Render a la vista
          $router->render("auth/crear", [
             "titulo" => "Crear Cuenta",
             "usuario" => $usuario,
             "alertas" => $alertas,
        ]);
    }

    public static function olvide( Router $router ) {
        $alertas = [];

        if($_SERVER["REQUEST_METHOD"]=== "POST") {
            $usuario = new Usuario($_POST);
            $alertas = $usuario->validarEmail();

            if(empty($alertas)) {
                // Buscar el usuario por su email
                $usuario = Usuario::where("email",$usuario->email);

                if($usuario && $usuario->confirmado) {
                    // Generar Token
                    $usuario->crearToken();
                    unset($usuario->password2);
                    // Actualizar el usuario
                    $usuario->guardar();

                    // Enviar el email
                    $email = new Email($usuario->email,$usuario->nombre,$usuario->token);
                    $email->enviarInstrucciones();

                    // Imprimir la alerta
                    Usuario::setAlerta("exito", "Hemos enviado las intrucciones a tu email");
                } else {
                    Usuario::setAlerta("error", "El Usuario no existe o no esta confirmado");
                }
                
                
            }
        }
            $alertas = Usuario::getAlertas();
        $router->render("auth/olvide", [
            "titulo" => "Recuperar Contraseña",
            "alertas" => $alertas
        ]);
    }

    public static function reestablecer( Router $router ) {
        $alertas = [];
        $token = s($_GET["token"]);
        $mostrar = true;

        if(!$token) header("location:/");
        
        // Identificar usuario con el token
        $usuario = Usuario::where("token", $token);
        if(empty($usuario)) {
            // No se encontro usuario con ese token
            Usuario::setAlerta("error","Token Inválido");
            $mostrar = false;
        }

        if($_SERVER["REQUEST_METHOD"]=== "POST") {
            $usuario->sincronizar($_POST);
            $alertas = $usuario->validarNuevoPassword();

            if(empty($alertas)) {
                // Hashear password
                $usuario->hashPassword();

                // Eliminar password2 y token
                $usuario->token = "";
                unset($usuario->password2);

                // Guardar password
                $resultado = $usuario->guardar();

                if($resultado) header("location: /");
                
            }
        }

            $alertas = Usuario::getAlertas();
        $router->render("auth/reestablecer", [
            "titulo" => "Reestablecer Contraseña",
            "alertas" => $alertas,
            "mostrar" => $mostrar
        ]);
    }

    public static function mensaje( Router $router ) {

        $router->render("auth/mensaje", [
            "titulo" => "Cuenta Creada exitosamente"
        ]);
    }

    public static function confirmar( Router $router ) {

        $token = s($_GET["token"]);
        
            if(!$token) {
                header("location:/");
            }

        // Encontrar al Usuario
        $usuario = Usuario::where("token",$token);
            if(empty($usuario)) {
                // No se encontro usuario con ese token
                Usuario::setAlerta("error","Token Inválido");
            }else {
                // Confirmar la cuenta 
                $usuario->confirmado = 1;
                $usuario->token = "";
                unset($usuario->password2);

                // Guardar en la BD
                $usuario->guardar();
                Usuario::setAlerta("exito","Cuenta Confirmada Correctamente");
            }

        $alertas = Usuario::getAlertas();
        
        $router->render("auth/confirmar", [
            "titulo" => "Confirmar Cuenta",
            "alertas" => $alertas
        ]);
    }
}
