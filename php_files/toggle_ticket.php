<?php
$conn = new mysqli("localhost", "root", "", "attendance_system");
$ticket_code = $_GET['ticket_code'];

$result = $conn->query("SELECT verified FROM tickets WHERE ticket_code = '$ticket_code'");
$row = $result->fetch_assoc();
$newStatus = $row['verified'] ? 0 : 1;

$conn->query("UPDATE tickets SET verified = $newStatus WHERE ticket_code = '$ticket_code'");
echo json_encode(['success' => true, 'verified' => $newStatus]);
?>
