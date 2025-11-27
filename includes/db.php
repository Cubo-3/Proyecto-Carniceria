<?php
// includes/db.php

// Configuración de la base de datos
$host = 'localhost';
$db   = 'carniceria'; // Asegúrate de que este nombre coincida con tu base de datos
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Lanza excepciones en caso de error
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Devuelve arrays asociativos
    PDO::ATTR_EMULATE_PREPARES   => false,                  // Usa sentencias preparadas reales
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
    // echo "Conexión exitosa"; // Descomentar para probar
} catch (\PDOException $e) {
    // En producción, es mejor registrar el error en un log y mostrar un mensaje genérico
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}
?>
