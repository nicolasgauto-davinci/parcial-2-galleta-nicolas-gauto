<?php
declare(strict_types=1);
date_default_timezone_set('America/Argentina/Buenos_Aires');
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {    //Si el request no viene de POST, vuelve al login
    header("Location: login.php");
    exit();
}

//Generacion del archivo para registrar los eventos


// Me conecto a la base de datos
require_once "../app/config/conexion.php";

/*Obtengo los datos del formulario con filter input, y lo limpio con trim*/
$usuario = filter_input(INPUT_POST, 'nuevoUsuario', FILTER_SANITIZE_SPECIAL_CHARS);
$clave = filter_input(INPUT_POST, 'nuevaClave');
$email = filter_input(INPUT_POST, 'nuevoEmail', FILTER_SANITIZE_EMAIL);
$fechaNac = filter_input(INPUT_POST, 'nuevaFechaNac');

if ($usuario !== null){
    $usuario = trim($usuario);
}

if ($clave !== null){
    $clave = trim($clave);
}

if ($email !== null){
    $email = trim($email);
}

if ($fechaNac !== null){
    $fechaNac = trim($fechaNac);
}

/*Valido que todos los campos esten completos */
if($usuario === '' || $clave === '' || $email === '' || $fechaNac === ''){
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
$stmt->bind_param("ssss", $email, $usuario, $clave, $fechaNac);
$stmt->execute();
$stmt->close();

$fechaActual = date('d-m-Y H:i:s');
//Cada vez que se genere un nuevo usuario de forma correcta, se guarda este suceso
$manejador = fopen($archivo, $modo);

if($manejador){
    $contenido= "Se genero correctamente el nuevo usuario '" . $usuario . "'. Fecha del suceso: " . $fechaActual . "\n";
    fwrite($manejador, $contenido);
    fclose($manejador);
}

header('Location: login.php');
exit();

?>