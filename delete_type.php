<?php
session_start();
require_once __DIR__ . '/includes/db.php';

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    header('Location: tipos.php?error=ID inválido');
    exit;
}

try {
    $stmt = $pdo->prepare("DELETE FROM tipos_productos WHERE id = :id");
    $stmt->execute([':id' => $id]);
    header('Location: tipos.php?msg=Tipo eliminado correctamente');
} catch (PDOException $e) {
    // Error 23000 is Integrity constraint violation
    if ($e->getCode() == 23000) {
        header('Location: tipos.php?error=No se puede eliminar este tipo porque tiene productos asociados');
    } else {
        header('Location: tipos.php?error=Error al eliminar: ' . $e->getMessage());
    }
}
