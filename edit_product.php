<?php

session_start();
require_once 'includes/db.php';

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$producto = null;
$tipos = [];

if ($id) {
    try {

        $stmt = $pdo->prepare("SELECT * FROM productos WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $producto = $stmt->fetch();


        $stmtTypes = $pdo->query("SELECT * FROM tipos_productos");
        $tipos = $stmtTypes->fetchAll();

    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
}

if (!$producto) {
    header('Location: dashboard.php?error=Producto no encontrado');
    exit;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre_corte = trim($_POST['nombre_corte']);
    $id_tipo = filter_input(INPUT_POST, 'id_tipo', FILTER_VALIDATE_INT);
    $precio_kilo = filter_input(INPUT_POST, 'precio_kilo', FILTER_VALIDATE_FLOAT);
    $stock_kg = filter_input(INPUT_POST, 'stock_kg', FILTER_VALIDATE_FLOAT);
    $descripcion = trim($_POST['descripcion']);
    $id_prod = $_POST['id'];

    if ($nombre_corte && $id_tipo && $precio_kilo !== false && $stock_kg !== false) {
        try {

            $imagen = null;
            $sql_imagen = "";
            $params = [
                ':nombre' => $nombre_corte,
                ':precio' => $precio_kilo,
                ':stock' => $stock_kg,
                ':desc' => $descripcion,
                ':tipo' => $id_tipo,
                ':id' => $id_prod
            ];

            if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {

                $nombreSlug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '_', $nombre_corte)));
                $extension = strtolower(pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION));
                $nombreArchivo = uniqid() . "_" . $nombreSlug . "." . $extension;
                
                $rutaDestino = "uploads/" . $nombreArchivo;
                
                $permitidos = ['jpg', 'jpeg', 'png', 'webp'];
                
                if (in_array($extension, $permitidos)) {
                    if (move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaDestino)) {
                        $imagen = $nombreArchivo;
                        $sql_imagen = ", imagen = :imagen";
                        $params[':imagen'] = $imagen;


                    }
                }
            }

            $sql = "UPDATE productos SET nombre_corte = :nombre, precio_kilo = :precio, stock_kg = :stock, descripcion = :desc, id_tipo = :tipo $sql_imagen WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);

            header('Location: dashboard.php?msg=Producto actualizado correctamente');
            exit;

        } catch (PDOException $e) {
            $error = "Error al actualizar: " . $e->getMessage();
        }
    } else {
        $error = "Datos inválidos";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Producto - Carnicería SENA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Editar Producto</h4>
                        <a href="dashboard.php" class="btn btn-sm btn-light">Volver</a>
                    </div>
                    <div class="card-body">
                        <?php if (isset($error)): ?>
                            <div class="alert alert-danger"><?php echo $error; ?></div>
                        <?php endif; ?>

                        <form action="edit_product.php?id=<?php echo $id; ?>" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="id" value="<?php echo $producto['id']; ?>">
                            
                            <div class="mb-3">
                                <label for="nombre_corte" class="form-label">Nombre del Corte</label>
                                <input type="text" class="form-control" id="nombre_corte" name="nombre_corte" value="<?php echo htmlspecialchars($producto['nombre_corte']); ?>" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="id_tipo" class="form-label">Tipo de Carne</label>
                                <select class="form-select" id="id_tipo" name="id_tipo" required>
                                    <?php foreach ($tipos as $tipo): ?>
                                        <option value="<?php echo $tipo['id']; ?>" <?php echo $tipo['id'] == $producto['id_tipo'] ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($tipo['nombre']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="imagen" class="form-label">Imagen del Producto</label>
                                <?php if ($producto['imagen']): ?>
                                    <div class="mb-2">
                                        <img src="uploads/<?php echo htmlspecialchars($producto['imagen']); ?>" alt="Imagen actual" style="max-width: 150px; border-radius: 5px;">
                                        <p class="text-muted small">Imagen actual</p>
                                    </div>
                                <?php endif; ?>
                                <input type="file" class="form-control" id="imagen" name="imagen" accept="image/*">
                                <div class="form-text">Dejar en blanco para mantener la imagen actual.</div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="precio_kilo" class="form-label">Precio por Kilo</label>
                                    <input type="number" step="0.01" class="form-control" id="precio_kilo" name="precio_kilo" value="<?php echo $producto['precio_kilo']; ?>" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="stock_kg" class="form-label">Stock (Kg)</label>
                                    <input type="number" step="0.001" class="form-control" id="stock_kg" name="stock_kg" value="<?php echo $producto['stock_kg']; ?>" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="descripcion" class="form-label">Descripción</label>
                                <textarea class="form-control" id="descripcion" name="descripcion" rows="3"><?php echo htmlspecialchars($producto['descripcion']); ?></textarea>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">Actualizar Producto</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
