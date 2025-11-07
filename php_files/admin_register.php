<?php
session_start();

$host = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "attendance_system";

$conn = new mysqli($host, $db_user, $db_pass, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO admins (username, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $email, $hashed_password);

    if ($stmt->execute()) {
        echo "<script>alert('Admin registered successfully'); window.location='../html_files/admin_login.html';</script>";
    } else {
        echo "<script>alert('Error: Username may already exist'); window.location='../html_files/admin_login.html';</script>";
    }

    $stmt->close();
}
$conn->close();
?>
