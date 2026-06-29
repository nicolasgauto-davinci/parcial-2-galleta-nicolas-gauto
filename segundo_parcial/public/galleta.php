<?php
declare(strict_types=1);
date_default_timezone_set('America/Argentina/Buenos_Aires');
session_start();

// Valido si hay una sesion
if (!isset($_SESSION['usuario'])){
    // Si no hay sesion, valido si esta la cookie de recordarme
    if(isset($_COOKIE['usuario'])){
        // Se le devuelve la sesion con la cookie guardada de recordar
        $_SESSION['usuario'] = $_COOKIE['usuario'];
    }
    else{
        // Si no hay sesión ni cookies lo manda al login
        header('Location: login.php');
        exit();
    }
}

require_once "../app/config/conexion.php";

$archivoJson = file_get_contents('frases.json');
$listadoFrases = json_decode($archivoJson, true);

$cantFrases = count($listadoFrases);
$numAlAzar = rand(0, $cantFrases - 1);
$consejo = $listadoFrases[$numAlAzar];

$fechaActual = date('d-m-Y H:i:s');

$stmt = $mysqli->prepare("INSERT INTO historial_galletas (usuario, mensaje) VALUES(?, ?)");
$stmt->bind_param("ss", $_SESSION['usuario'], $consejo);
$stmt->execute();
$stmt->close();



/*
// API Clima
// Uso la url de la api para obtener los datos
$apiUrl = "https://api.openweathermap.org/data/4.0/onecall/current?lat=-34.6037&lon=-58.3816&lang=es&appid=1ed7394a8b564ee8b32f9d19cc920fc9";
// Realizo la solicitud HTTP a la API y obtengo la respuesta
$response = file_get_contents($apiUrl);
// Decodifoco la respuesta JSON en un arreglo
$clima = json_decode($response, true);
*/

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Galleta abierta</title>
    <link rel="stylesheet" href="./assets/css/global.css">
    <link rel="stylesheet" href="./assets/css/galleta.css">
</head>
<body>
    <header>
        <a href="./home.php">Inicio</a>
        <a href="./logout.php">Cerrar sesión</a>
    </header>
    
    <main>
        <h1>GALLETA CHINA DE LA FORTUNA</h1>
        <article>
            <p>¡Su fortuna fue escrita por nuestros sabios antepasados! Y de yapa, te hacemos un full service y te pasamos el clima actual en CABA</p>
            <h3><?php echo htmlspecialchars($consejo);
            ?></h3>
            <p><?php echo htmlspecialchars($fechaActual);
            ?></p>
             <div id="clima">
                <p><em>Consultando el clima en CABA...</em></p>
             </div>
            <p>Haga click en la galleta nuevamente para saber su nueva fortuna</p>
            <div class="foto"><a href="./galleta.php"><img class="galleta" src="./assets/img/galleta abierta.png"></a></div>
        </article>
    </main>

    <script src="./assets/js/script.js"></script>
</body>
</html>