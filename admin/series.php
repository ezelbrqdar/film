<?php
require_once 'partials/header.php';

// Handle Delete Action
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $id_to_delete = (int)$_GET['id'];
    // You might want to also delete related episodes or handle them as you see fit
    $stmt = $conn->prepare("DELETE FROM series WHERE id = ?");
    $stmt->bind_param("i", $id_to_delete);
    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>Series deleted successfully.</div>";
    } else {
        echo "<div class='alert alert-danger'>Error deleting series.</div>";
    }
    $stmt->close();
}

// Fetch all series from the database
$series_result = $conn->query("SELECT series.*, genres.name as genre_name FROM series LEFT JOIN genres ON series.genre_id = genres.id ORDER BY created_at DESC");

?>

<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Manage Series</h1>
        <a href="series_form.php" class="btn btn-primary"><i class="bi bi-plus-circle"></i> Add New Series</a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Poster</th>
                            <th>Title</th>
                            <th>Year</th>
                            <th>Genre</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($series_result->num_rows > 0): ?>
                            <?php while($series_item = $series_result->fetch_assoc()): ?>
                                <tr>
                                    <td><img src="<?php echo htmlspecialchars($series_item['poster']); ?>" width="60" alt=""></td>
                                    <td><?php echo htmlspecialchars($series_item['title']); ?></td>
                                    <td><?php echo htmlspecialchars($series_item['year']); ?></td>
                                    <td><span class="badge bg-secondary"><?php echo htmlspecialchars($series_item['genre_name'] ?? 'N/A'); ?></span></td>
                                    <td>
                                        <a href="series_form.php?id=<?php echo $series_item['id']; ?>" class="btn btn-sm btn-warning"><i class="bi bi-pencil-square"></i> Edit</a>
                                        <a href="episodes.php?series_id=<?php echo $series_item['id']; ?>" class="btn btn-sm btn-info"><i class="bi bi-list-ul"></i> Episodes</a>
                                        <a href="series.php?action=delete&id=<?php echo $series_item['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this series and all its episodes?')"><i class="bi bi-trash"></i> Delete</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center">No series found.</td>
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