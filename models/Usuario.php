<?php 

namespace Model;

class Usuario extends ActiveRecord {

    protected static $tabla = 'usuarios';
    protected static $columnasDB = ['id', 'nombre', 'email', 'password', 'token', 'confirmado'];
   
    public $id;
    public $nombre;
    public $email;
    public $password; 
    public $password2;   
    public $password_actual;   
    public $password_nuevo;    
    public $password_nuevo2;   
    public $token;
    public $confirmado;
    
    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->email = $args['email'] ?? '';
        $this->password = $args['password'] ?? '';
        $this->password2 = $args['password2'] ?? '';
        $this->password_actual = $args['password_actual'] ?? '';
        $this->password_nuevo = $args['password_nuevo'] ?? '';
        $this->password_nuevo2 = $args['password_nuevo2'] ?? '';
        $this->token = $args['token'] ?? '';
        $this->confirmado = $args['confirmado'] ?? 0;
    }

    // Validar el Login de Usuarios
    public function validarLogin() : array {
        
        if(!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            self::$alertas['error'][] = 'E-mail no válido!';
        }
        if(!$this->email) {
            self::$alertas['error'][] ='El E-mail es Obligatorio!';
        }

        if(!$this->password) {
            self::$alertas['error'][] ='El Password no puede ir Vacio!';
        }
        return self::$alertas;
    }

    // Validación para cuentas nuevas
    public function validarNuevaCuenta() : array
    {
        if(!$this->nombre) {
            self::$alertas['error'][] ='El Nombre es Obligatorio!';
        }

        if(!$this->email) {
            self::$alertas['error'][] ='El E-mail es Obligatorio!';
        }

        if(!$this->password) {
            self::$alertas['error'][] ='El Password no puede ir Vacio!';
        }
        if(strlen($this->password) <6 ) {
            self::$alertas['error'][] ='El Password debe Contener al menos 6 Caracteres!';
        }
        if($this->password !== $this->password2) {
            self::$alertas['error'][] ='Los Passwords son diferentes!';
        }

        return self::$alertas;
    }

    // Valida un email
    public function validarEmail() : array {
        if(!$this->email) {
            self::$alertas['error'][] = 'El E-mail es Obligatorio!';
        }
        if(!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            self::$alertas['error'][] = 'E-mail no válido!';
        }
        return self::$alertas;
    }

    // Valida el Password
    public function ValidarPassword() : array {
        if(!$this->password) {
            self::$alertas['error'][] ='El Password no puede ir Vacio!';
        }
        if(strlen($this->password) <6 ) {
            self::$alertas['error'][] ='El Password debe Contener al menos 6 Caracteres!';
        }
        if($this->password !== $this->password2) {
            self::$alertas['error'][] ='Los Passwords son diferentes!';
        }
        
        return self::$alertas;
    }

    public function validar_perfil() : array {
        if(!$this->nombre) {
            self::$alertas['error'][] ='El Nombre es Obligatorio!';
        }
        if(!$this->email) {
            self::$alertas['error'][] = 'El E-mail es Obligatorio!';
        }
        if(!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            self::$alertas['error'][] = 'E-mail no válido!';
        }
        return self::$alertas;
    }

    public function nuevoPassword() : array {
        if(!$this->password_actual) {
            self::$alertas['error'][] = 'El Password Actual no puede ir Vacio!';
        }
        if(!$this->password_nuevo) {
            self::$alertas['error'][] = 'El Password Nuevo no puede ir Vacio!';
        }
        if(strlen($this->password_nuevo) <6 ) {
            self::$alertas['error'][] ='El Password debe Contener al menos 6 Caracteres!';
        }
        if($this->password_nuevo !== $this->password_nuevo2) {
            self::$alertas['error'][] ='Los Passwords Nuevos son diferentes!';
        }
        return self::$alertas;
    }

    // Comprobar el password
    public function comprobar_password() : bool {
        return password_verify($this->password_actual, $this->password );
    }

    // Hashea el password
    public function hashPassword() : void {
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);
    }

    // Generar un Token
    public function crearToken() : void {
        $this->token = uniqid();
    }
}