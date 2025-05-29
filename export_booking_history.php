<?php
session_start();
if (!isset($_SESSION['role']) || strtolower($_SESSION['role']) !== 'member') {
    header("Location: login.php");
    exit();
}
require_once 'includes/db_config.php';
require_once 'vendor/autoload.php'; // Ensure you have dompdf in vendor

use Dompdf\Dompdf;

$username = $_SESSION['username'];
$member_result = mysqli_query($conn, "SELECT member_id FROM members WHERE full_name = '$username' OR email = '$username'");
$member = mysqli_fetch_assoc($member_result);
$member_id = $member['member_id'] ?? null;

$bookings = mysqli_query($conn, "SELECT c.*, b.booking_date FROM bookings b JOIN classes c ON b.class_id = c.class_id WHERE b.member_id = $member_id ORDER BY c.day_of_week, c.start_time");

$html = "<h2>FitTrack Pro - Booking History</h2><table border='1' cellspacing='0' cellpadding='6' style='width:100%; font-family: Arial; font-size: 14px;'>
<tr><th>Class</th><th>Day</th><th>Time</th><th>Instructor</th><th>Booking Date</th></tr>";

while ($row = mysqli_fetch_assoc($bookings)) {
    $html .= "<tr>
        <td>{$row['title']}</td>
        <td>{$row['day_of_week']}</td>
        <td>{$row['start_time']} - {$row['end_time']}</td>
        <td>{$row['instructor']}</td>
        <td>{$row['booking_date']}</td>
    </tr>";
}
$html .= "</table>";

$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("booking_history.pdf", array("Attachment" => true));
exit();
?>
