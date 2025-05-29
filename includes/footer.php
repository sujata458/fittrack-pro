<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!-- Optional: Footer bar -->
<footer class="bg-gray-100 text-center text-sm text-gray-500 py-4 border-t mt-6">
    &copy; <?= date('Y') ?> FitTrack Pro. All rights reserved.
</footer>
