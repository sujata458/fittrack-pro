<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$role = strtolower($_SESSION['role'] ?? '');
$current = basename($_SERVER['PHP_SELF']);
?>

<!-- Sidebar (fixed and scrollable) -->
<div id="sidebar" class="h-screen w-64 fixed top-0 left-0 bg-gradient-to-b from-gray-600 to-gray-800 text-white flex flex-col shadow-lg z-50 transition-transform transform">



    <!-- Logo Section -->
    <div class="p-4 flex items-center space-x-3 border-b border-blue-500">
        <div class="w-10 h-10 rounded-full bg-white flex items-center justify-center">
            <i class="fas fa-dumbbell text-blue-600 text-xl"></i>
        </div>
        <h1 class="text-xl font-bold">FitTrack Pro</h1>
    </div>

    <!-- Navigation -->
    <nav class="p-4 flex-1 overflow-y-auto">
        <div class="mb-6">
            <h3 class="text-xs uppercase tracking-wider text-blue-300 mb-3">Dashboard</h3>
            <ul class="space-y-2">
                <li>
                    <a href="<?= $role === 'admin' ? 'admin_dashboard.php' : ($role === 'trainer' ? 'trainer_dashboard.php' : 'member_dashboard.php') ?>" class="flex items-center space-x-3 p-2 rounded-lg hover:bg-green-700 transition <?= $current == ($role === 'admin' ? 'admin_dashboard.php' : ($role === 'trainer' ? 'trainer_dashboard.php' : 'member_dashboard.php')) ? 'bg-green-700' : '' ?>">
                        <i class="fas fa-home w-5"></i>
                        <span>Overview</span>
                    </a>
                </li>
            </ul>
        </div>

        <div class="mb-6">
            <h3 class="text-xs uppercase tracking-wider text-blue-300 mb-3">Management</h3>
            <ul class="space-y-2">
                <?php if ($role === 'admin'): ?>
                <li>
                    <a href="members.php" class="flex items-center space-x-3 p-2 rounded-lg hover:bg-green-700 transition <?= $current == 'members.php' ? 'bg-green-700' : '' ?>">
                        <i class="fas fa-user-check w-5"></i>
                        <span>Approve Members</span>
                    </a>
                </li>
                <li>
                    <a href="admin_members.php" class="flex items-center space-x-3 p-2 rounded-lg hover:bg-green-700 transition <?= $current == 'admin_members.php' ? 'bg-green-700' : '' ?>">
                        <i class="fas fa-users w-5"></i>
                        <span>Manage Members</span>
                    </a>
                </li>
                <li>
                    <a href="admin_subscriptions.php" class="flex items-center space-x-3 p-2 rounded-lg hover:bg-green-700 transition <?= $current == 'admin_subscriptions.php' ? 'bg-green-700' : '' ?>">
                        <i class="fas fa-sync w-5"></i>
                        <span>Subscriptions</span>
                    </a>
                </li>
                <li>
                    <a href="admin_revenue.php" class="flex items-center space-x-3 p-2 rounded-lg hover:bg-green-700 transition <?= $current == 'admin_revenue.php' ? 'bg-green-700' : '' ?>">
                        <i class="fas fa-chart-line w-5"></i>
                        <span>Revenue</span>
                    </a>
                </li>
                <?php endif; ?>

                <?php if (in_array($role, ['admin', 'trainer', 'member'])): ?>
                <li>
                    <a href="classes.php" class="flex items-center space-x-3 p-2 rounded-lg hover:bg-green-700 transition <?= $current == 'classes.php' ? 'bg-green-700' : '' ?>">
                        <i class="fas fa-calendar-alt w-5"></i>
                        <span>Classes</span>
                    </a>
                </li>
                <?php endif; ?>

                <?php if (in_array($role, ['admin', 'trainer'])): ?>
                <li>
                    <a href="equipment.php" class="flex items-center space-x-3 p-2 rounded-lg hover:bg-green-700 transition <?= $current == 'equipment.php' ? 'bg-green-700' : '' ?>">
                        <i class="fas fa-dumbbell w-5"></i>
                        <span>Equipment</span>
                    </a>
                </li>
                <?php endif; ?>

                <?php if (in_array($role, ['admin', 'trainer', 'member'])): ?>
                <li>
                    <a href="payments.php" class="flex items-center space-x-3 p-2 rounded-lg hover:bg-green-700 transition <?= $current == 'payments.php' ? 'bg-green-700' : '' ?>">
                        <i class="fas fa-file-invoice-dollar w-5"></i>
                        <span>Payments</span>
                    </a>
                </li>
                <?php endif; ?>
            </ul>
        </div>

        <div class="mb-6">
            <h3 class="text-xs uppercase tracking-wider text-blue-300 mb-3">Settings</h3>
            <ul class="space-y-2">
                <?php if ($role === 'admin'): ?>
                <li>
                    <a href="settings.php" class="flex items-center space-x-3 p-2 rounded-lg hover:bg-green-700 transition <?= $current == 'settings.php' ? 'bg-green-700' : '' ?>">
                        <i class="fas fa-cog w-5"></i>
                        <span>System Settings</span>
                    </a>
                </li>

                <li>
                    <a href="add_user.php" class="flex items-center space-x-3 p-2 rounded-lg hover:bg-green-700 transition <?= $current == 'add_user.php' ? 'bg-green-700' : '' ?>">
                        <i class="fas fa-user-shield w-5"></i>
                        <span>Add Staff</span>
                    </a>
                </li>
                <?php endif; ?>

                <li>
                    <a href="logout.php" class="flex items-center space-x-3 p-2 rounded-lg hover:bg-red-700 transition">
                        <i class="fas fa-sign-out-alt w-5"></i>
                        <span>Logout</span>
                    </a>
                </li>
            </ul>
        </div>
    </nav>
</div>

<!-- Sidebar toggle script (place before </body>) -->
<script>
    const sidebar = document.getElementById('sidebar');
    const toggleBtn = document.getElementById('sidebarToggle');

    // Load state from localStorage on page load
    const sidebarState = localStorage.getItem('sidebarOpen');
    if (sidebarState === 'false') {
        sidebar.classList.add('-translate-x-full');
    }

    toggleBtn?.addEventListener('click', () => {
        const isHidden = sidebar.classList.toggle('-translate-x-full');
        localStorage.setItem('sidebarOpen', !isHidden);
    });
</script>
