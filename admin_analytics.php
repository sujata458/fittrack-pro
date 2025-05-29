<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

include('includes/db_config.php');
include('includes/header.php');
include('includes/sidebar.php');

// Subscription status counts
$statusCounts = ['Active' => 0, 'Paused' => 0, 'Cancelled' => 0];
$statusQuery = "SELECT status, COUNT(*) AS count FROM subscriptions GROUP BY status";
$statusResult = mysqli_query($conn, $statusQuery);
while ($row = mysqli_fetch_assoc($statusResult)) {
    $statusCounts[$row['status']] = $row['count'];
}

// Monthly revenue
$revenueLabels = [];
$revenueData = [];
$revenueQuery = "SELECT DATE_FORMAT(payment_date, '%b %Y') as month, SUM(amount) as total 
                 FROM payments 
                 WHERE payment_date >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
                 GROUP BY DATE_FORMAT(payment_date, '%Y-%m')
                 ORDER BY DATE_FORMAT(payment_date, '%Y-%m') ASC";
$revenueResult = mysqli_query($conn, $revenueQuery);
while ($row = mysqli_fetch_assoc($revenueResult)) {
    $revenueLabels[] = $row['month'];
    $revenueData[] = $row['total'];
}

// Member join trends
$joinLabels = [];
$joinData = [];
$joinQuery = "SELECT DATE_FORMAT(join_date, '%b %Y') as month, COUNT(*) as count 
              FROM members 
              WHERE join_date >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
              GROUP BY DATE_FORMAT(join_date, '%Y-%m')
              ORDER BY DATE_FORMAT(join_date, '%Y-%m') ASC";
$joinResult = mysqli_query($conn, $joinQuery);
while ($row = mysqli_fetch_assoc($joinResult)) {
    $joinLabels[] = $row['month'];
    $joinData[] = $row['count'];
}
?>

<div class="container mt-5">
    <h2 class="mb-4 text-primary"><i class="fas fa-chart-pie me-2"></i>Analytics Dashboard</h2>

    <div class="row mb-5">
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm p-3">
                <h5 class="text-center">Subscription Status</h5>
                <canvas id="statusChart" height="300"></canvas>
            </div>
        </div>
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm p-3">
                <h5 class="text-center">Monthly Revenue</h5>
                <canvas id="revenueChart" height="300"></canvas>
            </div>
        </div>
    </div>

    <div class="row mb-5">
        <div class="col-md-12">
            <div class="card shadow-sm p-3">
                <h5 class="text-center">New Member Signups</h5>
                <canvas id="memberChart" height="300"></canvas>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const statusCtx = document.getElementById('statusChart').getContext('2d');
new Chart(statusCtx, {
    type: 'pie',
    data: {
        labels: ['Active', 'Paused', 'Cancelled'],
        datasets: [{
            data: [<?= $statusCounts['Active'] ?>, <?= $statusCounts['Paused'] ?>, <?= $statusCounts['Cancelled'] ?>],
            backgroundColor: ['#4CAF50', '#FFC107', '#F44336']
        }]
    },
    options: {
        plugins: {
            legend: { position: 'bottom' },
            title: { display: true, text: 'Subscription Status Overview', font: { size: 16 } }
        }
    }
});

const revenueCtx = document.getElementById('revenueChart').getContext('2d');
new Chart(revenueCtx, {
    type: 'bar',
    data: {
        labels: <?= json_encode($revenueLabels) ?>,
        datasets: [{
            label: 'Revenue ($)',
            data: <?= json_encode($revenueData) ?>,
            backgroundColor: '#2196F3'
        }]
    },
    options: {
        scales: { y: { beginAtZero: true } },
        plugins: {
            title: { display: true, text: 'Last 6 Months Revenue', font: { size: 16 } },
            legend: { display: false }
        }
    }
});

const memberCtx = document.getElementById('memberChart').getContext('2d');
new Chart(memberCtx, {
    type: 'line',
    data: {
        labels: <?= json_encode($joinLabels) ?>,
        datasets: [{
            label: 'New Members',
            data: <?= json_encode($joinData) ?>,
            fill: false,
            borderColor: '#673AB7',
            tension: 0.3
        }]
    },
    options: {
        scales: { y: { beginAtZero: true } },
        plugins: {
            title: { display: true, text: 'New Members (Last 6 Months)', font: { size: 16 } }
        }
    }
});
</script>

<?php include('includes/footer.php'); ?>
