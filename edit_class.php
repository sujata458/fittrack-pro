<?php
include('includes/session.php');
include('includes/db_config.php');

// Admin-only access
if ($role !== 'Admin') {
    die("Access denied. Only admins can edit classes.");
}

// Validate class ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid class ID.");
}
$class_id = (int) $_GET['id'];

// Fetch existing class data
$stmt = $conn->prepare("SELECT * FROM classes WHERE class_id = ?");
$stmt->bind_param("i", $class_id);
$stmt->execute();
$result = $stmt->get_result();
$class = $result->fetch_assoc();
$stmt->close();

if (!$class) {
    die("Class not found.");
}

// Update logic
$success = $error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $instructor = trim($_POST['instructor']);
    $day = $_POST['day_of_week'];
    $start = $_POST['start_time'];
    $end = $_POST['end_time'];
    $capacity = (int) $_POST['capacity'];
    $enrolled = (int) $_POST['enrolled'];

    if ($title && $instructor && $day && $start && $end && $capacity > 0) {
        $stmt = $conn->prepare("UPDATE classes SET title=?, instructor=?, day_of_week=?, start_time=?, end_time=?, capacity=?, enrolled=? WHERE class_id=?");
        $stmt->bind_param("ssssssii", $title, $instructor, $day, $start, $end, $capacity, $enrolled, $class_id);

        if ($stmt->execute()) {
            $success = "Class updated successfully.";
            // Refresh class data
            $class = [
                'title' => $title,
                'instructor' => $instructor,
                'day_of_week' => $day,
                'start_time' => $start,
                'end_time' => $end,
                'capacity' => $capacity,
                'enrolled' => $enrolled
            ];
        } else {
            $error = "Update failed: " . $conn->error;
        }
        $stmt->close();
    } else {
        $error = "All fields are required and must be valid.";
    }
}
?>

<?php include('includes/header.php'); ?>
<?php include('includes/sidebar.php'); ?>

<div class="max-w-lg mx-auto bg-white p-6 rounded shadow">
    <h2 class="text-xl font-semibold mb-4">Edit Class</h2>

    <?php if ($success): ?>
        <p class="text-green-600 mb-4"><?= $success ?></p>
    <?php endif; ?>
    <?php if ($error): ?>
        <p class="text-red-600 mb-4"><?= $error ?></p>
    <?php endif; ?>

    <form method="POST" class="space-y-4">
        <div>
            <label class="block text-sm font-medium">Class Title</label>
            <input type="text" name="title" value="<?= htmlspecialchars($class['title']) ?>" required class="w-full px-3 py-2 border rounded">
        </div>

        <div>
            <label class="block text-sm font-medium">Instructor</label>
            <input type="text" name="instructor" value="<?= htmlspecialchars($class['instructor']) ?>" required class="w-full px-3 py-2 border rounded">
        </div>

        <div>
            <label class="block text-sm font-medium">Day of the Week</label>
            <select name="day_of_week" class="w-full px-3 py-2 border rounded" required>
                <?php
                foreach (['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'] as $day):
                    $selected = $day === $class['day_of_week'] ? 'selected' : '';
                    echo "<option value='$day' $selected>$day</option>";
                endforeach;
                ?>
            </select>
        </div>

        <div class="flex gap-4">
            <div class="flex-1">
                <label class="block text-sm font-medium">Start Time</label>
                <input type="time" name="start_time" value="<?= $class['start_time'] ?>" required class="w-full px-3 py-2 border rounded">
            </div>
            <div class="flex-1">
                <label class="block text-sm font-medium">End Time</label>
                <input type="time" name="end_time" value="<?= $class['end_time'] ?>" required class="w-full px-3 py-2 border rounded">
            </div>
        </div>

        <div class="flex gap-4">
            <div class="flex-1">
                <label class="block text-sm font-medium">Capacity</label>
                <input type="number" name="capacity" value="<?= $class['capacity'] ?>" min="1" required class="w-full px-3 py-2 border rounded">
            </div>
            <div class="flex-1">
                <label class="block text-sm font-medium">Enrolled</label>
                <input type="number" name="enrolled" value="<?= $class['enrolled'] ?>" min="0" max="<?= $class['capacity'] ?>" required class="w-full px-3 py-2 border rounded">
            </div>
        </div>

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            Update Class
        </button>
    </form>
</div>

<?php include('includes/footer.php'); ?>
