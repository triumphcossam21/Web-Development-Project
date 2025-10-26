<?php
session_start();

// Database connection
$host = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "attendance";

$conn = new mysqli($host, $db_user, $db_pass, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Prepare statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT id, password FROM admins WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $hashed_password);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            // Successful login
            $_SESSION['admin_id'] = $id;
            $_SESSION['username'] = $username;
            header("Location: admin-dashboard.php"); // redirect to dashboard
            exit();
        } else {
            echo "<script>alert('Incorrect password'); window.location='admin-login.html';</script>";
        }
    } else {
        echo "<script>alert('Admin not found'); window.location='admin-login.html';</script>";
    }

    $stmt->close();
}
$conn->close();
?>
