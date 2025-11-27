<?php

session_start();
require_once 'includes/db.php';


if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit;
}


try {
    $stmt = $pdo->query("
        SELECT t.*, COUNT(p.id) as cantidad 
        FROM tipos_productos t 
        LEFT JOIN productos p ON t.id = p.id_tipo 
        GROUP BY t.id 
        ORDER BY t.id ASC
    ");
    $tipos = $stmt->fetchAll();
} catch (PDOException $e) {
    $error = "Error al cargar los tipos: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tipos de Carne - Carnicería SENA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-danger">
        <div class="container">
            <a class="navbar-brand" href="dashboard.php">Carnicería SENA</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
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
            <h1>Tipos de Carne</h1>
            <a href="dashboard.php" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Volver al Dashboard
            </a>
        </div>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Nombre del Tipo</th>
                                <th>Cantidad de Productos</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($tipos)): ?>
                                <?php foreach ($tipos as $tipo): ?>
                                    <tr>
                                        <td><?php echo $tipo['id']; ?></td>
                                        <td><strong><?php echo htmlspecialchars($tipo['nombre']); ?></strong></td>
                                        <td>
                                            <span class="badge bg-info text-dark">
                                                <?php echo $tipo['cantidad']; ?> cortes
                                            </span>
                                        </td>
                                        <td>
                                            <a href="dashboard.php?tipo_id=<?php echo $tipo['id']; ?>" class="btn btn-sm btn-outline-danger">
                                                Ver Productos <i class="bi bi-arrow-right"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" class="text-center text-muted">No hay tipos registrados.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
