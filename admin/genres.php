<?php
require_once 'partials/header.php';

$genre = ['id' => '', 'name' => ''];
$form_title = "Add New Genre";
$form_action = "genres.php";

// Handle Edit Request
if (isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['id'])) {
    $form_title = "Edit Genre";
    $genre_id = (int)$_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM genres WHERE id = ?");
    $stmt->bind_param("i", $genre_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $genre = $result->fetch_assoc();
    }
    $stmt->close();
}

// Handle Delete Action
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $id_to_delete = (int)$_GET['id'];
    $stmt = $conn->prepare("DELETE FROM genres WHERE id = ?");
    $stmt->bind_param("i", $id_to_delete);
    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>Genre deleted successfully.</div>";
    } else {
        echo "<div class='alert alert-danger'>Error deleting genre.</div>";
    }
    $stmt->close();
}

// Handle Form Submission (Add/Edit)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $name = $_POST['name'];

    if (empty($id)) { // ADD
        $stmt = $conn->prepare("INSERT INTO genres (name) VALUES (?)");
        $stmt->bind_param("s", $name);
    } else { // UPDATE
        $stmt = $conn->prepare("UPDATE genres SET name = ? WHERE id = ?");
        $stmt->bind_param("si", $name, $id);
    }

    if ($stmt->execute()) {
        echo "<script>window.location.href = 'genres.php';</script>";
    } else {
        echo "<div class='alert alert-danger'>Error: " . $stmt->error . "</div>";
    }
    $stmt->close();
}

// Fetch all genres
$genres_result = $conn->query("SELECT * FROM genres ORDER BY name");

?>

<div class="container-fluid">
    <div class="row">
        <!-- Form Column -->
        <div class="col-md-4">
            <h1 class="h3 mb-4 text-gray-800"><?php echo $form_title; ?></h1>
            <div class="card shadow mb-4">
                <div class="card-body">
                    <form action="<?php echo $form_action; ?>" method="POST">
                        <input type="hidden" name="id" value="<?php echo $genre['id']; ?>">
                        <div class="mb-3">
                            <label for="name" class="form-label">Genre Name</label>
                            <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($genre['name']); ?>" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Save Genre</button>
                        <?php if ($form_title == 'Edit Genre'): ?>
                            <a href="genres.php" class="btn btn-secondary">Cancel</a>
                        <?php endif; ?>
                    </form>
                </div>
            </div>
        </div>

        <!-- Table Column -->
        <div class="col-md-8">
            <h1 class="h3 mb-4 text-gray-800">Existing Genres</h1>
            <div class="card shadow mb-4">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($genres_result->num_rows > 0): ?>
                                    <?php while($g = $genres_result->fetch_assoc()): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($g['name']); ?></td>
                                            <td>
                                                <a href="genres.php?action=edit&id=<?php echo $g['id']; ?>" class="btn btn-sm btn-warning"><i class="bi bi-pencil-square"></i> Edit</a>
                                                <a href="genres.php?action=delete&id=<?php echo $g['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')"><i class="bi bi-trash"></i> Delete</a>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="2" class="text-center">No genres found.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
require_once 'partials/footer.php';
?>