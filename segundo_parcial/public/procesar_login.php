<?php
declare(strict_types=1);
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {    //Si el request no viene de POST, vuelve al login
    header("Location: login.php");
    exit();
}


/*Obtengo los datos del formulario con filter input, y lo limpio con trim*/
$usuario_login = filter_input(INPUT_POST, 'usuario', FILTER_SANITIZE_SPECIAL_CHARS);   
$clave_login = filter_input(INPUT_POST, 'clave');

if ($usuario_login !== null){
    $usuario_login = trim($usuario_login);
}

/*Valido que ambos campos esten completos */
if ($usuario_login === '' || $clave_login === '') {
    header("Location: login.php?error=1");
    exit();
}

// Valido que el usuario exista en la base de datos
if (!isset($_SESSION['usuarios_registrados'][$usuario_login])){
    header("Location: login.php?error=5");
    exit();
}

/*Guardo los datos que envie del array usuarios_registrados de procesar_registro en una base de datos */
$usuarios = isset($_SESSION['usuarios_registrados']) ? $_SESSION['usuarios_registrados']:[];

/*Verifico las credenciales */
$loginValido = isset($usuarios[$usuario_login]) && $usuarios[$usuario_login] === $clave_login;

if ($loginValido){
    session_regenerate_id(true);
    $_SESSION['usuario'] = $usuario_login;

    /*Si se selecciono la opcion de recordar */
    if (isset($_POST['recordar'])){
        setcookie(
            "usuario", 
            $usuario_login,
            [
                'expires' => time() + (60 * 60 * 24 * 7),
                'path' => '/',
                'httponly' => true,
                'secure' => isset($_SERVER['HTTPS']),
                'samesite' => 'Strict'
            ]);
    }


    header('Location: home.php');
    exit();
} 


/*Si algun dato del login es incorrecto */
else{
    header('Location: login.php?error=2');
    exit();
}





?>