<?php
require_once 'partials/header.php';

if (!isset($_GET['series_id']) || !is_numeric($_GET['series_id'])) {
    header('Location: series.php');
    exit;
}
$series_id = (int)$_GET['series_id'];

// Handle Delete Action
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $id_to_delete = (int)$_GET['id'];
    $stmt = $conn->prepare("DELETE FROM episodes WHERE id = ?");
    $stmt->bind_param("i", $id_to_delete);
    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>Episode deleted successfully.</div>";
    } else {
        echo "<div class='alert alert-danger'>Error deleting episode.</div>";
    }
    $stmt->close();
}

// Fetch series title
$series_title_stmt = $conn->prepare("SELECT title FROM series WHERE id = ?");
$series_title_stmt->bind_param("i", $series_id);
$series_title_stmt->execute();
$series_title_result = $series_title_stmt->get_result();
$series_title = $series_title_result->fetch_assoc()['title'] ?? 'Series';

// Fetch all episodes for the series
$episodes_result = $conn->prepare("SELECT * FROM episodes WHERE series_id = ? ORDER BY season_number, episode_number");
$episodes_result->bind_param("i", $series_id);
$episodes_result->execute();
$episodes = $episodes_result->get_result();

?>

<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Manage Episodes for: <strong><?php echo htmlspecialchars($series_title); ?></strong></h1>
        <a href="episode_form.php?series_id=<?php echo $series_id; ?>" class="btn btn-primary"><i class="bi bi-plus-circle"></i> Add New Episode</a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Season</th>
                            <th>Episode</th>
                            <th>Title</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($episodes->num_rows > 0): ?>
                            <?php while($episode = $episodes->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($episode['season_number']); ?></td>
                                    <td><?php echo htmlspecialchars($episode['episode_number']); ?></td>
                                    <td><?php echo htmlspecialchars($episode['title']); ?></td>
                                    <td>
                                        <a href="episode_form.php?id=<?php echo $episode['id']; ?>&series_id=<?php echo $series_id; ?>" class="btn btn-sm btn-warning"><i class="bi bi-pencil-square"></i> Edit</a>
                                        <a href="episodes.php?series_id=<?php echo $series_id; ?>&action=delete&id=<?php echo $episode['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this episode?')"><i class="bi bi-trash"></i> Delete</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="text-center">No episodes found for this series.</td>
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