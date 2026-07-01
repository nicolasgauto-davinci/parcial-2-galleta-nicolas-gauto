<?php
declare(strict_types=1);
date_default_timezone_set('America/Argentina/Buenos_Aires');
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {    //Si el request no viene de POST, vuelve al login
    header("Location: login.php");
    exit();
}

//Generacion del archivo para registrar los eventos
$archivo = '../EventosCriticos.txt';
$modo = "a";

// Me conecto a la base de datos
require_once "../app/config/conexion.php";

/*Obtengo los datos del formulario con filter input, y lo limpio con trim*/
$usuarioLogin = filter_input(INPUT_POST, 'usuario', FILTER_SANITIZE_SPECIAL_CHARS);   
$claveLogin = filter_input(INPUT_POST, 'clave');

if ($usuarioLogin !== null){
    $usuarioLogin = trim($usuarioLogin);
}

if ($claveLogin !== null){
    $claveLogin = trim($claveLogin);
}

/*Valido que ambos campos esten completos */
if ($usuarioLogin === '' || $claveLogin === '') {
    header("Location: login.php?error=1");
    exit();
}

$fechaActual = date('d-m-Y H:i:s');
//Verifico que el usuario exista en la base de datos
$stmt = $mysqli->prepare("SELECT id FROM usuarios WHERE usuario = ?");
$stmt->bind_param("s", $usuarioLogin);
$stmt->execute();
$resultado = $stmt->get_result();

if($resultado->num_rows === 0){
    $stmt->close();
    header("Location: login.php?error=5");
    //Cada vez que un alguien trate de iniciar sesión con un usuario que no existe, se va a guardar este suceso
    $manejador = fopen($archivo, $modo);

    if($manejador){
        $contenido= "Se intento iniciar sesión con el siguiente dato de Usuario inexistente: '" . $usuarioLogin . "'. Fecha del suceso: " . $fechaActual . "\n";
        fwrite($manejador, $contenido);
        fclose($manejador);
    }
    exit();
}
$stmt->close();

/* Mi idea para chequear que el usuario y contraseña sean el mismo, es preparar un select id donde usuario = ? y clave = ?,
y si el ese user y clave pertenecen a ese id, les deja entrar*/
//Verifico que sean correctas las credenciales para ingresar
$stmt = $mysqli->prepare("SELECT id FROM usuarios WHERE usuario = ? AND clave = ?");
$stmt->bind_param("ss", $usuarioLogin, $claveLogin);
$stmt->execute();
$resultado = $stmt->get_result();

if($resultado->num_rows > 0){
    session_regenerate_id(true);
    $_SESSION['usuario'] = $usuarioLogin;

    //Cada vez que un usuario inicie sesión de forma correcta, se va a guardar este suceso
    $manejador = fopen($archivo, $modo);

    if($manejador){
        $contenido= "El usuario '" . $usuarioLogin . "' inicio sesión. Fecha del suceso: " . $fechaActual . "\n";
        fwrite($manejador, $contenido);
        fclose($manejador);
    }

    /*Si se selecciono la opcion de recordar */
    //No guarda la cookie de recordar si inicia sesion el admin
    if (isset($_POST['recordar']) && ($_SESSION['usuario'] !== 'admin')){
        setcookie(
            "usuario", 
            $usuarioLogin,
            [
                'expires' => time() + (60 * 60 * 24 * 7),
                'path' => '/',
                'httponly' => true,
                'secure' => isset($_SERVER['HTTPS']),
                'samesite' => 'Strict'
            ]);
    }
    $stmt->close();

    if($usuarioLogin === 'admin'){
        header('Location: admin.php');
    }
    else{
        header('Location: home.php');
    }

    exit();
}

else{
    $stmt->close();
    header('Location: login.php?error=2');
    //Cada vez que un alguien trate de iniciar sesión de forma incorrecta, se va a guardar este suceso
    $manejador = fopen($archivo, $modo);

    if($manejador){
        $contenido= "Se intento iniciar sesión en el usuario '" . $usuarioLogin . "' con una clave incorrecta '" . $claveLogin . "'. Fecha del suceso: " . $fechaActual . "\n";
        fwrite($manejador, $contenido);
        fclose($manejador);
    }
    exit();
}

?>