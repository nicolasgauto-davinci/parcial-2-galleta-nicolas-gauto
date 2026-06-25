<?php
declare(strict_types=1);
session_start();


if ($_SERVER['REQUEST_METHOD'] !== 'POST') {    //Si el request no viene de POST, vuelve al login
    header("Location: login.php");
    exit();
}

/*Obtengo los datos del formulario con filter input, y lo limpio con trim*/
$usuario = filter_input(INPUT_POST, 'nuevo_usuario', FILTER_SANITIZE_SPECIAL_CHARS);
$clave = filter_input(INPUT_POST, 'nueva_clave');
$email = filter_input(INPUT_POST, 'nuevo_email');

if ($usuario !== null){
    $usuario = trim($usuario);
}

/*Valido que ambos campos esten completos */
if($usuario === '' || $clave === ''){
    header("Location: register.php?error=3");
    exit();
}

/*Me aseguro de crear el array 'usuarios_registrados' para la primera vez que corre el sitio. Si ya esta creado, no crea un nuevo array */
if(!isset($_SESSION['usuarios_registrados'])){
    $_SESSION['usuarios_registrados'] = [];
}

/*Me aseguro de que el nombre de usuario no este en uso */
if(isset($_SESSION['usuarios_registrados'][$usuario])){
    header("Location: register.php?error=4");
    exit();
}

if(isset($_SESSION['usuarios_registrados'][$email])){
    header("Location: register.php?error=6");
    exit();
}

/*Guarda en la sesion en el array llamado usuarios_registrados el usuario y la clave luego de pasar las validaciones*/
$_SESSION['usuarios_registrados'][$usuario] = $clave;


header('Location: login.php');
exit();

?>