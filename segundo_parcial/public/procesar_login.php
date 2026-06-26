<?php
declare(strict_types=1);
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {    //Si el request no viene de POST, vuelve al login
    header("Location: login.php");
    exit();
}

// Me conecto a la base de datos
require_once "../app/config/conexion.php";

/*Obtengo los datos del formulario con filter input, y lo limpio con trim*/
$usuario_login = filter_input(INPUT_POST, 'usuario', FILTER_SANITIZE_SPECIAL_CHARS);   
$clave_login = filter_input(INPUT_POST, 'clave');

if ($usuario_login !== null){
    $usuario_login = trim($usuario_login);
}

if ($clave_login !== null){
    $clave_login = trim($clave_login);
}

/*Valido que ambos campos esten completos */
if ($usuario_login === '' || $clave_login === '') {
    header("Location: login.php?error=1");
    exit();
}

//Verifico que el usuario exista en la base de datos
$stmt = $mysqli->prepare("SELECT id FROM usuarios WHERE usuario = ?");
$stmt->bind_param("s", $usuario_login);
$stmt->execute();
$resultado = $stmt->get_result();

if($resultado->num_rows === 0){
    $stmt->close();
    header("Location: login.php?error=5");
    exit();
}
$stmt->close();

/* Mi idea para chequear que el usuario y contraseña sean el mismo, es preparar un select id donde usuario = ? y clave = ?,
 y si el ese user y clave pertenecen a ese id, les deja entrar*/

//Verifico que sean correctas las credenciales para ingresar
$stmt = $mysqli->prepare("SELECT id FROM usuarios WHERE usuario = ? AND clave = ?");
$stmt->bind_param("ss", $usuario_login, $clave_login);
$stmt->execute();
$resultado = $stmt->get_result();

if($resultado->num_rows > 0){
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
    $stmt->close();
    header('Location: home.php');
    exit();
}

else{
    $stmt->close();
    header('Location: login.php?error=2');
    exit();
}

?>