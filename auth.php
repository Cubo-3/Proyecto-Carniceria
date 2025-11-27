<?php
// auth.php
session_start();
require_once 'includes/db.php';

// Verificar si se enviaron datos por POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Sanitización básica de entrada
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password']; // La contraseña no se sanea igual, se verifica hash

    if ($email && $password) {
        try {
            // Preparar la consulta para buscar el usuario por email
            // Seguridad: Uso de Prepared Statements para evitar Inyección SQL
            $stmt = $pdo->prepare("SELECT id, nombre, password, rol FROM usuarios WHERE email = :email LIMIT 1");
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            
            $user = $stmt->fetch();

            // Verificar si el usuario existe y la contraseña es correcta
            // Soporte para bcrypt (nuevo) y MD5 (legacy)
            if ($user && (password_verify($password, $user['password']) || md5($password) === $user['password'])) {
                
                // Login exitoso: Regenerar ID de sesión para prevenir Session Fixation
                session_regenerate_id(true);

                // Guardar datos en sesión
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['nombre'];
                $_SESSION['user_role'] = $user['rol'];
                $_SESSION['logged_in'] = true;

                // Redirigir al dashboard
                header('Location: dashboard.php');
                exit;

            } else {
                // Credenciales inválidas
                header('Location: login.php?error=1');
                exit;
            }

        } catch (PDOException $e) {
            // Error de base de datos
            error_log("Error de login: " . $e->getMessage());
            header('Location: login.php?error=system');
            exit;
        }
    } else {
        // Datos incompletos
        header('Location: login.php?error=empty');
        exit;
    }
} else {
    // Acceso directo no permitido
    header('Location: login.php');
    exit;
}
?>
