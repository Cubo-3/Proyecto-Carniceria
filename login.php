<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Carnicería SENA</title>
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            width: 100%;
            max-width: 400px;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            background: white;
        }
        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        .login-header h2 {
            color: #dc3545; /* Color rojo carnicería */
            font-weight: bold;
        }
    </style>
</head>
<body>

    <div class="login-card">
        <div class="login-header">
            <h2>Carnicería SENA</h2>
            <p class="text-muted">Ingrese sus credenciales</p>
        </div>

        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger text-center" role="alert">
                Usuario o contraseña incorrectos.
            </div>
        <?php endif; ?>

        <form action="auth.php" method="POST">
            <div class="mb-3">
                <label for="email" class="form-label">Correo Electrónico</label>
                <input type="email" class="form-control" id="email" name="email" required placeholder="ejemplo@correo.com">
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Contraseña</label>
                <input type="password" class="form-control" id="password" name="password" required placeholder="********">
            </div>
            <div class="d-grid">
                <button type="submit" class="btn btn-danger btn-lg">Ingresar</button>
            </div>
        </form>
        <div class="text-center mt-3">
            <a href="#" class="text-decoration-none text-muted small">¿Olvidó su contraseña?</a>
        </div>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
