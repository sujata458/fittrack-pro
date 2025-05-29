<?php
include('includes/session.php');
include('includes/db_config.php');
include('includes/header.php');
include('includes/sidebar.php');

$members = mysqli_query($conn, "SELECT * FROM members ORDER BY join_date DESC");
?>

<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Member Approvals</h1>
    <p class="text-sm text-gray-500">Change member status to manage access.</p>
</div>

<div class="overflow-x-auto bg-white rounded shadow">
    <table class="min-w-full text-sm text-left text-gray-600">
        <thead class="bg-gray-100 text-gray-700 uppercase text-xs">
            <tr>
                <th class="p-4">Name</th>
                <th class="p-4">Email</th>
                <th class="p-4">Phone</th>
                <th class="p-4">Join Date</th>
                <th class="p-4">Status</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($members)): ?>
                <tr class="border-b hover:bg-gray-50">
                    <td class="p-4"><?= htmlspecialchars($row['full_name']) ?></td>
                    <td class="p-4"><?= htmlspecialchars($row['email']) ?></td>
                    <td class="p-4"><?= htmlspecialchars($row['phone']) ?></td>
                    <td class="p-4"><?= $row['join_date'] ?></td>
                    <td class="p-4">
                        <form method="POST" action="update_member_status.php">
                            <input type="hidden" name="member_id" value="<?= $row['member_id'] ?>">
                            <select name="status" onchange="this.form.submit()" class="text-sm px-2 py-1 border rounded">
                                <option <?= $row['status'] === 'Pending' ? 'selected' : '' ?>>Pending</option>
                                <option <?= $row['status'] === 'Active' ? 'selected' : '' ?>>Active</option>
                                <option <?= $row['status'] === 'Inactive' ? 'selected' : '' ?>>Inactive</option>
                            </select>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php include('includes/footer.php'); ?>
