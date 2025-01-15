<?php
session_start();
include 'bd.php'; // Conexión a la base de datos

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validar que los campos de username y password estén definidos
    if (isset($_POST['username']) && isset($_POST['password'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        try {
            // Consulta para verificar las credenciales
            $sql = "SELECT * FROM usuarios WHERE username = :username";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':username', $username, PDO::PARAM_STR);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                // Verificar la contraseña
                if ($password === $user['password']) { // Reemplazar por password_verify si usas hash
                    $_SESSION['logged_in'] = true;
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['role'] = $user['role'];

                    // Redirigir según el rol
                    if ($user['role'] === 'admin') {
                        header("Location: index.php");
                    } else {
                        header("Location: dashboard.php");
                    }
                    exit();
                }
            }
            $error = "Credenciales inválidas.";
        } catch (PDOException $e) {
            $error = "Error de conexión: " . $e->getMessage();
        }
    } else {
        $error = "Por favor, complete todos los campos.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SabitecGPS</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
        }
        .left-section {
            flex: 1;
            background: linear-gradient(45deg, #4CAF50, #2196F3);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: white;
        }
        .left-section img {
            max-width: 1000px;
            height: auto;
            margin-bottom: 20px;
        }
        .left-section p {
            font-size: 20px;
            text-align: center;
            margin: 0;
        }
        .right-section {
            flex: 1;
            background: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        .right-section .login-title {
            font-size: 24px;
            margin-bottom: 20px;
            color: #007bff;
        }
        .right-section form {
            width: 100%;
            max-width: 300px;
        }
        .form-control {
            margin-bottom: 15px;
        }
        .btn-primary {
            background-color: #007bff;
            border: none;
            width: 100%;
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }
        .right-panel {
            width: 50%;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 30px;
        }
        .login-container {
            width: 100%;
            max-width: 400px;
            padding: 20px;
            border-radius: 10px;
        }

        .login-container h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #2f80ed;
        }
    </style>
</head>
<body>
    <!-- Sección izquierda -->
    <div class="left-section">
        <img src="images/sabit.png" alt="Sabitec GPS Soporte">
        <p>Tu seguridad en nuestras manos</p>
    </div>
    <!-- Sección derecha -->
    <div class="right-panel">
        <div class="login-container">
            <h2>Iniciar Sesión</h2>
            <?php if (isset($error)): ?>
                <div class="alert alert-danger text-center"><?= $error ?></div>
            <?php endif; ?>
            <form method="POST" action="">
                <div class="form-group mb-3">
                    <label for="username" class="form-label">Usuario</label>
                    <input type="text" name="username" id="username" class="form-control" placeholder="Ingrese su usuario" required>
                </div>
                <div class="form-group mb-4">
                    <label for="password" class="form-label">Contraseña</label>
                    <input type="password" name="password" id="password" class="form-control" placeholder="Ingrese su contraseña" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Ingresar</button>
            </form>
        </div>
    </div>
</body>
</html>
