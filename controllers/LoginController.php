<?php

namespace Controllers;

use Classes\Email;
use Model\Usuario;
use MVC\Router;

class LoginController {
    public static function login( Router $router ) {
        
        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario = new Usuario($_POST);

           $alertas = $usuario->validarLogin();

           if(empty($alertas)) { 
             //Verificar que el usuario exista
             $usuario = Usuario::where('email', $usuario->email);

             if(!$usuario || !$usuario->confirmado) {
                Usuario::setAlerta('error', 'El Usuario No existe! o No esta Confirmado!');
             } else {
                // El Usuario existe
                if( password_verify($_POST['password'], $usuario->password)) {
                    
                    // Inicar Sesión
                    iniciaSesion();
                    $_SESSION['id'] = $usuario->id;
                    $_SESSION['nombre'] = $usuario->nombre;
                    $_SESSION['email'] = $usuario->email;
                    $_SESSION['login'] = true;

                    // Redireccionar
                    header('Location: /dashboard');

                } else {
                    Usuario::setAlerta('error', 'Password Incorrecto!');
                }
             }
           }
        }

        $alertas = Usuario::getAlertas();
        // Render a la Vista
        $router->render('auth/login', [
            'titulo' => 'Iniciar Sesión',
            'alertas' => $alertas
        ]);
    }

    public static function logout() {
        iniciaSesion();
        $_SESSION = [];
        header('Location: /');
    }

    public static function crear( Router $router ) {
        $alertas = [];
        $usuario = new Usuario;

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario->sincronizar($_POST);
            $alertas = $usuario->validarNuevaCuenta();
            
            if(empty($alertas)) {
                $existeUsuario = Usuario::where('email', $usuario->email);
                if($existeUsuario) {
                    Usuario::setAlerta('error', 'El usuario ya esta registrado!');
                    $alertas = Usuario::getAlertas();
                } else {
                    // Hashear el password
                    $usuario->hashPassword();

                    // Eliminar password2
                    unset($usuario->password2);

                    // Generar el Token
                    $usuario->crearToken();

                    // Crear un nuevo usuario
                    $resultado =$usuario->guardar();

                    // Enviar email
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                    $email->enviarConfirmacion();

                    if($resultado) {
                        header('Location: /mensaje');
                    }
                }
            }
        }

        $alertas = Usuario::getAlertas();
        // Render a la Vista
        $router->render('auth/crear', [
            'titulo' => 'Crea tu cuenta en UpTask',
            'usuario' => $usuario,
            'alertas' => $alertas
        ]);
    }

    public static function olvide( Router $router) {

        $alertas = [];
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario = new Usuario($_POST);
            $alertas = $usuario->validarEmail();

            if(empty($alertas)) {
                // Buscar el Usuario
                $usuario = Usuario::where('email', $usuario->email);

                if($usuario && $usuario->confirmado) {
                    
                    // Generar un nuevo Token
                    $usuario->crearToken();
                    unset($usuario->password2);

                    // Actualizar el usuario
                    $usuario->guardar();

                    // Enviar el email
                    $email = new Email($usuario->email, $usuario->nombre , $usuario->token);
                    $email->enviarInstrucciones();

                    // Imprimir la alerta
                    Usuario::setAlerta('exito', 'Hemos enviado las instrucciones a tu email!');
                } else {
                    Usuario::setAlerta('error', 'El usuario no Existe o No esta Confirmado!');
                }
            }
        }

        $alertas = Usuario::getAlertas();
        // Muestra la Vista
        $router->render('auth/olvide', [
            'titulo' => 'Recupera tu cuenta',
            'alertas' => $alertas
        ]);
    }

    public static function reestablecer( Router $router) {

        $token = s($_GET['token']);
        $mostrar = true;
        
        if(!$token) header('Location: /');

        // Identificar el usuario con este token
        $usuario = Usuario::where('token', $token);
        
        if(empty($usuario)) {
            Usuario::setAlerta('error', 'Token No Válido!');
            $mostrar = false;
        }

        if($_SERVER['REQUEST_METHOD'] === 'POST') {

            // Añadir el nuevo password
            $usuario->sincronizar($_POST);

            // Validar el password
            $alertas = $usuario->validarPassword();
            
            if(empty($alertas)) {
                // Hashear el nuevo password
                $usuario->hashPassword();
                unset($usuario->password2);
                unset($usuario->password_actual);
                unset($usuario->password_nuevo);
                unset($usuario->password_nuevo2);
                
                // Eliminar el Token
                $usuario->token = '';

                // Guardar el usuario en la BD
                $resultado = $usuario->guardar();
                Usuario::setAlerta('exito', 'Password Actualizado Correctamente!');
                // Redireccionar
                if($resultado) {
                    header('Refresh: 3; url= /');
                }             
            }
        }

        $alertas = Usuario::getAlertas();
        // Muestra la vista
        $router->render('auth/reestablecer', [
            'titulo' => 'Reestablecer Password',
            'alertas' => $alertas,
            'mostrar' => $mostrar
        ]);
    }

    public static function mensaje( Router $router ) {

        // Muestra la vista
        $router->render('auth/mensaje', [
            'titulo' => 'Cuenta Creada Exitosamente'
        ]);
    }

    public static function confirmar( Router $router ) {

        $token = s($_GET['token']);

        if(!$token) header('Location: /');

        // Encontrar al Usuario con este Token
        $usuario = Usuario::where('token', $token);
        
        if(empty($usuario)) {
            // No se encontró un usuario con ese token
            Usuario::setAlerta('error', 'Token No Válido!');
        } else {
            // Confirmar Cuenta
            $usuario->confirmado = 1;
            $usuario->token = '';
            unset($usuario->password2);
            
            // Guardar en la BD
            $usuario->guardar();

            Usuario::setAlerta('exito', 'Cuenta Comprobada satisfactoriamente!');
            // Redireccionar
            header('Refresh: 3; url= /');
        }

        $alertas = Usuario::getAlertas();
        // Muestra la vista
        $router->render('auth/confirmar', [
            'titulos' => 'Confirma tu cuenta UpTask',
            'alertas' => $alertas
        ]);
    }


}

