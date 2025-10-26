<?php
session_start();

$host = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "attendance";

$conn = new mysqli($host, $db_user, $db_pass, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO admins (username, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $username, $hashed_password);

    if ($stmt->execute()) {
        echo "<script>alert('Admin registered successfully'); window.location='admin-login.html';</script>";
    } else {
        echo "<script>alert('Error: Username may already exist'); window.location='admin-register.html';</script>";
    }

    $stmt->close();
}
$conn->close();
?>
