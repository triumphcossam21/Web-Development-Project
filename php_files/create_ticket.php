<?php
// Connect to database
$conn = new mysqli("localhost", "root", "", "attendance_system");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to generate 5-character code
function generateTicketCode($length = 5) {
    return strtoupper(substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, $length));
}

// Check if POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = trim($_POST['user_id']);

    // Check if user exists
    $userCheck = $conn->prepare("SELECT user_id, name FROM member_details WHERE user_id = ?");
    $userCheck->bind_param("s", $user_id);
    $userCheck->execute();
    $userCheck->store_result();
    $userCheck->bind_result($db_user_id, $name);
    $userCheck->fetch();

    if ($userCheck->num_rows === 0) {
        echo "<h2 style='color:red; text-align:center;'>Invalid User ID. Please check and try again.</h2>";
        exit;
    }

    // Check if ticket already exists today
    $todayStart = date("Y-m-d") . " 00:00:00";
    $todayEnd = date("Y-m-d") . " 23:59:59";

    $ticketCheck = $conn->prepare("SELECT ticket_id FROM tickets WHERE user_id = ? AND issue_date BETWEEN ? AND ?");
    $ticketCheck->bind_param("sss", $user_id, $todayStart, $todayEnd);
    $ticketCheck->execute();
    $ticketCheck->store_result();

    if ($ticketCheck->num_rows > 0) {
        echo "<h2 style='color:orange; text-align:center;'>You already have a ticket for today.</h2>";
        exit;
    }

    // Generate ticket code
    $ticket_code = generateTicketCode();

    // Insert ticket
    $insertTicket = $conn->prepare("INSERT INTO tickets (user_id, ticket_code) VALUES (?, ?)");
    $insertTicket->bind_param("ss", $user_id, $ticket_code);

    if ($insertTicket->execute()) {
        echo "
        <div style='font-family: Arial, sans-serif; display: flex; justify-content: center; align-items: center; height: 100vh; background: #f0f0f0;'>
            <div style='background: #fff; padding: 40px; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.2); text-align: center; width: 350px;'>
                <h1 style='color:#003366;'>Ticket Created!</h1>
                <p><strong>Name:</strong> " . htmlspecialchars($name) . "</p>
                <p><strong>User ID:</strong> $user_id</p>
                <p><strong>Ticket Code:</strong> <span style='font-size: 1.5em; color:#ff0000;'>$ticket_code</span></p>
                <p><strong>Date:</strong> " . date("M d, Y") . "</p>
                <a href='../html_files/index.html'><button style='margin-top: 15px; padding: 10px 20px; background:#003366; color:#fff; border:none; border-radius:5px; cursor:pointer;'>Back to Home</button></a>
            </div>
        </div>
        ";
    } else {
        echo "<h2 style='color:red; text-align:center;'>Error creating ticket. Please try again.</h2>";
    }

    $insertTicket->close();
    $ticketCheck->close();
    $userCheck->close();
}

$conn->close();
?>
