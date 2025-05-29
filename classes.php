<?php
session_start();
include('includes/session.php');
include('includes/db_config.php');
include('includes/header.php');
include('includes/sidebar.php');

$role = strtolower($_SESSION['role'] ?? '');

// Fetch class schedule
$classes = mysqli_query($conn, "SELECT * FROM classes 
    ORDER BY FIELD(day_of_week, 'Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'), start_time");
?>

<div class="mb-6 flex justify-between items-center">
    <h1 class="text-2xl font-bold text-gray-800">Class Schedule</h1>
    <?php if ($role === 'admin'): ?>
        <a href="add_class.php" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            <i class="fas fa-plus mr-2"></i>Add Class
        </a>
    <?php endif; ?>
</div>
<!-- 📋 Class Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
    <?php while ($class = mysqli_fetch_assoc($classes)): ?>
        <div class="bg-white p-5 rounded shadow hover:shadow-md transition space-y-2">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold"><?= htmlspecialchars($class['title']) ?></h2>
                <span class="text-sm px-2 py-1 rounded-full bg-blue-100 text-blue-700">
                    <?= $class['day_of_week'] ?>
                </span>
            </div>
            <p class="text-sm text-gray-600">⏰ <?= $class['start_time'] ?> - <?= $class['end_time'] ?></p>
            <p class="text-sm text-gray-600">👤 Instructor: <?= htmlspecialchars($class['instructor']) ?></p>
            <p class="text-sm text-gray-600">👥 Enrolled: <?= $class['enrolled'] ?>/<?= $class['capacity'] ?></p>

            <?php if ($role === 'admin'): ?>
                <div class="flex gap-3 pt-2">
                    <a href="edit_class.php?id=<?= $class['class_id'] ?>" class="text-blue-600 text-sm hover:underline">Edit</a>
                    <form method="POST" action="delete_class.php" onsubmit="return confirm('Are you sure you want to delete this class?');">
                        <input type="hidden" name="class_id" value="<?= $class['class_id'] ?>">
                        <button type="submit" class="text-red-600 text-sm hover:underline">Delete</button>
                    </form>
                </div>
            <?php endif; ?>
        </div>
    <?php endwhile; ?>
</div>

<?php include('includes/footer.php'); ?>
