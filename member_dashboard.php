<?php
session_start();

if (!isset($_SESSION['role']) || strtolower($_SESSION['role']) !== 'member') {
    header("Location: login.php");
    exit();
}

include('includes/db_config.php');

$username = $_SESSION['username'];
$member_result = mysqli_query($conn, "SELECT * FROM members WHERE email = '$username' OR full_name = '$username' LIMIT 1");

if (!$member_result || mysqli_num_rows($member_result) == 0) {
    die("<div class='alert alert-danger'>❌ Member not found for user: " . htmlspecialchars($username) . "</div>");
}

$member = mysqli_fetch_assoc($member_result);
$member_id = $member['member_id'];
$member_name = $member['full_name'] ?? 'Member';

// Sample values for goals, subscriptions, etc.
$sub_result = mysqli_query($conn, "SELECT * FROM subscriptions WHERE member_id = $member_id ORDER BY start_date DESC LIMIT 1");
$subscription = $sub_result ? mysqli_fetch_assoc($sub_result) : null;

$debit_result = mysqli_query($conn, "SELECT * FROM direct_debit_mandates WHERE member_id = $member_id LIMIT 1");
$debit = $debit_result ? mysqli_fetch_assoc($debit_result) : null;

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Member Dashboard | FitTrack Pro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<!-- Navigation Sidebar -->
<div class="position-fixed top-0 start-0 h-100 bg-dark text-white p-3" style="width: 220px;">
    <h4 class="text-white mb-4"><i class="fas fa-dumbbell me-2"></i>FitTrack Pro</h4>
    <ul class="nav flex-column">
        <li class="nav-item mb-2"><a href="member_dashboard.php" class="nav-link text-white"><i class="fas fa-chart-line me-2"></i>Dashboard</a></li>
        <li class="nav-item mb-2"><a href="manage_subscription.php" class="nav-link text-white"><i class="fas fa-credit-card me-2"></i>Subscribe</a></li>
        <li class="nav-item mb-2"><a href="setup_direct_debit.php" class="nav-link text-white"><i class="fas fa-university me-2"></i>Direct Debit</a></li>
        <li class="nav-item mt-4"><a href="logout.php" class="nav-link text-danger"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
    </ul>
</div>
<div class="ms-5 ps-4">


