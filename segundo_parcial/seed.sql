CREATE DATABASE sitio_galleta IF NOT EXISTS;

USE sitio_galleta;

CREATE TABLE usuarios IF NOT EXISTS(
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(250) NOT NULL,
    usuario VARCHAR(50) NOT NULL,
    clave VARCHAR(250) NOT NULL,
    fecha_nacimiento DATE NOT NULL,
    fecha_alta TIMESTAMP DEFAULT 
)