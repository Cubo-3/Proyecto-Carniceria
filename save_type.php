<?php
session_start();
require_once __DIR__ . '/includes/db.php';

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');

    if (empty($nombre)) {
        header('Location: tipos.php?error=El nombre del tipo es obligatorio');
        exit;
    }

    try {
        $stmt = $pdo->prepare("INSERT INTO tipos_productos (nombre) VALUES (:nombre)");
        $stmt->execute([':nombre' => $nombre]);
        header('Location: tipos.php?msg=Tipo agregado correctamente');
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) { // Duplicate entry
            header('Location: tipos.php?error=El tipo de carne ya existe');
        } else {
            header('Location: tipos.php?error=Error al guardar: ' . $e->getMessage());
        }
    }
} else {
    header('Location: tipos.php');
}
