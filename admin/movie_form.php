<?php
require_once 'partials/header.php';

$movie = [
    'id' => '', 'title' => '', 'description' => '', 
    'poster' => '', 'year' => '', 'genre_id' => '', 'embed_code' => ''
];
$form_title = "Add New Movie";

// Check if it's an edit request
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $form_title = "Edit Movie";
    $movie_id = (int)$_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM movies WHERE id = ?");
    $stmt->bind_param("i", $movie_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $movie = $result->fetch_assoc();
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
    $embed_code = $_POST['embed_code'];

    if (empty($id)) { // ADD NEW MOVIE
        $stmt = $conn->prepare("INSERT INTO movies (title, description, poster, year, genre_id, embed_code) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssiis", $title, $description, $poster, $year, $genre_id, $embed_code);
    } else { // UPDATE EXISTING MOVIE
        $stmt = $conn->prepare("UPDATE movies SET title = ?, description = ?, poster = ?, year = ?, genre_id = ?, embed_code = ? WHERE id = ?");
        $stmt->bind_param("sssiisi", $title, $description, $poster, $year, $genre_id, $embed_code, $id);
    }

    if ($stmt->execute()) {
        echo "<script>window.location.href = 'movies.php';</-script>";
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
            <form action="movie_form.php" method="POST">
                <input type="hidden" name="id" value="<?php echo $movie['id']; ?>">

                <div class="mb-3">
                    <label for="title" class="form-label">Title</label>
                    <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($movie['title']); ?>" required>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="5" required><?php echo htmlspecialchars($movie['description']); ?></textarea>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="poster" class="form-label">Poster URL</label>
                        <input type="text" class="form-control" id="poster" name="poster" value="<?php echo htmlspecialchars($movie['poster']); ?>" required>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="year" class="form-label">Year</label>
                        <input type="number" class="form-control" id="year" name="year" value="<?php echo htmlspecialchars($movie['year']); ?>">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="genre_id" class="form-label">Genre</label>
                        <select class="form-select" id="genre_id" name="genre_id">
                            <option value="">Select Genre</option>
                            <?php while($genre = $genres_result->fetch_assoc()): ?>
                                <option value="<?php echo $genre['id']; ?>" <?php echo ($movie['genre_id'] == $genre['id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($genre['name']); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="embed_code" class="form-label">Embed Code (Iframe)</label>
                    <textarea class="form-control" id="embed_code" name="embed_code" rows="3" required><?php echo htmlspecialchars($movie['embed_code']); ?></textarea>
                </div>

                <button type="submit" class="btn btn-primary">Save Movie</button>
                <a href="movies.php" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>

<?php
require_once 'partials/footer.php';
?>