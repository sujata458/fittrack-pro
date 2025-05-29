<?php
session_start();
include('includes/session.php');
include('includes/db_config.php');
include('includes/header.php');
include('includes/sidebar.php');

if (strtolower($_SESSION['role']) !== 'admin') {
    echo "<div class='p-4 text-red-600 font-semibold'>Access denied. Admins only.</div>";
    exit();
}

$members = mysqli_query($conn, "SELECT member_id, full_name FROM members WHERE status = 'Active' ORDER BY full_name");
$classes = mysqli_query($conn, "SELECT class_id, title FROM classes ORDER BY title");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['member_id'], $_POST['class_id'])) {
    $member_id = $_POST['member_id'];
    $class_id = $_POST['class_id'];
    $status = $_POST['status'];

    $stmt = $conn->prepare("INSERT INTO attendance (member_id, class_id, status) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE status = ?");
    $stmt->bind_param("iiss", $member_id, $class_id, $status, $status);
    $stmt->execute();
    $success = true;
}
?>

<div class="p-6">
  <h2 class="text-2xl font-bold mb-4 text-gray-800">Mark Attendance</h2>
  <?php if (!empty($success)): ?>
    <div class="bg-green-100 text-green-800 p-3 rounded mb-4">✅ Attendance marked successfully.</div>
  <?php endif; ?>

  <form method="POST" class="bg-white p-6 rounded shadow space-y-4 max-w-xl">
    <div>
      <label class="block text-sm font-medium">Select Member</label>
      <select name="member_id" class="w-full mt-1 border rounded px-3 py-2" required>
        <option value="">Choose Member</option>
        <?php while ($m = mysqli_fetch_assoc($members)): ?>
          <option value="<?= $m['member_id'] ?>"><?= htmlspecialchars($m['full_name']) ?></option>
        <?php endwhile; ?>
      </select>
    </div>

    <div>
      <label class="block text-sm font-medium">Select Class</label>
      <select name="class_id" class="w-full mt-1 border rounded px-3 py-2" required>
        <option value="">Choose Class</option>
        <?php while ($c = mysqli_fetch_assoc($classes)): ?>
          <option value="<?= $c['class_id'] ?>"><?= htmlspecialchars($c['title']) ?></option>
        <?php endwhile; ?>
      </select>
    </div>

    <div>
      <label class="block text-sm font-medium">Status</label>
      <select name="status" class="w-full mt-1 border rounded px-3 py-2">
        <option value="Present">Present</option>
        <option value="Absent">Absent</option>
      </select>
    </div>

    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded">Submit</button>
  </form>
</div>

<?php include('includes/footer.php'); ?>
