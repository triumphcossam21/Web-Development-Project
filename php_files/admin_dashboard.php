<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: ../html_files/admin_login.html");
    exit();
}

$host = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "attendance_system";

$conn = new mysqli($host, $db_user, $db_pass, $db_name);
if ($conn->connect_error) { 
  die("Connection failed: " . $conn->connect_error); 
}

$studentsCount = $conn->query("SELECT COUNT(*) AS count FROM member_details")->fetch_assoc()['count'];
$ticketsIssued = $conn->query("SELECT COUNT(*) AS count FROM tickets")->fetch_assoc()['count'];
$ticketsVerified = $conn->query("SELECT COUNT(*) AS count FROM tickets WHERE verified=1")->fetch_assoc()['count'];

$recentUsers = $conn->query("
    SELECT m.name, m.phone, m.residence, m.user_id, t.ticket_code, t.verified
    FROM member_details m
    LEFT JOIN tickets t ON m.user_id = t.user_id
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
<style>
button.verify-btn {
    padding: 5px 10px;
    border: none;
    border-radius: 5px;
    color: white;
    cursor: pointer;
    width: 90px;
    text-align: center;
}
button.verify-btn.verify { background: green; }
button.verify-btn.unverify { background: red; }
</style>
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
              <th>TICKET CODE</th>
              <th>VERIFIED</th>
              <th>ACTION</th>
            </tr>
          </thead>
          <tbody>
            <?php while($user = $recentUsers->fetch_assoc()): ?>
            <tr id="row-<?php echo $user['ticket_code']; ?>">
              <td><?php echo htmlspecialchars($user['name']); ?></td>
              <td><?php echo htmlspecialchars($user['phone']); ?></td>
              <td><?php echo htmlspecialchars($user['residence']); ?></td>
              <td><?php echo htmlspecialchars($user['user_id']); ?></td>
              <td><?php echo htmlspecialchars($user['ticket_code'] ?? 'N/A'); ?></td>
              <td id="status-<?php echo $user['ticket_code']; ?>"><?php echo $user['verified'] ? 'Yes' : 'No'; ?></td>
              <td>
                <?php if($user['ticket_code']): ?>
                <button class="verify-btn <?php echo $user['verified'] ? 'unverify' : 'verify'; ?>" 
                        onclick="toggleVerify('<?php echo $user['ticket_code']; ?>')">
                  <?php echo $user['verified'] ? 'Unverify' : 'Verify'; ?>
                </button>
                <?php else: ?>
                  N/A
                <?php endif; ?>
              </td>
            </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </section>
    </main>
</div>

<script>
function toggleVerify(ticketCode) {
    fetch('../php_files/toggle_ticket.php?ticket_code=' + ticketCode)
    .then(response => response.json())
    .then(data => {
        if(data.success){
            const statusCell = document.getElementById('status-' + ticketCode);
            const btn = document.querySelector('#row-' + ticketCode + ' button');
            statusCell.textContent = data.verified ? 'Yes' : 'No';
            btn.textContent = data.verified ? 'Unverify' : 'Verify';
            btn.className = 'verify-btn ' + (data.verified ? 'unverify' : 'verify');

            // Update Tickets Verified card
            const ticketsVerifiedCard = document.getElementById('ticketsVerified');
            let currentCount = parseInt(ticketsVerifiedCard.textContent);
            ticketsVerifiedCard.textContent = data.verified ? currentCount + 1 : currentCount - 1;
        } else {
            alert('Failed to update ticket.');
        }
    });
}
</script>
</body>
</html>
