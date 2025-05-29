<?php
session_start();
if (!isset($_SESSION['role']) || strtolower($_SESSION['role']) !== 'admin') {
    header("Location: login.php");
    exit();
}

include('includes/session.php');
include('includes/db_config.php');
include('includes/header.php');
include('includes/sidebar.php');

$total_members = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS count FROM members"))['count'];
$total_classes = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS count FROM classes"))['count'];
$revenue = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(amount) AS total FROM payments"))['total'] ?? 0;
$active_today = rand(30, 120);
$pending_members = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS count FROM members WHERE status = 'Pending'"))['count'];
?>

<div class="mb-6">
  <h1 class="text-2xl font-bold text-gray-800">Welcome Admin</h1>
  <p class="text-gray-600">Overview and admin shortcuts</p>
</div>

<!-- Dashboard Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
  <div class="bg-white p-4 rounded shadow">
    <h4 class="text-sm font-medium text-gray-600">Total Members</h4>
    <p class="text-2xl font-bold text-blue-700"><?= $total_members ?></p>
  </div>
  <div class="bg-white p-4 rounded shadow">
    <h4 class="text-sm font-medium text-gray-600">Active Today</h4>
    <p class="text-2xl font-bold text-green-600"><?= $active_today ?></p>
  </div>
  <div class="bg-white p-4 rounded shadow">
    <h4 class="text-sm font-medium text-gray-600">Revenue</h4>
    <p class="text-2xl font-bold text-yellow-600">$<?= number_format($revenue, 2) ?></p>
  </div>
  <div class="bg-white p-4 rounded shadow">
    <h4 class="text-sm font-medium text-gray-600">Pending Members</h4>
    <p class="text-2xl font-bold text-red-600"><?= $pending_members ?></p>
  </div>
</div>

<!-- Quick Admin Links -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
  <a href="add_user.php" class="bg-blue-600 hover:bg-blue-700 text-white p-4 rounded shadow text-center">
    <i class="fas fa-user-plus mr-2"></i>Add Member
  </a>
  <a href="add_payment.php" class="bg-teal-600 hover:bg-teal-700 text-white p-4 rounded shadow text-center">
    <i class="fas fa-dollar-sign mr-2"></i>Add Payment
  </a>
  <a href="mark_attendance.php" class="bg-indigo-600 hover:bg-indigo-700 text-white p-4 rounded shadow text-center">
    <i class="fas fa-check-circle mr-2"></i>Mark Attendance
  </a>
  <a href="admin_revenue.php" class="bg-yellow-500 hover:bg-yellow-600 text-white p-4 rounded shadow text-center">
    <i class="fas fa-wallet mr-2"></i>Revenue Report
  </a>
  <a href="classes.php" class="bg-green-600 hover:bg-green-700 text-white p-4 rounded shadow text-center">
    <i class="fas fa-calendar-alt mr-2"></i>View Classes
  </a>
  <a href="equipment.php" class="bg-purple-600 hover:bg-purple-700 text-white p-4 rounded shadow text-center">
    <i class="fas fa-dumbbell mr-2"></i>Equipment
  </a>
  <a href="payments.php" class="bg-gray-700 hover:bg-gray-800 text-white p-4 rounded shadow text-center">
    <i class="fas fa-credit-card mr-2"></i>Payments
  </a>
  <a href="admin_analytics.php" class="bg-pink-500 hover:bg-pink-600 text-white p-4 rounded shadow text-center">
    <i class="fas fa-chart-pie mr-2"></i>Analytics
  </a>
</div>

<!-- Chart -->
<div class="mt-8">
  <h2 class="text-xl font-bold text-gray-800 mb-4">Member Status Overview</h2>
  <canvas id="memberChart" width="400" height="400" class="bg-white p-4 rounded shadow"></canvas>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
  const ctx = document.getElementById('memberChart').getContext('2d');

  fetch('fetch_member_status.php')
    .then(response => response.json())
    .then(chartData => {
      new Chart(ctx, {
        type: 'pie',
        data: {
          labels: chartData.labels,
          datasets: [{
            data: chartData.data,
            backgroundColor: ['#38bdf8', '#facc15', '#f87171']
          }]
        },
        options: {
          responsive: false,
          plugins: {
            title: {
              display: true,
              text: 'Member Status Overview',
              font: { size: 20 }
            },
            legend: {
              labels: {
                font: { size: 14 }
              }
            }
          }
        }
      });
    });
});
</script>

</div>

<?php include('includes/footer.php'); ?>
