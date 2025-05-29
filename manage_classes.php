<?php
session_start();
include('includes/db_config.php');
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$classes = mysqli_query($conn, "SELECT * FROM classes ORDER BY day_of_week, start_time");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Classes</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="p-4 bg-light">
<div class="container">
  <h2 class="mb-4">📝 Manage Classes</h2>
  <table class="table table-bordered bg-white shadow-sm">
    <thead class="table-light">
      <tr>
        <th>Title</th>
        <th>Instructor</th>
        <th>Day</th>
        <th>Time</th>
        <th>Capacity</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($c = mysqli_fetch_assoc($classes)): ?>
        <tr>
          <td><?= htmlspecialchars($c['title']) ?></td>
          <td><?= htmlspecialchars($c['instructor']) ?></td>
          <td><?= $c['day_of_week'] ?></td>
          <td><?= $c['start_time'] ?>–<?= $c['end_time'] ?></td>
          <td><?= $c['enrolled'] ?>/<?= $c['capacity'] ?></td>
          <td>
            <a href="edit_class.php?id=<?= $c['class_id'] ?>" class="btn btn-sm btn-warning">Edit</a>
            <a href="delete_class.php?id=<?= $c['class_id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this class?')">Delete</a>
          </td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>
</body>
</html>
