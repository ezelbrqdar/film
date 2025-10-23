<?php
require_once 'partials/header.php';

$series_id = isset($_GET['series_id']) ? (int)$_GET['series_id'] : 0;

$episode = [
    'id' => '', 'title' => '', 'season_number' => '1', 
    'episode_number' => '1', 'embed_code' => '', 'series_id' => $series_id
];
$form_title = "Add New Episode";

// Check if it's an edit request
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $form_title = "Edit Episode";
    $episode_id = (int)$_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM episodes WHERE id = ?");
    $stmt->bind_param("i", $episode_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $episode = $result->fetch_assoc();
        $series_id = $episode['series_id']; // Ensure series_id is correct
    }
    $stmt->close();
}

if ($series_id === 0) {
    die("Series ID is missing.");
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $post_series_id = (int)$_POST['series_id'];
    $title = $_POST['title'];
    $season_number = (int)$_POST['season_number'];
    $episode_number = (int)$_POST['episode_number'];
    $embed_code = $_POST['embed_code'];

    if (empty($id)) { // ADD
        $stmt = $conn->prepare("INSERT INTO episodes (series_id, season_number, episode_number, title, embed_code) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("iiiss", $post_series_id, $season_number, $episode_number, $title, $embed_code);
    } else { // UPDATE
        $stmt = $conn->prepare("UPDATE episodes SET season_number = ?, episode_number = ?, title = ?, embed_code = ? WHERE id = ?");
        $stmt->bind_param("iissi", $season_number, $episode_number, $title, $embed_code, $id);
    }

    if ($stmt->execute()) {
        echo "<script>window.location.href = 'episodes.php?series_id=$post_series_id';</script>";
    } else {
        echo "<div class='alert alert-danger'>Error: " . $stmt->error . "</div>";
    }
    $stmt->close();
}

?>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800"><?php echo $form_title; ?></h1>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="episode_form.php?series_id=<?php echo $series_id; ?>" method="POST">
                <input type="hidden" name="id" value="<?php echo $episode['id']; ?>">
                <input type="hidden" name="series_id" value="<?php echo $series_id; ?>">

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="season_number" class="form-label">Season Number</label>
                        <input type="number" class="form-control" id="season_number" name="season_number" value="<?php echo htmlspecialchars($episode['season_number']); ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="episode_number" class="form-label">Episode Number</label>
                        <input type="number" class="form-control" id="episode_number" name="episode_number" value="<?php echo htmlspecialchars($episode['episode_number']); ?>" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="title" class="form-label">Episode Title</label>
                    <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($episode['title']); ?>" required>
                </div>

                <div class="mb-3">
                    <label for="embed_code" class="form-label">Embed Code (Iframe)</label>
                    <textarea class="form-control" id="embed_code" name="embed_code" rows="3" required><?php echo htmlspecialchars($episode['embed_code']); ?></textarea>
                </div>

                <button type="submit" class="btn btn-primary">Save Episode</button>
                <a href="episodes.php?series_id=<?php echo $series_id; ?>" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>

<?php
require_once 'partials/footer.php';
?>