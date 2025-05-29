<?php
include('includes/session.php');
include('includes/db_config.php');

$role = strtolower($_SESSION['role'] ?? '');
if ($role !== 'admin') {
    die("Access denied. Admins only.");
}

include('includes/header.php');
include('includes/sidebar.php');
?>

<div class="bg-white p-6 rounded shadow max-w-3xl mx-auto">
    <h1 class="text-2xl font-bold mb-4 text-gray-800">System Settings</h1>

    <ul class="text-sm text-gray-700 space-y-3">
        <li><strong>System Name:</strong> FitTrack Pro – Gym Management System</li>
        <li><strong>Server:</strong> <?= $_SERVER['SERVER_NAME'] ?></li>
        <li><strong>PHP Version:</strong> <?= phpversion() ?></li>
        <li><strong>MySQL Host:</strong> <?= $host ?></li>
        <li><strong>Database:</strong> <?= $db ?></li>
        <li><strong>Current User:</strong> <?= $username ?> (<?= $role ?>)</li>
    </ul>

    <p class="mt-6 text-gray-500 text-sm">
        Note: This system is in development mode. Configuration management and user permissions will be extended in future versions.
    </p>
</div>

<?php include('includes/footer.php'); ?>
