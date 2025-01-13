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
            background-color: #f8f9fa;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .login-container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
            max-width: 400px;
            width: 100%;
        }

        .login-container h2 {
            margin-bottom: 20px;
            font-size: 24px;
            text-align: center;
        }

        .login-container .btn {
            background-color: #007bff;
            border: none;
        }

        .login-container .btn:hover {
            background-color: #0056b3;
        }

        .alert {
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Iniciar Sesión</h2>
        <?php if (isset($error)): ?>
            <div class="alert alert-danger text-center"><?php echo $error; ?></div>
        <?php endif; ?>
        <form method="POST" action="login.php">
            <div class="mb-3">
                <label for="username" class="form-label">Usuario</label>
                <input type="text" id="username" name="username" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Contraseña</label>
                <input type="password" id="password" name="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Iniciar Sesión</button>
        </form>
    </div>
</body>
</html>
