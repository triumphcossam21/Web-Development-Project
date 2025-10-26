<?php
$user_id = isset($_GET['user_id']) ? $_GET['user_id'] : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Attendance Recorded</title>
    <style>
        body { font-family: Arial; background: #f8f9fa; display: flex; justify-content: center; align-items: center; height: 100vh; }
        .box { background: #fff; padding: 30px; border-radius: 10px; text-align: center; box-shadow: 0 4px 8px rgba(0,0,0,0.2); }
        h2 { color: green; }
        a { display: inline-block; margin-top: 20px; padding: 10px 20px; background: blue; color: white; text-decoration: none; border-radius: 5px; }
        a:hover { background: darkblue; }
    </style>
</head>
<body>
    <div class="box">
        <h2>Attendance Recorded!</h2>
        <p>Thank you! Your attendance for today has been marked.</p>
        <p>User ID: <strong><?php echo htmlspecialchars($user_id); ?></strong></p>
        <a href="regular_attendance.html">Back to Attendance Form</a>
    </div>
</body>
</html>
