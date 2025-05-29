<?php
session_start();
include('includes/session.php');
include('includes/db_config.php');
include('includes/header.php');
include('includes/sidebar.php');

if (strtolower($_SESSION['role']) !== 'admin') {
    echo "<div class='p-4 text-red-600'>Access denied.</div>";
    exit();
}

$members = mysqli_query($conn, "SELECT * FROM members ORDER BY member_id DESC");
?>

<div class="p-6">
    <h2 class="text-2xl font-bold text-gray-800 mb-4">👥 Member Management</h2>
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border rounded shadow text-sm">
            <thead class="bg-gray-100 text-gray-700">
                <tr>
                    <th class="py-2 px-4 border">ID</th>
                    <th class="py-2 px-4 border">Full Name</th>
                    <th class="py-2 px-4 border">Email</th>
                    <th class="py-2 px-4 border">Phone</th>
                    <th class="py-2 px-4 border">Join Date</th>
                    <th class="py-2 px-4 border">Status</th>
                    <th class="py-2 px-4 border">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($members)): ?>
                    <tr class="text-center hover:bg-gray-50">
                        <td class="py-2 px-4 border"><?= $row['member_id'] ?></td>
                        <td class="py-2 px-4 border"><?= htmlspecialchars($row['full_name']) ?></td>
                        <td class="py-2 px-4 border"><?= htmlspecialchars($row['email']) ?></td>
                        <td class="py-2 px-4 border"><?= htmlspecialchars($row['phone']) ?></td>
                        <td class="py-2 px-4 border"><?= $row['join_date'] ?></td>
                        <td class="py-2 px-4 border">
                            <span class="px-2 py-1 rounded-full text-xs <?=
                                $row['status'] === 'Active' ? 'bg-green-100 text-green-700' :
                                ($row['status'] === 'Pending' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700')
                            ?>">
                                <?= $row['status'] ?>
                            </span>
                        </td>
                        <td class="py-2 px-4 border">
                            <a href="edit_member.php?id=<?= $row['member_id'] ?>" class="text-blue-600 hover:underline">Edit</a>
                            |
                            <a href="delete_member.php?id=<?= $row['member_id'] ?>" onclick="return confirm('Are you sure you want to delete this member?');" class="text-red-600 hover:underline">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include('includes/footer.php'); ?>
