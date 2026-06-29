<?php
declare(strict_types=1);
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

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="./assets/css/global.css">
    <link rel="stylesheet" href="./assets/css/galleta.css">
</head>
<body>
    <header>
        <a href="./home.php">Inicio</a>
        <a href="./logout.php">Cerrar sesión</a>
        <?php
        if($_SESSION['usuario'] === 'admin'){
            echo "<a href='./admin.php'>Admin</a>";
        }
        ?>
    </header>
    
    <main>
        <h1>GALLETA CHINA DE LA FORTUNA</h1>
        <article>
            <p>¿Necesitas de un sabio consejo o de un levantón?¡Obtén la sabiduría de la galleta china de la fortuna, sin las calorias!</p>
            <p>Hace click en la galleta para saber tu fortuna</p>
            <div class="foto"><a href="./galleta.php"><img class="galleta" src="./assets/img/foto galleta.png"></a></div>
        </article>
    </main>

</body>
</html>