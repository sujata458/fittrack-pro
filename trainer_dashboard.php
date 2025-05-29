<?php
session_start();
if (strtolower($_SESSION['role']) !== 'trainer') {
    header("Location: login.php");
    exit();
}
include('includes/db_config.php');
include('includes/header.php');
$username = $_SESSION['username'];
$today = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');
$day_of_week = date('l', strtotime($today));
?>

<!-- Sidebar Toggle for Mobile -->
<div class="lg:hidden p-4 bg-gray-700 text-white">
    <button id="sidebarToggle"><i class="fas fa-bars text-xl"></i></button>
</div>

<div class="flex">
    <!-- Sidebar -->
    <aside id="sidebar" class="w-64 bg-gray-800 text-white min-h-screen fixed top-0 left-0 shadow-lg z-40">
        <div class="p-4 border-b border-gray-700 flex items-center space-x-3">
            <i class="fas fa-dumbbell text-xl"></i>
            <a href="trainer_dashboard.php" class="text-xl font-semibold hover:underline">FitTrack Pro</a>
        </div>
        <nav class="p-4 space-y-6 overflow-y-auto h-full">
            <div>
                <h3 class="text-xs uppercase tracking-wider text-gray-400 mb-3">Dashboard</h3>
                <a href="trainer_dashboard.php" class="flex items-center space-x-3 p-2 rounded bg-blue-600">
                    <i class="fas fa-home w-5"></i>
                    <span>Overview</span>
                </a>
            </div>
            <div>
                <h3 class="text-xs uppercase tracking-wider text-gray-400 mb-3">Management</h3>
                <a href="classes.php" class="flex items-center space-x-3 p-2 rounded hover:bg-blue-600">
                    <i class="fas fa-calendar-alt w-5"></i>
                    <span>Classes</span>
                </a>
                <a href="equipment.php" class="flex items-center space-x-3 p-2 rounded hover:bg-blue-600">
                    <i class="fas fa-dumbbell w-5"></i>
                    <span>Equipment</span>
                </a>
                <a href="payments.php" class="flex items-center space-x-3 p-2 rounded hover:bg-blue-600">
                    <i class="fas fa-file-invoice-dollar w-5"></i>
                    <span>Payments</span>
                </a>
            </div>
            <div>
                <h3 class="text-xs uppercase tracking-wider text-gray-400 mb-3">Settings</h3>
                <a href="logout.php" class="flex items-center space-x-3 p-2 rounded hover:bg-red-600">
                    <i class="fas fa-sign-out-alt w-5"></i>
                    <span>Logout</span>
                </a>
            </div>
        </nav>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 bg-gray-100 min-h-screen p-4 ml-64">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <style>
        .hero-header {
          background: linear-gradient(135deg, #007bff, #00c6ff);
          color: white;
          padding: 2rem;
          border-radius: 0.5rem;
          margin-bottom: 2rem;
        }
        </style>

        <div class="container">
            <div class="hero-header text-center shadow-sm">
                <h2 class="fw-bold mb-1">Welcome Trainer, <?= htmlspecialchars($username) ?> 👋</h2>
                <p class="mb-0">Manage your schedule, track attendance, and stay connected with members.</p>
            </div>

            <!-- Summary Cards -->
            <?php
            $total_classes = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM classes WHERE instructor = '$username'"))['total'];
            $today_classes = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as today_total FROM classes WHERE instructor = '$username' AND day_of_week = '$day_of_week'"))['today_total'];
            $booked_today = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as booked FROM bookings b JOIN classes c ON b.class_id = c.class_id WHERE c.instructor = '$username' AND c.day_of_week = '$day_of_week'"))['booked'];
            ?>
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="card text-white bg-primary shadow h-100">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <h5>Total Classes</h5>
                                <h3><?= $total_classes ?></h3>
                            </div>
                            <i class="fas fa-calendar-alt fa-2x"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-white bg-success shadow h-100">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <h5>Today’s Sessions</h5>
                                <h3><?= $today_classes ?></h3>
                            </div>
                            <i class="fas fa-clock fa-2x"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-white bg-info shadow h-100">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <h5>Bookings Today</h5>
                                <h3><?= $booked_today ?></h3>
                            </div>
                            <i class="fas fa-users fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Trainer Classes Section -->
            <h3 class="text-xl font-semibold mb-4">Class Schedule</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <?php
                $class_query = mysqli_query($conn, "SELECT * FROM classes WHERE instructor = '$username' ORDER BY FIELD(day_of_week, 'Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday')");
                while ($class = mysqli_fetch_assoc($class_query)):
                    $enrolled_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as enrolled FROM bookings WHERE class_id = " . $class['class_id']))['enrolled'];
                ?>
                <div class="bg-white p-4 rounded shadow border-l-4 border-blue-500">
                    <h4 class="font-bold text-lg mb-1"><?= htmlspecialchars($class['title']) ?></h4>
                    <p class="text-sm text-gray-600 mb-1">
                        <i class="far fa-clock me-1"></i><?= $class['start_time'] ?> - <?= $class['end_time'] ?>
                    </p>
                    <p class="text-sm text-gray-600 mb-1">
                        <i class="fas fa-chalkboard-teacher me-1"></i>Instructor: <?= htmlspecialchars($class['instructor']) ?>
                    </p>
                    <p class="text-sm text-gray-600">
                        <i class="fas fa-user-friends me-1"></i>Enrolled: <?= $enrolled_count ?>/<?= isset($class['max_capacity']) ? $class['max_capacity'] : 'N/A' ?>
                    </p>
                    <div class="mt-3 flex flex-col gap-2">
                        <span class="inline-block px-3 py-1 text-xs font-semibold bg-blue-100 text-blue-800 rounded-full w-max">
                            <?= $class['day_of_week'] ?>
                        </span>

                        <a href="trainer_mark_attendance.php?class_id=<?= $class['class_id'] ?>" 
                           class="inline-block bg-green-600 hover:bg-green-700 text-white text-sm px-4 py-1 rounded text-center">
                            Mark Attendance
                        </a>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script>
        const sidebar = document.getElementById('sidebar');
        const toggleBtn = document.getElementById('sidebarToggle');
        const stored = localStorage.getItem('sidebarOpen');
        if (stored === 'false') sidebar.classList.add('-translate-x-full');
        toggleBtn?.addEventListener('click', () => {
            const hidden = sidebar.classList.toggle('-translate-x-full');
            localStorage.setItem('sidebarOpen', !hidden);
        });
        </script>

        <?php include('includes/footer.php'); ?>
    </div>
</div>
