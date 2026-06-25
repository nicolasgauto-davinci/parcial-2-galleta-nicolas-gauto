<?php
declare(strict_types=1);
session_start();

if (isset($_SESSION['usuario'])){
    unset($_SESSION['usuario']);
}

if (isset($_COOKIE['usuario'])){
    setcookie(
        "usuario",
        "",
        [
            'expires' => time() - 3600,
            'path' => '/',
            'httponly' => true,
            'secure' => isset($_SERVER['HTTPS']),
            'samesite' => 'Strict'
        ]
    );
}

header("Location: index.php");
exit();

?>