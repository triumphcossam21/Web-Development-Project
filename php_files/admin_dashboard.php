<?php
  session_start();

  // Redirect if not logged in
  if (!isset($_SESSION['admin_id'])) {
      header("Location: ../html_files/admin_login.html");
      exit();
  }

  // Connect to database
  $host = "localhost";
  $db_user = "root";
  $db_pass = "";
  $db_name = "attendance_system";

  $conn = new mysqli($host, $db_user, $db_pass, $db_name);
  if ($conn->connect_error) { 
    die("Connection failed: " . $conn->connect_error); 
  }

  // Fetch stats
  $studentsCount = $conn->query("SELECT COUNT(*) AS count FROM member_details")->fetch_assoc()['count'];
  // $ticketsIssued = $conn->query("SELECT COUNT(*) AS count FROM tickets")->fetch_assoc()['count'];
  // $ticketsVerified = $conn->query("SELECT COUNT(*) AS count FROM tickets WHERE verified=1")->fetch_assoc()['count'];
  $ticketsIssued = 60;
  $ticketsVerified = 55;
  // Fetch recent users (last 5)
  $recentUsers = $conn->query("
      SELECT m.name, m.phone, m.residence, m.user_id
      FROM member_details m
      INNER JOIN (
          SELECT user_id
          FROM register
          ORDER BY attendance_date DESC
          LIMIT 5
      ) r ON m.user_id = r.user_id
  ");


?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin Dashboard | MUBAS SCOM</title>
  <link rel="stylesheet" href="../css_files/dashboard.css" />
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap" rel="stylesheet">
  <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</head>
<body>
  <div class="container">
    <aside class="sidebar">
      <h2 class="logo">SCOM Admin</h2>
      <nav>
        <ul>
          <li class="active"><i class="fas fa-home"></i> Home</li>
          <li><a href="../html_files/admin_login.html"><i class="fas fa-sign-in-alt"></i> Logout</a></li>
          <li><a href="../html_files/index.html"><i class="fas fa-user-plus"></i> Scom Home Page</a></li>
        </ul>
      </nav>
    </aside>

    <main class="main-content">
      <header>
        <h1>Dashboard Overview</h1>
      </header>

      <section class="stats">
        <div class="card">
          <h3>Students Attended</h3>
          <p id="studentsCount"><?php echo $studentsCount; ?></p>
        </div>
        <div class="card">
          <h3>Tickets Issued</h3>
          <p id="ticketsIssued"><?php echo $ticketsIssued; ?></p>
        </div>
        <div class="card">
          <h3>Tickets Verified</h3>
          <p id="ticketsVerified"><?php echo $ticketsVerified; ?></p>
        </div>
      </section>

      <section class="recent-users">
        <h2>Recent Users</h2>
        <table id="usersTable">
          <thead>
            <tr>
              <th>NAME</th>
              <th>PHONE</th>
              <th>RESIDENCE</th>
              <th>USER ID</th>
            </tr>
          </thead>
          <tbody>
            <?php while($user = $recentUsers->fetch_assoc()): ?>
            <tr>
              <td><?php echo htmlspecialchars($user['name']); ?></td>
              <td><?php echo htmlspecialchars($user['phone']); ?></td>
              <td><?php echo htmlspecialchars($user['residence']); ?></td>
              <td><?php echo htmlspecialchars($user['user_id']); ?></td>
              
            </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </section>
    </main>
  </div>
</body>
</html>
