<?php
require_once 'includes/header.php';

// 1. Get Movie ID from URL and validate it
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    // A more user-friendly error page could be created
    die("Invalid Movie ID.");
}
$movie_id = (int)$_GET['id'];

// 2. Fetch movie details from the database with genre
$stmt = $conn->prepare("
    SELECT movies.*, genres.name as genre_name
    FROM movies
    LEFT JOIN genres ON movies.genre_id = genres.id
    WHERE movies.id = ?
");
$stmt->bind_param("i", $movie_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Movie not found.");
}

$movie = $result->fetch_assoc();
$stmt->close();

?>

<!-- SEO Optimization -->
<title><?php echo htmlspecialchars($movie['title']); ?> - CineStream</title>
<meta name="description" content="<?php echo htmlspecialchars(substr(strip_tags($movie['description']), 0, 160)); ?>">

<div class="row">
    <!-- Video Player -->
    <div class="col-12">
        <div class="ratio ratio-16x9 bg-dark">
            <?php 
                // 4. Display the sanitized video player
                echo get_sanitized_iframe($movie['embed_code']); 
            ?>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-3">
        <img src="<?php echo htmlspecialchars($movie['poster']); ?>" class="img-fluid rounded" alt="<?php echo htmlspecialchars($movie['title']); ?>">
    </div>
    <div class="col-md-9">
        <h2><?php echo htmlspecialchars($movie['title']); ?></h2>
        <p class="text-muted">
            <span><?php echo htmlspecialchars($movie['year']); ?></span>
            <?php if ($movie['genre_name']): ?>
                <span class="mx-2">|</span>
                <a href="#" class="badge bg-danger text-decoration-none"><?php echo htmlspecialchars($movie['genre_name']); ?></a>
            <?php endif; ?>
        </p>
        <hr>
        <h4>الوصف</h4>
        <p><?php echo nl2br(htmlspecialchars($movie['description'])); ?></p>
        <!-- Other details like actors can be added here later -->
    </div>
</div>


<?php
require_once 'includes/footer.php';
?>