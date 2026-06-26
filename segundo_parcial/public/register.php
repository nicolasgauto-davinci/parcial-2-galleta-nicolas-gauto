<?php
declare(strict_types=1);
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <link rel="stylesheet" href="./assets/css/forms.css">
</head>
<body>
    <h2 class="resaltado">Registrarse</h2>
    <form class="resaltado" action="procesar_registro.php" method="POST">
        <label>Mail:</label>
        <input type="email" name="nuevo_email" required><br>
        <label>Usuario:</label>
        <input type="text" name="nuevo_usuario" required><br>
        <label>Contraseña:</label>
        <input type="password" name="nueva_clave" required><br>
        <label>Fecha de nacimiento:</label>
        <input type="date" name="nueva_fecha_nac" required><br>
        <button type="submit">Guardar</button>
    </form>
    <p><a href="./login.php">Volver</a></p>

<?php

if (isset($_GET['error'])) {
        if ($_GET['error'] === '3') {
            echo "<p style='color:red;'>No se pueden enviar campos vacios</p>";
        }
        else if ($_GET['error'] === '4') {
            echo "<p style='color:red;'>El nombre de usuario ya esta en uso, elegi otro</p>";
        }
        else if ($_GET['error'] === '6'){
            echo "<p style='color:red;'>El email ya esta en uso, elegi otro</p>";
        }
    }

?>

</body>
</html>