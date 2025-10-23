<?php
require_once 'partials/header.php';

// Fetch all users from the database
$users_result = $conn->query("SELECT * FROM users ORDER BY created_at DESC");

?>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Manage Users</h1>

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Registered At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($users_result->num_rows > 0): ?>
                            <?php while($user = $users_result->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $user['id']; ?></td>
                                    <td><?php echo htmlspecialchars($user['username']); ?></td>
                                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                                    <td><?php echo $user['created_at']; ?></td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-warning disabled"><i class="bi bi-pencil-square"></i> Edit</a>
                                        <a href="#" class="btn btn-sm btn-danger disabled"><i class="bi bi-trash"></i> Delete</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center">No users found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php
require_once 'partials/footer.php';
?>