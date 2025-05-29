<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

include('includes/db_config.php');
include('includes/header.php');
include('includes/sidebar.php');

if (!isset($_GET['id'])) {
    die("❌ Invalid request.");
}

$member_id = intval($_GET['id']);
$member = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM members WHERE member_id = $member_id"));

if (!$member) {
    die("❌ Member not found.");
}

$success = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $status = mysqli_real_escape_string($conn, $_POST['membership_status']);
    $join_date = mysqli_real_escape_string($conn, $_POST['join_date']);

    $update = mysqli_query($conn, "UPDATE members SET full_name='$full_name', email='$email', membership_status='$status', join_date='$join_date' WHERE member_id=$member_id");

    if ($update) {
        $success = "✅ Member updated successfully.";
        $member = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM members WHERE member_id = $member_id"));
    } else {
        $success = "❌ Update failed: " . mysqli_error($conn);
    }
}
?>

<div class="container mt-4">
    <h2 class="mb-3">✏️ Edit Member</h2>

    <?php if ($success): ?>
        <div class="alert alert-info"><?= $success ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Full Name</label>
            <input type="text" name="full_name" class="form-control" value="<?= htmlspecialchars($member['full_name']) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($member['email']) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Membership Status</label>
            <select name="membership_status" class="form-select">
                <option value="Active" <?= $member['membership_status'] === 'Active' ? 'selected' : '' ?>>Active</option>
                <option value="Inactive" <?= $member['membership_status'] === 'Inactive' ? 'selected' : '' ?>>Inactive</option>
                <option value="Trial" <?= $member['membership_status'] === 'Trial' ? 'selected' : '' ?>>Trial</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Join Date</label>
            <input type="date" name="join_date" class="form-control" value="<?= $member['join_date'] ?>">
        </div>
        <button type="submit" class="btn btn-success">Update Member</button>
        <a href="admin_members.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<?php include('includes/footer.php'); ?>
