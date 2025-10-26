<?php
// Only process if form is submitted
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: regular_attendance.html");
    exit;
}

// Connect to database
$conn = new mysqli("localhost", "root", "", "attendance_system");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_POST['user_id'];

// Check if User ID exists
$result = $conn->query("SELECT * FROM first_time_attendance WHERE user_id='$user_id'");

if ($result->num_rows > 0) {
    // Save attendance
    $date = date("Y-m-d");
    $time = date("H:i:s");

    $conn->query("INSERT INTO regular_attendance (user_id, attendance_date, attendance_time)
                  VALUES ('$user_id', '$date', '$time')");

    // Redirect to success page
    header("Location: regular_success.php?user_id=$user_id");
    exit;

} else {
    echo "Invalid User ID. Please check and try again.";
}

$conn->close();
?>
