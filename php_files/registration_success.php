<?php
$name = isset($_GET['name']) ? $_GET['name'] : '';
$user_id = isset($_GET['user_id']) ? $_GET['user_id'] : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Registration Successful</title>
    <style>
        body { font-family: Arial; background: #f8f9fa; display: flex; justify-content: center; align-items: center; height: 100vh; }
        .box { background: #fff; padding: 30px; border-radius: 10px; text-align: center; box-shadow: 0 4px 8px rgba(0,0,0,0.2); }
        h2 { color: red; }
        a { display: inline-block; margin-top: 20px; padding: 10px 20px; background: blue; color: white; text-decoration: none; border-radius: 5px; }
        a:hover { background: darkblue; }
    </style>
</head>
<body>
    <div class="box">
        <h2>
        <div id="logo">
        <img src="../Images/SCOM logo.png" alt="SCOM Logo" width="100">
       </div>
       Registration Successful!
       </h2>
        <p>Thank you, <?php echo htmlspecialchars($name); ?>!</p>
        <p>Your User ID is: <strong><?php echo htmlspecialchars($user_id); ?></strong></p>
        <p>Please keep this ID for future logins!</p>
        <a href="../html_files/mark_attendance.html">Confirm Attendance</a>
    </div>
</body>
</html>
