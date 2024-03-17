<?php 

namespace Model;

class Usuario extends ActiveRecord {

    protected static $tabla = "usuarios";
    protected static $columnasDB = ["id", "nombre", "email", "password", "token", "confirmado"];

    public function __construct($args = [])
    {
     $this->id = $args["id"] ?? null;   
     $this->nombre = $args["nombre"] ?? "";   
     $this->email = $args["email"] ?? "";   
     $this->password = $args["password"] ?? "";   
     $this->password2 = $args["password2"] ?? "";   
     $this->password_actual = $args["password_actual"] ?? "";   
     $this->password_nuevo = $args["password_nuevo"] ?? "";   
     $this->token = $args["token"] ?? "";   
     $this->confirmado = $args["confirmado"] ?? 0;   
    
    }
    // Validar login de usuarios
    public function validarLogin() {
        if(!$this->email) {
            self::$alertas["error"][] = "El Email es Obligatorio";
        }
        if(!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            self::$alertas["error"][] = "Email Inválido";
        }
        if(!$this->password) {
            self::$alertas["error"][] = "La Contraseña es Obligatoria";
        }

        return self::$alertas;
    }

    // Validacion de cuentas nuevas
    public function validarNuevaCuenta() {
        if(!$this->nombre) {
            self::$alertas["error"][] = "El Nombre es Obligatorio";
        }
        if(!$this->email) {
            self::$alertas["error"][] = "El Email es Obligatorio";
        }
        if(!$this->password) {
            self::$alertas["error"][] = "La Contraseña es Obligatoria";
        }
        if(strlen($this->password) < 6) {
            self::$alertas["error"][] = "La Contraseña debe contener al menos 6 caracteres";
        }
        if($this->password !== $this->password2) {
            self::$alertas["error"][] = "Las Contraseñas deben coincidir";
        }

            return self::$alertas;
    }

    // Validar Password nuevo
    public function validarNuevoPassword() {
        if(!$this->password) {
            self::$alertas["error"][] = "La Contraseña es Obligatoria";
        }
        if(strlen($this->password) < 6) {
            self::$alertas["error"][] = "La Contraseña debe contener al menos 6 caracteres";
        }
        if($this->password !== $this->password2) {
            self::$alertas["error"][] = "Las Contraseñas deben coincidir";
        }

            return self::$alertas;
    }

    
    // Comprobar el password
    public function comprobar_password() : bool {
        return password_verify($this->password_actual, $this->password);
    }

    // Hashear Password
    public function hashPassword() : void {
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);
    }

    
    // Generar un Token
    public function crearToken() : void{
        $this->token = uniqid();
    }

    // Valida Email
    public function validarEmail() {
        if(!$this->email) {
            self::$alertas["error"][] = "El Email es Obligatorio";
        }
        if(!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            self::$alertas["error"][] = "Email Inválido";
        }
            return self::$alertas;
    }

    public function validar_perfil() {
        if (!$this->nombre) {
            self::$alertas["error"][] = "El Nombre es Obligatiorio";
        }
        if (!$this->email) {
            self::$alertas["error"][] = "El Email es Obligatiorio";
        }
            return self::$alertas;
    }

    public function nuevoPassword() : array{
        if (!$this->password_actual) {
            self::$alertas["error"][] = "La Contraseña Actual es Obligatioria";
        }
        if (!$this->password_nuevo) {
            self::$alertas["error"][] = "La Contraseña Nuevo es Obligatioria";
        }
        if (strlen($this->password_nuevo) < 6) {
            self::$alertas["error"][] = "La Contraseña debe con tener al menos 6 Caracteres";
        }
            return self::$alertas;
    }


}