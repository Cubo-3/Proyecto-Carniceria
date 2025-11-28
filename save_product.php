<?php

session_start();
require_once 'includes/db.php';

if (isset($_SESSION['logged_in']) !== true || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nombre_corte = filter_input(INPUT_POST, 'nombre_corte', FILTER_SANITIZE_SPECIAL_CHARS);
    $id_tipo = filter_input(INPUT_POST, 'id_tipo', FILTER_VALIDATE_INT);
    $precio_kilo = filter_input(INPUT_POST, 'precio_kilo', FILTER_VALIDATE_FLOAT);
    $stock_kg = filter_input(INPUT_POST, 'stock_kg', FILTER_VALIDATE_FLOAT);
    $descripcion = filter_input(INPUT_POST, 'descripcion', FILTER_SANITIZE_SPECIAL_CHARS);

    if (empty($nombre_corte) === false && empty($id_tipo) === false && $precio_kilo !== false && $stock_kg !== false) {
        try {

            $imagen = null;
            if (isset($_FILES['imagen']) === true && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {

                $nombreSlug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '_', $nombre_corte)));
                $extension = strtolower(pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION));
                $nombreArchivo = uniqid() . "_" . $nombreSlug . "." . $extension;
                
                $rutaDestino = "uploads/" . $nombreArchivo;
                
                $permitidos = ['jpg', 'jpeg', 'png', 'webp'];
                $mimePermitidos = ['image/jpeg', 'image/png', 'image/webp'];
                
                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                $mimeType = finfo_file($finfo, $_FILES['imagen']['tmp_name']);
                finfo_close($finfo);
                
                $maxSize = 5 * 1024 * 1024;
                
                if (in_array($extension, $permitidos) === true && 
                    in_array($mimeType, $mimePermitidos) === true &&
                    $_FILES['imagen']['size'] <= $maxSize &&
                    is_uploaded_file($_FILES['imagen']['tmp_name']) === true) {
                    
                    if (move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaDestino) === true) {
                        $imagen = $nombreArchivo;
                    }
                }
            }

            $sql = "INSERT INTO productos (nombre_corte, precio_kilo, stock_kg, descripcion, id_tipo, imagen) VALUES (:nombre, :precio, :stock, :desc, :tipo, :imagen)";
            $stmt = $pdo->prepare($sql);
            
            $stmt->execute([
                ':nombre' => $nombre_corte,
                ':precio' => $precio_kilo,
                ':stock' => $stock_kg,
                ':desc' => $descripcion,
                ':tipo' => $id_tipo,
                ':imagen' => $imagen
            ]);

            header('Location: dashboard.php?msg=Producto agregado correctamente');
            exit;

        } catch (PDOException $e) {
            error_log("Error al guardar producto: " . $e->getMessage());
            header('Location: dashboard.php?error=Error al guardar en base de datos');
            exit;
        }
    } else {
        header('Location: dashboard.php?error=Datos inválidos, por favor verifique');
        exit;
    }
} else {
    header('Location: dashboard.php');
    exit;
}
?>
