<?php
require_once 'partials/header.php';

$series = [
    'id' => '', 'title' => '', 'description' => '', 
    'poster' => '', 'year' => '', 'genre_id' => ''
];
$form_title = "Add New Series";

// Check if it's an edit request
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $form_title = "Edit Series";
    $series_id = (int)$_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM series WHERE id = ?");
    $stmt->bind_param("i", $series_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $series = $result->fetch_assoc();
    }
    $stmt->close();
}

// Handle form submission for both add and edit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $poster = $_POST['poster'];
    $year = $_POST['year'];
    $genre_id = !empty($_POST['genre_id']) ? (int)$_POST['genre_id'] : null;

    if (empty($id)) { // ADD NEW SERIES
        $stmt = $conn->prepare("INSERT INTO series (title, description, poster, year, genre_id) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssis", $title, $description, $poster, $year, $genre_id);
    } else { // UPDATE EXISTING SERIES
        $stmt = $conn->prepare("UPDATE series SET title = ?, description = ?, poster = ?, year = ?, genre_id = ? WHERE id = ?");
        $stmt->bind_param("sssisi", $title, $description, $poster, $year, $genre_id, $id);
    }

    if ($stmt->execute()) {
        echo "<script>window.location.href = 'series.php';</-script>";
    } else {
        echo "<div class='alert alert-danger'>Error: " . $stmt->error . "</div>";
    }
    $stmt->close();
}

// Fetch genres for the dropdown
$genres_result = $conn->query("SELECT * FROM genres ORDER BY name");

?>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800"><?php echo $form_title; ?></h1>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="series_form.php" method="POST">
                <input type="hidden" name="id" value="<?php echo $series['id']; ?>">

                <div class="mb-3">
                    <label for="title" class="form-label">Title</label>
                    <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($series['title']); ?>" required>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="5" required><?php echo htmlspecialchars($series['description']); ?></textarea>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="poster" class="form-label">Poster URL</label>
                        <input type="text" class="form-control" id="poster" name="poster" value="<?php echo htmlspecialchars($series['poster']); ?>" required>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="year" class="form-label">Year</label>
                        <input type="number" class="form-control" id="year" name="year" value="<?php echo htmlspecialchars($series['year']); ?>">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="genre_id" class="form-label">Genre</label>
                        <select class="form-select" id="genre_id" name="genre_id">
                            <option value="">Select Genre</option>
                             <?php while($genre = $genres_result->fetch_assoc()): ?>
                                <option value="<?php echo $genre['id']; ?>" <?php echo ($series['genre_id'] == $genre['id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($genre['name']); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Save Series</button>
                <a href="series.php" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>

<?php
require_once 'partials/footer.php';
?>