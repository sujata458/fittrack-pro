<?php
session_start();
include('includes/db_config.php');
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$members = mysqli_query($conn, "SELECT * FROM members ORDER BY join_date DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Members</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="p-4 bg-light">
<div class="container">
  <h2 class="mb-4">👥 Manage Members</h2>
  <table class="table table-bordered bg-white shadow-sm">
    <thead class="table-light">
      <tr>
        <th>Name</th>
        <th>Email</th>
        <th>Phone</th>
        <th>Status</th>
        <th>Join Date</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($m = mysqli_fetch_assoc($members)): ?>
        <tr>
          <td><?= htmlspecialchars($m['full_name']) ?></td>
          <td><?= htmlspecialchars($m['email']) ?></td>
          <td><?= htmlspecialchars($m['phone']) ?></td>
          <td><?= $m['status'] ?></td>
          <td><?= $m['join_date'] ?></td>
          <td>
            <a href="edit_member.php?id=<?= $m['member_id'] ?>" class="btn btn-sm btn-warning">Edit</a>
            <a href="delete_member.php?id=<?= $m['member_id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this member?')">Delete</a>
          </td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>
</body>
</html>
