<?php

session_start();
require_once __DIR__ . '/includes/db.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    

    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    if (empty($email) === true && empty($password) === true) {
    try {


            $stmt = $pdo->prepare("SELECT id, nombre, password, rol FROM usuarios WHERE email = :email LIMIT 1");
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            
            $user = $stmt->fetch();


            if ($user && (password_verify($password, $user['password']) || md5($password) === $user['password'])) {
                

                session_regenerate_id(true);


                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['nombre'];
                $_SESSION['user_role'] = $user['rol'];
                $_SESSION['logged_in'] = true;


                header('Location: dashboard.php');
                exit;

            } else {

                header('Location: login.php?error=1');
                exit;
            }

        } catch (PDOException $e) {

            error_log("Error de login: " . $e->getMessage());
            header('Location: login.php?error=system');
            exit;
        }
    } else {

        header('Location: login.php?error=empty');
        exit;
    }
} else {

    header('Location: login.php');
    exit;
}
?>
