<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo Ticket - SabitecGPS</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .header-container {
            background-color: #343a40;
            padding: 10px 20px;
            color: white;
        }
        .header-container h1 {
            font-size: 24px;
            display: inline-block;
        }
        .header-container nav a {
            color: white;
            margin: 0 10px;
            text-decoration: none;
        }
        .form-container {
            background-color: #ffffff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            margin: 30px auto;
            max-width: 800px;
        }
        .form-container h2 {
            font-size: 24px;
            margin-bottom: 20px;
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }
        .toast-success {
        position: fixed;
        top: 40%; /* Ajusta este valor para mover el mensaje más arriba */
        left: 50%;
        transform: translate(-50%, -50%);
        background-color: #28a745;
        color: white;
        padding: 20px 40px;
        font-size: 24px;
        font-weight: bold;
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        display: none;
        z-index: 1050;
        text-align: center;
    }
    </style>
</head>
<body>
    <!-- Header -->
    <header>
        <div class="header-container d-flex justify-content-between align-items-center">
            <h1>SabitecGPS</h1>
            <nav>
                <a href="index.php">Inicio</a>
                <a href="soporte.php">Soporte Técnico</a>
            </nav>
        </div>
    </header>

    <!-- Toast Message -->
    <div id="toast-success" class="toast-success">Ticket abierto exitosamente</div>

    <!-- Main Content -->
    <main class="container">
        <div class="form-container">
            <h2>¿Cómo abrir un nuevo Ticket?</h2>
            <p>
                Para abrir un nuevo ticket, deberá llenar todos los campos del siguiente formulario. Podrá verificar el estado de su ticket mediante el <strong>Ticket ID</strong> que se le proporcionará al enviar este formulario.
            </p>

            <!-- Formulario -->
            <form action="guardar_ticket.php" method="POST">
                <div class="mb-3">
                    <label for="fecha" class="form-label">Fecha:</label>
                    <input type="date" id="fecha" name="fecha" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre:</label>
                    <input type="text" id="nombre" name="nombre" class="form-control" placeholder="Escriba su nombre" required>
                </div>

                <div class="mb-3">
                    <label for="tecnico" class="form-label">Técnico:</label>
                    <select id="tecnico" name="tecnico" class="form-select" required>
                        <option value="Ruben">Ruben</option>
                        <option value="Diego">Diego</option>
                        <option value="Carlos">Carlos</option>
                        <option value="Fran">Fran</option>
                        <option value="Miguel">Miguel</option>
                        <option value="Marcos">Marcos</option>
                        <option value="Moises">Moises</option>
                        <option value="Gian">Gian</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="prioridad" class="form-label">Prioridad:</label>
                    <select id="prioridad" name="prioridad" class="form-select" required>
                        <option value="Baja">Baja</option>
                        <option value="Media">Media</option>
                        <option value="Alta">Alta</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="asunto" class="form-label">Asunto:</label>
                    <input type="text" id="asunto" name="asunto" class="form-control" placeholder="Escriba el asunto" required>
                </div>

                <div class="mb-3">
                    <label for="problema" class="form-label">Problema de su producto:</label>
                    <textarea id="problema" name="problema" class="form-control" placeholder="Describa el problema que presenta su producto" required></textarea>
                </div>

                <div class="mb-3">
                    <label for="tiempo_solucion" class="form-label">Tiempo de Solución (HH:MM:SS):</label>
                    <input type="time" step="1" id="tiempo_solucion" name="tiempo_solucion" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="hora_creacion" class="form-label">Hora de Creación:</label>
                    <input type="text" id="hora_creacion" name="hora_creacion" class="form-control" readonly>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">Abrir Ticket</button>
                </div>
            </form>
        </div>
    </main>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const horaCreacionInput = document.getElementById("hora_creacion");

            // Obtener la hora actual
            const now = new Date();
            const horaActual = now.toLocaleTimeString('en-GB', { hour12: false });

            // Asignar la hora actual al campo de hora de creación
            horaCreacionInput.value = horaActual;

            // Mostrar el mensaje flotante si hay un parámetro de éxito en la URL
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('success')) {
                const toast = document.getElementById('toast-success');
                toast.style.display = 'block';

                // Ocultar el mensaje después de 3 segundos
                setTimeout(() => {
                    toast.style.display = 'none';
                }, 3000);
            }
        });
    </script>
</body>
</html>
