<?php

session_start();
require_once __DIR__ . '/includes/db.php';


if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit;
}


try {
    $tipo_id = isset($_GET['tipo_id']) ? (int)$_GET['tipo_id'] : null;
    $filtro_nombre = "";

    $sql = "
        SELECT p.id, p.nombre_corte, p.precio_kilo, p.stock_kg, p.descripcion, p.imagen, t.nombre as tipo 
        FROM productos p 
        INNER JOIN tipos_productos t ON p.id_tipo = t.id
    ";

    if ($tipo_id) {
        $sql .= " WHERE p.id_tipo = :tipo_id";
    }

    $sql .= " ORDER BY p.id DESC";

    $stmt = $pdo->prepare($sql);
    
    if ($tipo_id) {
        $stmt->bindParam(':tipo_id', $tipo_id);
        

        $stmtTipo = $pdo->prepare("SELECT nombre FROM tipos_productos WHERE id = :id");
        $stmtTipo->execute([':id' => $tipo_id]);
        $tipoData = $stmtTipo->fetch();
        if ($tipoData) {
            $filtro_nombre = $tipoData['nombre'];
        }
    }

    $stmt->execute();
    $productos = $stmt->fetchAll();


    $stmtTypes = $pdo->query("SELECT * FROM tipos_productos");
    $tipos = $stmtTypes->fetchAll();

} catch (PDOException $e) {
    $error = "Error al cargar datos: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Carnicería-La Fama</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Iconos de Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f4f6f9;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .navbar {
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 0 20px rgba(0,0,0,0.05);
        }
        .table-responsive {
            border-radius: 12px;
            overflow: hidden;
        }
        .table thead th {
            background-color: #2c3e50;
            color: white;
            border: none;
            padding: 15px;
            font-weight: 600;
        }
        .table tbody td {
            vertical-align: middle;
            padding: 15px;
            border-bottom: 1px solid #eee;
        }
        .product-img-thumbnail {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            transition: transform 0.2s;
            cursor: pointer;
        }
        .product-img-thumbnail:hover {
            transform: scale(1.1);
        }
        .btn-action {
            border-radius: 50%;
            width: 35px;
            height: 35px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin: 0 2px;
        }
        .page-header {
            background: white;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.03);
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-danger">
        <div class="container">
            <a class="navbar-brand" href="#">Carnicería-La Fama</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link text-white" href="tipos.php">Tipos de Carne</a>
                    </li>
                    <li class="nav-item">
                        <span class="nav-link text-white">Hola, <?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn btn-outline-light btn-sm ms-2" href="logout.php">Cerrar Sesión</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1>Gestión de Productos</h1>
                <?php if ($tipo_id && $filtro_nombre): ?>
                    <h4 class="text-muted">
                        Filtrado por: <span class="badge bg-danger"><?php echo htmlspecialchars($filtro_nombre); ?></span>
                        <a href="dashboard.php" class="btn btn-sm btn-outline-secondary ms-2">Ver Todos</a>
                    </h4>
                <?php endif; ?>
            </div>
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addProductModal">
                <i class="bi bi-plus-circle"></i> Agregar Nuevo Corte
            </button>
        </div>

        <?php if (isset($_GET['msg'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo htmlspecialchars($_GET['msg']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php echo htmlspecialchars($_GET['error']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Imagen</th>
                                <th>Corte</th>
                                <th>Tipo</th>
                                <th>Precio / Kg</th>
                                <th>Stock (Kg)</th>
                                <th>Descripción</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($productos)): ?>
                                <?php foreach ($productos as $prod): ?>
                                    <tr>
                                        <td><span class="text-muted">#<?php echo $prod['id']; ?></span></td>
                                        <td>
                                            <?php if ($prod['imagen']): ?>
                                                <img src="uploads/<?php echo htmlspecialchars($prod['imagen']); ?>" 
                                                     alt="Img" 
                                                     class="product-img-thumbnail"
                                                     onclick="showImagePreview('uploads/<?php echo htmlspecialchars($prod['imagen']); ?>', '<?php echo htmlspecialchars($prod['nombre_corte']); ?>')">
                                            <?php else: ?>
                                                <div class="bg-light d-flex align-items-center justify-content-center rounded" style="width: 80px; height: 80px;">
                                                    <i class="bi bi-image text-muted h4 m-0"></i>
                                                </div>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <h6 class="mb-0 fw-bold text-dark"><?php echo htmlspecialchars($prod['nombre_corte']); ?></h6>
                                        </td>
                                        <td><span class="badge bg-light text-dark border"><?php echo htmlspecialchars($prod['tipo']); ?></span></td>
                                        <td><span class="fw-bold text-primary">$<?php echo number_format($prod['precio_kilo'], 0, ',', '.'); ?></span></td>
                                        <td>
                                            <?php 
                                                $stockClass = $prod['stock_kg'] < 5 ? 'text-danger fw-bold' : 'text-success fw-bold';
                                                echo "<span class='$stockClass'>" . number_format($prod['stock_kg'], 3, ',', '.') . " kg</span>";
                                            ?>
                                        </td>
                                        <td><small class="text-muted d-block text-truncate" style="max-width: 150px;"><?php echo htmlspecialchars($prod['descripcion']); ?></small></td>
                                        <td>
                                            <a href="edit_product.php?id=<?php echo $prod['id']; ?>" class="btn btn-primary btn-action shadow-sm" title="Editar">
                                                <i class="bi bi-pencil-fill small"></i>
                                            </a>
                                            <a href="delete_product.php?id=<?php echo $prod['id']; ?>" class="btn btn-danger btn-action shadow-sm" title="Eliminar" onclick="return confirm('¿Estás seguro de eliminar este producto?');">
                                                <i class="bi bi-trash-fill small"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="8" class="text-center text-muted">No hay productos registrados.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Agregar Producto -->
    <div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="addProductModalLabel">Nuevo Corte de Carne</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="save_product.php" method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="nombre_corte" class="form-label">Nombre del Corte</label>
                            <input type="text" class="form-control" id="nombre_corte" name="nombre_corte" required>
                        </div>
                        <div class="mb-3">
                            <label for="id_tipo" class="form-label">Tipo de Carne</label>
                            <select class="form-select" id="id_tipo" name="id_tipo" required>
                                <option value="">Seleccione...</option>
                                <?php foreach ($tipos as $tipo): ?>
                                    <option value="<?php echo $tipo['id']; ?>"><?php echo htmlspecialchars($tipo['nombre']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="imagen" class="form-label">Imagen del Producto</label>
                            <input type="file" class="form-control" id="imagen" name="imagen" accept="image/*">
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="precio_kilo" class="form-label">Precio por Kilo</label>
                                <input type="number" step="0.01" class="form-control" id="precio_kilo" name="precio_kilo" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="stock_kg" class="form-label">Stock Inicial (Kg)</label>
                                <input type="number" step="0.001" class="form-control" id="stock_kg" name="stock_kg" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="descripcion" class="form-label">Descripción</label>
                            <textarea class="form-control" id="descripcion" name="descripcion" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-danger">Guardar Producto</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Previsualización de Imagen -->
    <div class="modal fade" id="imagePreviewModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content bg-transparent border-0">
                <div class="modal-body text-center position-relative p-0">
                    <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-3" data-bs-dismiss="modal" aria-label="Close"></button>
                    <img src="" id="previewImage" class="img-fluid rounded shadow-lg" style="max-height: 85vh;">
                    <h5 class="text-white mt-3" id="previewTitle"></h5>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function showImagePreview(src, title) {
            document.getElementById('previewImage').src = src;
            document.getElementById('previewTitle').textContent = title;
            var myModal = new bootstrap.Modal(document.getElementById('imagePreviewModal'));
            myModal.show();
        }
    </script>
</body>
</html>
