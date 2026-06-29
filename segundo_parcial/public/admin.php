<?php
declare(strict_types=1);
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
    <link rel="stylesheet" href="./assets/css/global.css">
</head>
<body>
    <header>
        <a href="./home.php">Inicio</a>
        <a href="./logout.php">Cerrar sesión</a>
        <a href="./admin.php">Admin</a>
    </header>
</body>
</html>

<?php

$archivo = '../EventosCriticos.txt';
$modo = "r";

if(file_exists($archivo)){
    $manejador = fopen($archivo, $modo);
    if($manejador){
        while(!feof($manejador)){
            $leer = fgets($manejador);
            if($leer){
                echo "<p>" . htmlspecialchars($leer) . "</p>";
            }
        }
        fclose($manejador);
    }
}
else{
    echo "<p>Todavia no hay actividad registrada en el sitio</p>";
}

?>