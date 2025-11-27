-- Script de creación de base de datos para Carnicería SENA
-- Autor: Antigravity
-- Fecha: 2025-11-25

-- 1. Crear la base de datos
CREATE DATABASE IF NOT EXISTS carniceria;
USE carniceria;

-- 2. Tabla de Usuarios
-- Se usa MD5 para el ejemplo, pero en producción se recomienda BCRYPT o ARGON2.
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    rol VARCHAR(50) NOT NULL DEFAULT 'cliente'
) ENGINE=InnoDB;

-- 3. Tabla de Tipos de Productos
CREATE TABLE IF NOT EXISTS tipos_productos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL UNIQUE
) ENGINE=InnoDB;

-- 4. Tabla de Productos
CREATE TABLE IF NOT EXISTS productos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre_corte VARCHAR(100) NOT NULL,
    precio_kilo DECIMAL(10, 2) NOT NULL,
    stock_kg DECIMAL(10, 3) NOT NULL, -- 3 decimales por si se vende en gramos exactos
    descripcion TEXT,
    imagen VARCHAR(255) DEFAULT NULL,
    id_tipo INT NOT NULL,
    CONSTRAINT fk_productos_tipo FOREIGN KEY (id_tipo) 
        REFERENCES tipos_productos(id) 
        ON DELETE RESTRICT 
        ON UPDATE CASCADE
) ENGINE=InnoDB;

-- ==========================================
-- INSERCIÓN DE DATOS DE PRUEBA
-- ==========================================

-- 1. Usuario Administrador
-- Pass: '1234' hasheado con MD5
INSERT INTO usuarios (nombre, email, password, rol) VALUES 
('Administrador', 'admin@carniceria.com', '$2y$10$M/2TS0CaZcD41msTCOphPueiaEfmNqL4buxCanDRr/Y56rHnlSLne', 'admin');

-- 2. Tipos de Carne (4 tipos)
INSERT INTO tipos_productos (nombre) VALUES 
('Res'), 
('Cerdo'), 
('Pollo'), 
('Embutidos');

-- 3. Cortes de Carne (5 ejemplos)
-- Asumiendo IDs: 1=Res, 2=Cerdo, 3=Pollo, 4=Embutidos
INSERT INTO productos (nombre_corte, precio_kilo, stock_kg, descripcion, id_tipo) VALUES 
('Lomo Fino', 45000.00, 15.500, 'Corte tierno y magro, ideal para asar o a la plancha.', 1),
('Costilla de Cerdo', 28000.00, 20.000, 'Costilla carnuda perfecta para BBQ o guisos.', 2),
('Pechuga Entera', 18500.00, 30.000, 'Pechuga de pollo fresca sin piel.', 3),
('Chorizo Santarrosano', 22000.00, 10.000, 'Chorizo artesanal con especias naturales.', 4),
('Punta de Anca', 38000.00, 12.000, 'Corte con capa de grasa externa que da gran sabor.', 1);

-- Fin del script