<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>👋 Hello, <?= htmlspecialchars($member_name) ?>!</h2>
        <a href="logout.php" class="btn btn-danger">Logout</a>
    </div>

    <div class="row g-4">
        <!-- Book a Class -->
        <div class="col-md-6">
            <div class="card border-success">
                <div class="card-header bg-success text-white">📅 Book a Class</div>
                <div class="card-body">
                    <form method="POST" action="book_class.php">
                        <label class="form-label">Select Class</label>
                        <select name="class_id" class="form-select mb-3" required>
                            <?php
                            $class_query = mysqli_query($conn, "SELECT * FROM classes ORDER BY day_of_week, start_time");
                            while ($class = mysqli_fetch_assoc($class_query)) {
                                echo "<option value='{$class['class_id']}'>{$class['title']} - {$class['day_of_week']} at {$class['start_time']}</option>";
                            }
                            ?>
                        </select>
                        <button type="submit" class="btn btn-success w-100">Book</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Upcoming Classes -->
        <div class="col-md-6">
            <div class="card border-success">
                <div class="card-header bg-success text-white">📅 Upcoming Classes</div>
                <div class="card-body">
                    <p class="text-muted">No upcoming classes.</p>
                </div>
            </div>
        </div>

        <!-- Goal Tracker -->
        <div class="col-md-6">
            <div class="card border-info">
                <div class="card-header bg-info text-white">🎯 Fitness Goal Tracker</div>
                <div class="card-body">
                    <p>Your weekly goal progress:</p>
                    <div class="progress mb-2"><div class="progress-bar" style="width: 60%">60%</div></div>
                    <small>Goal: Attend 5 classes this week</small>
                </div>
            </div>
        </div>

        <!-- Subscription -->
        <div class="col-md-6">
            <div class="card border-warning">
                <div class="card-header bg-warning text-dark">💳 Subscription</div>
                <div class="card-body">
                    <?php if ($subscription): ?>
                        <p>Plan: <strong><?= $subscription['plan_name'] ?></strong></p>
                        <p>Status: <?= $subscription['status'] ?></p>
                        <p>Next Billing: <?= $subscription['next_billing_date'] ?></p>
                    <?php else: ?>
                        <p>No active subscription. Please subscribe.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Direct Debit -->
        <div class="col-md-6">
            <div class="card border-secondary">
                <div class="card-header bg-secondary text-white">🏦 Direct Debit</div>
                <div class="card-body">
                    <?php if ($debit): ?>
                        <p>Bank: <strong><?= $debit['bank_name'] ?></strong></p>
                        <p>Account: ****<?= substr($debit['account_number'], -4) ?></p>
                    <?php else: ?>
                        <p>No direct debit setup found.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- BMI Calculator -->
        <div class="col-md-6">
            <div class="card border-dark">
                <div class="card-header bg-dark text-white">📊 BMI Calculator</div>
                <div class="card-body">
                    <form id="bmiForm">
                        <input type="number" id="height" class="form-control mb-2" placeholder="Height (cm)" required>
                        <input type="number" id="weight" class="form-control mb-2" placeholder="Weight (kg)" required>
                        <button type="submit" class="btn btn-outline-dark w-100">Calculate BMI</button>
                    </form>
                    <p class="mt-3" id="bmiResult"></p>
                </div>
            </div>
        </div>

        <!-- Profile Info -->
        <div class="col-md-6">
            <div class="card border-info">
                <div class="card-header bg-info text-white">📋 Profile Info</div>
                <div class="card-body">
                    <ul class="list-group">
                        <li class="list-group-item"><strong>Email:</strong> <?= htmlspecialchars($member['email'] ?? '') ?></li>
                        <li class="list-group-item"><strong>Phone:</strong> <?= htmlspecialchars($member['phone'] ?? '') ?></li>
                        <li class="list-group-item"><strong>Join Date:</strong> <?= htmlspecialchars($member['join_date'] ?? '') ?></li>
                        <li class="list-group-item"><strong>Status:</strong> <?= htmlspecialchars($member['status'] ?? '') ?></li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Announcements -->
        <div class="col-md-6">
            <div class="card border-warning">
                <div class="card-header bg-warning text-dark">📢 Announcements</div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">🧘 New Yoga classes every Saturday at 9 AM</li>
                        <li class="list-group-item">⚙️ Cardio machines under maintenance next week</li>
                        <li class="list-group-item">🎁 Refer a friend and get 1 month free!</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Chat with Trainer -->
        <div class="col-md-6">
            <div class="card border-primary">
                <div class="card-header bg-primary text-white">💬 Chat with Trainer</div>
                <div class="card-body">
                    <form method="POST" action="send_message.php">
                        <textarea name="message" rows="3" class="form-control mb-2" placeholder="Type your message..." required></textarea>
                        <button type="submit" class="btn btn-primary w-100">Send</button>
                    </form>
                    <hr>
                    <h6>Trainer Responses</h6>
                    <p class="text-muted">No messages yet.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById("bmiForm").addEventListener("submit", function(e) {
    e.preventDefault();
    const h = parseFloat(document.getElementById("height").value) / 100;
    const w = parseFloat(document.getElementById("weight").value);
    if (h > 0 && w > 0) {
        const bmi = (w / (h * h)).toFixed(2);
        document.getElementById("bmiResult").innerText = "Your BMI is " + bmi;
    } else {
        document.getElementById("bmiResult").innerText = "Please enter valid height and weight.";
    }
});
</script>

</body>
</html>
