<?php
session_start();
include('includes/session.php');
include('includes/db_config.php');
include('includes/header.php');
include('includes/sidebar.php');

if (strtolower($_SESSION['role']) !== 'trainer') {
    echo "<div class='p-4 text-red-600 font-semibold'>Access denied. Trainers only.</div>";
    exit();
}

$username = $_SESSION['username'];
$class_id = isset($_GET['class_id']) ? intval($_GET['class_id']) : 0;
$success = false;

// Fetch the class and make sure it belongs to the trainer
$class_query = mysqli_query($conn, "SELECT * FROM classes WHERE class_id = $class_id AND instructor = '$username'");
if (!$class_query || mysqli_num_rows($class_query) == 0) {
    echo "<div class='p-4 text-red-600 font-semibold'>Class not found or you don't have access to this class.</div>";
    exit();
}
$class = mysqli_fetch_assoc($class_query);

// Fetch enrolled members
$members_query = mysqli_query($conn, "
    SELECT m.member_id, m.full_name 
    FROM bookings b 
    JOIN members m ON b.member_id = m.member_id 
    WHERE b.class_id = $class_id
");

// On submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['attendance'])) {
    $date = date('Y-m-d');

    foreach ($_POST['attendance'] as $member_id => $status) {
        $member_id = intval($member_id);
        $status = $status === 'Present' ? 'Present' : 'Absent';

        $stmt = $conn->prepare("INSERT INTO attendance (member_id, class_id, date, status)
            VALUES (?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE status = ?");
        $stmt->bind_param("iisss", $member_id, $class_id, $date, $status, $status);
        $stmt->execute();
    }

    $success = true;
}
?>

<div class="p-6">
  <h2 class="text-2xl font-bold mb-4 text-gray-800">Mark Attendance - <?= htmlspecialchars($class['title']) ?></h2>

  <?php if ($success): ?>
    <div class="bg-green-100 text-green-800 p-3 rounded mb-4">✅ Attendance submitted successfully for <?= date('Y-m-d') ?>.</div>
  <?php endif; ?>

  <form method="POST" class="bg-white p-6 rounded shadow max-w-2xl space-y-4">
    <table class="w-full table-auto border border-gray-300">
      <thead>
        <tr class="bg-gray-100 text-left">
          <th class="p-2 border">Member</th>
          <th class="p-2 border">Status</th>
        </tr>
      </thead>
      <tbody>
<?php
if (mysqli_num_rows($members_query) > 0):
    while ($m = mysqli_fetch_assoc($members_query)): ?>
        <tr>
            <td><?= htmlspecialchars($m['full_name']) ?></td>
            <td>
                <select name="attendance[<?= $m['member_id'] ?>]" class="form-select">
                    <option value="Present">Present</option>
                    <option value="Absent">Absent</option>
                </select>
            </td>
        </tr>
<?php endwhile; else: ?>
        <tr>
            <td colspan="2" class="text-center text-muted">No members enrolled for this class.</td>
        </tr>
<?php endif; ?>
</tbody>

    </table>
    <button type="submit" class="mt-4 bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded">Submit Attendance</button>
    <a href="trainer_dashboard.php" class="ml-3 inline-block text-sm text-gray-600 underline">← Back to Dashboard</a>
  </form>
</div>

<?php include('includes/footer.php'); ?>
