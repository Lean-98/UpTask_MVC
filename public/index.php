<?php 

require_once __DIR__ . '/../includes/app.php';

use Controllers\DashboardController;
use Controllers\LoginController;
use Controllers\TareaController;
use MVC\Router;
$router = new Router();

// Login
$router->get('/', [LoginController::class, 'login']);
$router->post('/', [LoginController::class, 'login']);
$router->get('/logout', [LoginController::class, 'logout']);

// Crear Cuenta
$router->get('/crear', [LoginController::class, 'crear']);
$router->post('/crear', [LoginController::class, 'crear']);

// Formulario de Olvide mi password
$router->get('/olvide', [LoginController::class, 'olvide']);
$router->post('/olvide', [LoginController::class, 'olvide']);

// Colocar el new password
$router->get('/reestablecer', [LoginController::class, 'reestablecer']);
$router->post('/reestablecer', [LoginController::class, 'reestablecer']);

// Confirmación de Cuenta
$router->get('/mensaje', [LoginController::class, 'mensaje']);
$router->get('/confirmar', [LoginController::class, 'confirmar']);

// ZONA DE PROYECTOS
$router->get('/dashboard',[DashboardController::class, 'index']);
$router->get('/crear-proyecto',[DashboardController::class, 'crear_proyecto']);
$router->post('/crear-proyecto',[DashboardController::class, 'crear_proyecto']);
$router->get('/proyecto',[DashboardController::class, 'proyecto']);
$router->get('/perfil',[DashboardController::class, 'perfil']);
$router->post('/perfil',[DashboardController::class, 'perfil']);
$router->get('/cambiar-password',[DashboardController::class, 'cambiar_password']);
$router->post('/cambiar-password',[DashboardController::class, 'cambiar_password']);


// API para las tareas
$router->get('/api/tareas',[TareaController::class,'index']);
$router->post('/api/tarea',[TareaController::class,'crear']);
$router->post('/api/tarea/actualizar',[TareaController::class,'actualizar']);
$router->post('/api/tarea/eliminar',[TareaController::class,'eliminar']);
// $router->post('/api/tarea/eliminarProyecto',[TareaController::class,'eliminar']);

// Comprueba y valida las rutas, que existan y les asigna las funciones del Controlador
$router->comprobarRutas();