<?php 

namespace Controllers;

use Model\Proyecto;
use Model\Usuario;
use MVC\Router;

class DashboardController {

    public static function index ( Router $router ) {
        session_start();
        isAuth();

        $id = $_SESSION["id"];

        $proyectos = Proyecto::belongsTo("propietarioid", $id);

        $router->render ("dashboard/index" ,[
            "titulo"=> "Proyectos",
            "proyectos" => $proyectos
        ]);
    }

    public static function crear_proyecto ( Router $router ) {
        session_start();
        isAuth();
        $alertas = [];

        if($_SERVER["REQUEST_METHOD"] === "POST") {
            $proyecto = new Proyecto($_POST);

            // Validación
            $alertas = $proyecto->validarProyecto();

            if(empty($alertas)) {
                // Generar un URL unica
                $hash = md5(uniqid());
                $proyecto->url = $hash;

                // Almacenar el creador del proyecto
                $proyecto->propietarioid = $_SESSION["id"];
                // Guardar Proyecto
                $proyecto->guardar();
                // Reedireccionar
                header("location: /proyecto?url=" . $proyecto->url);
            }
        }

        
        $router->render ("dashboard/crear-proyecto" ,[
            "titulo"=> "Crear Proyecto",
            "alertas" => $alertas
        ]);
    }

    public static function perfil ( Router $router ) {
        session_start();
        isAuth();
        $alertas = [];

        $usuario = Usuario::find($_SESSION["id"]);

        if($_SERVER["REQUEST_METHOD"] === "POST") {

            $usuario->sincronizar($_POST);
            $alertas = $usuario->validar_perfil();

            if(empty($alertas)) {
                
                $existeUsuario = Usuario::where("email", $usuario->email);
                if($existeUsuario && $existeUsuario->id !== $usuario->id) {
                    // Mostrar error
                    Usuario::setAlerta("error", "El email ya esta vinculado a una cuenta");
                    $alertas = $usuario->getAlertas();

                } else {
                    // Guardar registro
                    $usuario->guardar();
    
                    Usuario::setAlerta("exito", "Guardado Correctamente");
                    $alertas = $usuario->getAlertas();
    
                    // Actualiza el nombre de la sesion y lo asigna a la barra
                    $_SESSION["nombre"] = $usuario->nombre;
                }

            }
        }

        $router->render ("dashboard/perfil" ,[
            "titulo"=> "Perfil",
            "usuario" => $usuario,
            "alertas" => $alertas
        ]);
    }

    public static function proyecto( Router $router ) {
        session_start();
        isAuth();

        $token = $_GET["url"];        
        if(!$token) header("location: /dashboard");

        // Revisar que la persona que visita el proyecto, es quien lo creo
        $proyecto = Proyecto::where("url", $token);
        if($proyecto->propietarioid !== $_SESSION["id"]) {
            header("location: /dashboard");
        }


        $router->render ("dashboard/proyecto" ,[
            "titulo"=> $proyecto->proyecto
        ]);
    }

    public static function cambiar_password( Router $router ) {
        session_start();
        isAuth();

        $alertas = [];

        if($_SERVER["REQUEST_METHOD"] === "POST") {
            $usuario = Usuario::find($_SESSION["id"]);
            $usuario->sincronizar($_POST);

            $alertas = $usuario->nuevoPassword();

            if(empty($alertas)) {
                $resultado = $usuario->comprobar_password();

                if($resultado) {

                    $usuario->password = $usuario->password_nuevo;
                    // Eliminar propiedades inecesarias
                    unset($usuario->password_actual);
                    unset($usuario->password_nuevo);

                    // Hashear el nuevo password
                    $usuario->hashPassword();

                    // Actualizar la nueva contraseña
                    $resultado = $usuario->guardar();

                    if($resultado) {
                        Usuario::setAlerta("exito", "Guardado Correctamente");
                        $alertas = $usuario->getAlertas();
                    }

                }else {
                    Usuario::setAlerta("error", "La contraseña actual no es valida");
                    $alertas = $usuario->getAlertas();
                }
            }
        }
        

        $router->render("dashboard/cambiar-password", [
            "titulo" => "Cambiar Contraseña",
            "alertas" => $alertas,
        ]);
    }
}