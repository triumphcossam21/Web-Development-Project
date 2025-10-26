<?php
// Only process if form is submitted
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: regular_attendance.html");
    exit;
}
// Database connection
$host = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "attendance_system";

$conn = new mysqli($host, $db_user, $db_pass, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_POST['user_id'];

// Check if User ID exists
$result = $conn->query("SELECT * FROM member_details WHERE user_id='$user_id'");

if ($result->num_rows > 0) {
    // Save attendance
    $date = date("Y-m-d");
    $time = date("H:i:s");

    $conn->query("INSERT INTO register (user_id, attendance_date, attendance_time)
                  VALUES ('$user_id', '$date', '$time')");

    // Redirect to success page
    header("Location: attendance_success.php?user_id=$user_id");
    exit;

} else { 
    echo "
    <!DOCTYPE html>
    <html lang='en'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Error - Invalid User ID</title>
        <style>
            body {
                font-family: 'Montserrat', sans-serif;
                background-color: #f8f9fa;
                color: #333;
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
                margin: 0;
            }
            .error-box {
                background: white;
                padding: 40px 60px;
                border-radius: 12px;
                box-shadow: 0 0 15px rgba(0,0,0,0.1);
                text-align: center;
            }
            .error-box h1 {
                color: #d9534f;
                font-size: 28px;
                margin-bottom: 10px;
            }
            .error-box p {
                font-size: 16px;
                margin-bottom: 25px;
            }
            .error-box a {
                text-decoration: none;
                background: #007bff;
                color: white;
                padding: 10px 20px;
                border-radius: 6px;
                transition: background 0.3s;
            }
            .error-box a:hover {
                background: #0056b3;
            }
        </style>
    </head>
    <body>
        <div class='error-box'>
            <h1>Invalid User ID</h1>
            <p>Please check your ID and try again.</p>
            <a href='../html_files/mark_attendance.html'>Go Back</a>
        </div>
    </body>
    </html>
    ";
}

$conn->close();
?>
