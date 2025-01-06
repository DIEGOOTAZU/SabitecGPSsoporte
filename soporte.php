<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Soporte Técnico - SabitecGPS</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .header-container {
            background-color: #343a40;
            color: white;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .header-container h1 {
            font-size: 24px;
            margin: 0;
        }
        .header-container nav a {
            color: white;
            margin: 0 10px;
            text-decoration: none;
            font-size: 16px;
        }
        .header-container nav input[type="text"] {
            padding: 5px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        .header-container nav button {
            padding: 5px 10px;
            background-color: #007bff;
            border: none;
            border-radius: 5px;
            color: white;
        }
        .support-container {
            margin-top: 30px;
        }
        .support-container .welcome-section {
            text-align: center;
            margin-bottom: 30px;
        }
        .ticket-section {
            display: flex;
            gap: 30px;
            justify-content: space-around;
            flex-wrap: wrap;
        }
        .ticket-section .new-ticket,
        .ticket-section .check-ticket {
            background-color: white;
            border-radius: 15px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
            padding: 40px;
            text-align: center;
            width: 100%;
            max-width: 500px;
        }
        .ticket-section h3 {
            font-size: 24px;
            margin-bottom: 20px;
        }
        .ticket-section img {
            margin-bottom: 20px;
            width: 120px;
            height: auto;
        }
        .ticket-section button {
            margin-top: 20px;
            background-color: #007bff;
            border: none;
            color: white;
            padding: 15px 30px;
            font-size: 16px;
            border-radius: 5px;
        }
        .ticket-section form input {
            margin-bottom: 20px;
            width: 100%;
            padding: 15px;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 16px;
        }
        .ticket-section form button {
            width: 100%;
        }


    </style>
</head>
<body>
    <!-- Header -->
    <header>
        <div class="header-container">
            <h1>SabitecGPS</h1>
            <nav>
                <a href="index.php">Inicio</a>
               
               
                <input type="text" placeholder="Buscar">
                <button>Buscar</button>
                
            </nav>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container support-container">
        <div class="welcome-section">
            <img src="images/sabiteclogo.png" alt="Logo SabitecGPS" style="width: 120px;">
            <p>Bienvenido al centro de soporte de SabitecGPS</p>
            <p>Sistema para llevar un control sobre lo realizado durante el día.</p>
        </div>
        <div class="ticket-section">
            <!-- Nuevo Ticket -->
            <div class="new-ticket">
                <h3>Nuevo Ticket</h3>
                <img src="images/ticket.png" alt="Nuevo Ticket">
                <p>Si tienes un problema con cualquiera de nuestros productos, repórtalo creando un nuevo ticket y te ayudaremos a solucionarlo. Si desea actualizar una petición ya realizada, utiliza el formulario de la derecha.</p>
                <button onclick="window.location.href='nuevo_ticket.php'">Nuevo Ticket</button>
            </div>

            <!-- Comprobar Estado -->
            <div class="check-ticket">
                <h3>Comprobar estado de Ticket</h3>
                <img src="images/estado.png" alt="Comprobar Estado">
                <form action="estado_ticket.php" method="POST">
    <label for="ticket_id">Serie Ticket</label>
    <input type="text" id="ticket_id" name="ticket_id" placeholder="Ingrese la serie del Ticket" required>
    <button type="submit" class="btn btn-primary">Consultar</button>
</form>


            </div>
        </div>
    </main>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
