<?php
include('includes/session.php');
include('includes/db_config.php');
include('includes/header.php');
include('includes/sidebar.php');

// Sample data (replace with DB query if you store equipment in a table)
$equipment = [
    ['name' => 'Treadmill', 'usage' => 85, 'image' => 'treadmill.jpeg'],
    ['name' => 'Elliptical', 'usage' => 72, 'image' => 'elliptical.jpeg'],
    ['name' => 'Stationary Bike', 'usage' => 90, 'image' => 'bike.jpeg'],
    ['name' => 'Leg Press', 'usage' => 65, 'image' => 'legpress.jpeg']
];
?>

<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Equipment Usage</h1>
    <p class="text-sm text-gray-500">Visual status of equipment utilization</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
    <?php foreach ($equipment as $item): ?>
        <div class="bg-white rounded-xl shadow p-4 text-center hover:shadow-md transition">
            <img src="assets/img/equipment/<?= $item['image'] ?>" alt="<?= $item['name'] ?>" class="w-24 h-24 object-cover mx-auto mb-3 rounded">
            <h2 class="font-semibold text-gray-800"><?= $item['name'] ?></h2>
            <div class="w-full bg-gray-200 h-2 rounded mt-2">
                <div class="bg-green-500 h-2 rounded" style="width: <?= $item['usage'] ?>%;"></div>
            </div>
            <p class="text-sm text-gray-600 mt-1"><?= $item['usage'] ?>% usage</p>
        </div>
    <?php endforeach; ?>
</div>

<?php include('includes/footer.php'); ?>
