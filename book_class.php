<?php
session_start();
if (!isset($_SESSION['role']) || strtolower($_SESSION['role']) !== 'member') {
    header("Location: login.php");
    exit();
}

include('includes/db_config.php');
include('includes/header.php');
include('includes/sidebar.php');


$username = $_SESSION['username'] ?? '';

if (!$username) {
    die("<div class='alert alert-danger text-center mt-5'>❌ Username not found in session.</div>");
}

// Match username to email in members table
$member_result = mysqli_query($conn, "SELECT member_id FROM members WHERE email = '" . mysqli_real_escape_string($conn, $username) . "' LIMIT 1");
if (!$member_result) {
    die("<div class='alert alert-danger text-center mt-5'>❌ Member query failed: " . mysqli_error($conn) . "</div>");
}
$member = mysqli_fetch_assoc($member_result);
$member_id = $member['member_id'] ?? null;

if (!$member_id) {
    die("<div class='alert alert-danger text-center mt-5'>❌ Member not found for email: " . htmlspecialchars($username) . "</div>");
}

if (!$member_id) {
    die("<div class='alert alert-danger text-center mt-5'>❌ Member not found for email: " . htmlspecialchars($user_email) . "</div>");
}

$alert = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['class_id']) && isset($_POST['action']) && $member_id) {
        $class_id = intval($_POST['class_id']);
        if ($_POST['action'] === 'book') {
            $exists = mysqli_query($conn, "SELECT * FROM bookings WHERE member_id = $member_id AND class_id = $class_id");
            if (mysqli_num_rows($exists) === 0) {
                mysqli_query($conn, "INSERT INTO bookings (member_id, class_id, booking_date) VALUES ($member_id, $class_id, NOW())");
                $alert = "<div class='alert alert-success text-center'><i class='fas fa-check-circle me-1'></i> Class booked successfully!</div>";
            } else {
                $alert = "<div class='alert alert-warning text-center'><i class='fas fa-exclamation-circle me-1'></i> You already booked this class.</div>";
            }
        } elseif ($_POST['action'] === 'cancel') {
            mysqli_query($conn, "DELETE FROM bookings WHERE member_id = $member_id AND class_id = $class_id");
            $alert = "<div class='alert alert-danger text-center'><i class='fas fa-times-circle me-1'></i> Booking canceled.</div>";
        }
    }
}

$classes = mysqli_query($conn, "SELECT * FROM classes ORDER BY day_of_week, start_time");
$my_bookings = mysqli_query($conn, "SELECT c.*, b.booking_date FROM bookings b JOIN classes c ON b.class_id = c.class_id WHERE b.member_id = $member_id ORDER BY c.day_of_week, c.start_time");
?>

<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<div class="container mt-5">
  <div class="text-center mb-4">
    <h2 class="fw-bold text-primary">🏋️ Book a Fitness Class</h2>
    <p class="text-muted">Choose your session and track your bookings below.</p>
  </div>

  <?= $alert ?>

  <div class="card shadow mb-5">
    <div class="card-header bg-primary text-white fw-semibold d-flex justify-content-between">
      <span><i class="fas fa-calendar-plus me-2"></i>Available Classes</span>
    </div>
    <div class="card-body table-responsive">
      <table class="table table-bordered table-striped align-middle text-center">
        <thead class="table-primary">
          <tr>
            <th>Title</th>
            <th>Instructor</th>
            <th>Time</th>
            <th>Day</th>
            <th>Capacity</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($row = mysqli_fetch_assoc($classes)): ?>
          <tr>
            <td><?= htmlspecialchars($row['title']) ?></td>
            <td><?= htmlspecialchars($row['instructor']) ?></td>
            <td><?= $row['start_time'] ?> - <?= $row['end_time'] ?></td>
            <td><span class="badge bg-info text-dark"><?= $row['day_of_week'] ?></span></td>
            <td><span class="badge bg-dark"><?= $row['enrolled'] ?>/<?= $row['capacity'] ?></span></td>
            <td>
              <form method="POST" class="d-inline">
                <input type="hidden" name="class_id" value="<?= $row['class_id'] ?>">
                <input type="hidden" name="action" value="book">
                <button type="submit" class="btn btn-success btn-sm"><i class="fas fa-plus"></i> Book</button>
              </form>
            </td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>

  <div class="card shadow">
    <div class="card-header bg-success text-white fw-semibold">
      <i class="fas fa-clock me-2"></i>Your Booking History
    </div>
    <div class="card-body table-responsive">
      <table class="table table-striped table-hover align-middle text-center">
        <thead class="table-success">
          <tr>
            <th>Class</th>
            <th>Day</th>
            <th>Time</th>
            <th>Instructor</th>
            <th>Booked On</th>
            <th>Cancel</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($row = mysqli_fetch_assoc($my_bookings)): ?>
          <tr>
            <td><?= htmlspecialchars($row['title']) ?></td>
            <td><?= $row['day_of_week'] ?></td>
            <td><?= $row['start_time'] ?> - <?= $row['end_time'] ?></td>
            <td><?= htmlspecialchars($row['instructor']) ?></td>
            <td><?= $row['booking_date'] ?></td>
            <td>
              <form method="POST">
                <input type="hidden" name="class_id" value="<?= $row['class_id'] ?>">
                <input type="hidden" name="action" value="cancel">
                <button class="btn btn-outline-danger btn-sm"><i class="fas fa-times"></i></button>
              </form>
            </td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php include('includes/footer.php'); ?>
