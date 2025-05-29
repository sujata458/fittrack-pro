<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome | FitTrack Pro</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800">

    <!-- Hero Section -->
    <section class="bg-white shadow">
        <div class="max-w-6xl mx-auto px-6 py-12 flex flex-col lg:flex-row items-center gap-10">
            <div class="flex-1">
                <h1 class="text-4xl font-bold text-blue-700 mb-4">Welcome to FitTrack Pro</h1>
                <p class="text-gray-600 text-lg">Your all-in-one gym management solution for admins, trainers, and members.</p>
                <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <a href="login.php" class="bg-blue-600 hover:bg-blue-700 text-white py-3 px-5 rounded text-sm font-semibold transition">
                        Admin / Trainer Login
                    </a>
                    <a href="member_login.php" class="bg-green-600 hover:bg-green-700 text-white py-3 px-5 rounded text-sm font-semibold transition">
                        Member Login
                    </a>
                    <a href="member_register.php" class="bg-yellow-500 hover:bg-yellow-600 text-white py-3 px-5 rounded text-sm font-semibold transition sm:col-span-2 text-center">
                        Register as a Member
                    </a>
                </div>
            </div>
            <div class="flex-1">
                <img src="assets/img/gym-welcome.jpeg" alt="Gym Illustration" class="rounded-lg shadow-lg w-full">
            </div>
        </div>
    </section>

    <!-- About Us -->
    <section class="bg-blue-50 py-12">
        <div class="max-w-6xl mx-auto px-6 grid grid-cols-1 lg:grid-cols-2 gap-10 items-center">
            <div>
                <img src="assets/img/about-gym.jpeg" alt="About Us Gym" class="rounded shadow-lg">
            </div>
            <div>
                <h2 class="text-3xl font-bold mb-4 text-blue-800">About FitTrack Pro</h2>
                <p class="text-gray-700 text-lg mb-4">
                    FitTrack Pro is a comprehensive gym management system built for efficiency, clarity, and control. We simplify the way gyms handle memberships, class schedules, staff roles, and member participation.
                </p>
                <ul class="list-disc pl-5 text-gray-700 space-y-2">
                    <li>Admin & Trainer role separation</li>
                    <li>Member self-service portal</li>
                    <li>Class booking and withdrawal features</li>
                    <li>Clear dashboard metrics</li>
                    <li>Fast, mobile-responsive design</li>
                </ul>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="text-center text-sm text-gray-500 py-6 border-t bg-white">
        &copy; <?= date('Y') ?> FitTrack Pro. All rights reserved.
    </footer>

</body>
</html>
