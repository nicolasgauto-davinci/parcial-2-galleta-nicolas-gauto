<?php
declare(strict_types=1);
session_start();

// Si no hay sesión, pero SI hay una cookie de "recordarme", restauramos la sesión
if (!isset($_SESSION['usuario']) && isset($_COOKIE['usuario'])) {
    $_SESSION['usuario'] = $_COOKIE['usuario'];
}

// Si ya hay sesión (ya sea porque estaba viva o porque la restauró la cookie), va al home
if (isset($_SESSION['usuario'])) {
    header('Location: home.php');
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="./assets/css/forms.css">
</head>
<body>

    <h2 class="resaltado">Login</h2>
        <form class="resaltado" action="procesar_login.php" method="POST">
            <label>Usuario:</label>
            <input type="text" name="usuario" required><br>
            <label>Contraseña:</label>
            <input type="password" name="clave" required><br>
            <label>
                <input type="checkbox" name="recordar"> Recordarme
            </label><br>            
            <button type="submit">Ingresar</button>
        </form>
        <p class="resaltado">O</p>
        <form action="register.php" method="POST">
            <button type="submit">Registrarse</button>
        </form>

<?php
if (isset($_GET['error'])) {
    if($_GET['error'] === '1'){
        echo "<p style='color:red;'>Por favor completar todos los campos</p>";
    }
    else if($_GET['error'] === '2'){
        echo "<p style='color:red;'>Usuario o contraseña incorrectos</p>";
    }
    else if($_GET['error'] === '5'){
        echo "<p style='color:red;'>El usuario ingresado no existe</p>";
    }
}
?>

</body>
</html>