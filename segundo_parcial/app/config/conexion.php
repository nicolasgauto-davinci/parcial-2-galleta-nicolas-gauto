<?php
//Pido que me genere un reporte cuando haya algun error ya sea estricto o de conexion
//Este reporte se puede ver en la carpeta de logs de xammp y/o en la parte de errors y/o en la parte de reportes
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

require_once "config.php";

//Siempre que se trabaje con una base de datos, se va a usar un try catch
//Esto es para que si falla, quiero poder manejar ese error de alguna forma
try{
    //Lo que intento hacer
    $mysqli = new mysqli($host, $user, $password, $dbname);
    $mysqli->set_charset("utf8mb4");
}catch(Exception $ex){
    //Si hay algun problema se ejecuta esto
    //La funcion die termina todo el proceso
    die("Error al conectarse: " . $ex->getMessage());
}

?>