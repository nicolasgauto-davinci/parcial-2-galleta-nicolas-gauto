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
$usuario = filter_input(INPUT_POST, 'nuevo_usuario', FILTER_SANITIZE_SPECIAL_CHARS);
$clave = filter_input(INPUT_POST, 'nueva_clave');
$email = filter_input(INPUT_POST, 'nuevo_email', FILTER_SANITIZE_EMAIL);
$fecha_nac = filter_input(INPUT_POST, 'nueva_fecha_nac');

if ($usuario !== null){
    $usuario = trim($usuario);
}

if ($clave !== null){
    $clave = trim($clave);
}

if ($email !== null){
    $email = trim($email);
}

if ($fecha_nac !== null){
    $fecha_nac = trim($fecha_nac);
}

/*Valido que todos los campos esten completos */
if($usuario === '' || $clave === '' || $email === '' || $fecha_nac === ''){
    header("Location: register.php?error=3");
    exit();
}

//Verifico que no exista ya un usuario con el mismo nombre de usuario
$stmt = $mysqli->prepare("SELECT id FROM usuarios WHERE usuario = ?");
$stmt->bind_param("s", $usuario);
$stmt->execute();
$resultado = $stmt->get_result();

if($resultado->num_rows > 0){
    $stmt->close();
    header("Location: register.php?error=4");
    exit();
}
$stmt->close();

//Verifico que no exista ya un usuario con el mismo mail
$stmt = $mysqli->prepare("SELECT id FROM usuarios WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$resultado = $stmt->get_result();

if($resultado->num_rows > 0){
    $stmt->close();
    header("Location: register.php?error=6");
    exit();
}
$stmt->close();

//Una vez que verifique todo, guardo los nuevos datos
$stmt = $mysqli->prepare("INSERT INTO usuarios (email, usuario, clave, fecha_nacimiento) VALUES (?,?,?,?)");
$stmt->bind_param("ssss", $email, $usuario, $clave, $fecha_nac);
$stmt->execute();
$stmt->close();

header('Location: login.php');
exit();

?>