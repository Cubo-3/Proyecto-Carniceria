<?php

session_start();
require_once __DIR__ . '/includes/db.php';

if (isset($_SESSION['logged_in']) !== true || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

if (isset($_GET['id']) === true) {
    $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

    if (empty($id) === false) {
        try {
            $stmt = $pdo->prepare("DELETE FROM productos WHERE id = :id");
            $stmt->execute([':id' => $id]);

            if ($stmt->rowCount() > 0) {
                header('Location: dashboard.php?msg=Producto eliminado correctamente');
            } else {
                header('Location: dashboard.php?error=Producto no encontrado');
            }
            exit;

        } catch (PDOException $e) {

            if ($e->getCode() === '23000') {
                header('Location: dashboard.php?error=No se puede eliminar el producto porque tiene registros asociados');
            } else {
                error_log("Error al eliminar: " . $e->getMessage());
                header('Location: dashboard.php?error=Error del sistema');
            }
            exit;
        }
    }
}

header('Location: dashboard.php');
exit;
?>
