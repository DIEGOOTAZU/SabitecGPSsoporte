<?php
include 'bd.php';

$email = $_POST['email'];
$ticket_id = $_POST['ticket_id'];

$sql = "SELECT * FROM tickets WHERE email = ? AND id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('si', $email, $ticket_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $ticket = $result->fetch_assoc();
    echo "Estado del Ticket: " . $ticket['estado'];
} else {
    echo "No se encontró el ticket.";
}
?>
