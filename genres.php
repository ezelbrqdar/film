<?php 
require_once 'includes/header.php'; 

$genres = $conn->query("SELECT * FROM genres ORDER BY name");

$selected_genre_id = isset($_GET['id']) ? (int)$_GET['id'] : null;
$movies = [];
$series = [];
$genre_name = 'All Genres';

if ($selected_genre_id) {
    // Fetch genre name
    $g_stmt = $conn->prepare("SELECT name FROM genres WHERE id = ?");
    $g_stmt->bind_param("i", $selected_genre_id);
    $g_stmt->execute();
    $g_result = $g_stmt->get_result();
    if ($g_result->num_rows > 0) {
        $genre_name = $g_result->fetch_assoc()['name'];
    }

    // Fetch movies for this genre
    $m_stmt = $conn->prepare("SELECT * FROM movies WHERE genre_id = ? ORDER BY year DESC");
    $m_stmt->bind_param("i", $selected_genre_id);
    $m_stmt->execute();
    $movies_result = $m_stmt->get_result();
    while($row = $movies_result->fetch_assoc()) {
        $movies[] = $row;
    }

    // Fetch series for this genre
    $s_stmt = $conn->prepare("SELECT * FROM series WHERE genre_id = ? ORDER BY year DESC");
    $s_stmt->bind_param("i", $selected_genre_id);
    $s_stmt->execute();
    $series_result = $s_stmt->get_result();
    while($row = $series_result->fetch_assoc()) {
        $series[] = $row;
    }
}
?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-3">
            <div class="list-group">
                 <a href="genres.php" class="list-group-item list-group-item-action <?php echo !$selected_genre_id ? 'active' : ''; ?>">All Genres</a>
                <?php while($genre = $genres->fetch_assoc()): ?>
                    <a href="genres.php?id=<?php echo $genre['id']; ?>" class="list-group-item list-group-item-action <?php echo ($selected_genre_id == $genre['id']) ? 'active' : ''; ?>">
                        <?php echo htmlspecialchars($genre['name']); ?>
                    </a>
                <?php endwhile; ?>
            </div>
        </div>
        <div class="col-md-9">
            <h2 class="mb-4">Browsing: <?php echo htmlspecialchars($genre_name); ?></h2>

            <?php if (!$selected_genre_id): ?>
                 <div class="alert alert-info">Select a genre from the list to see the related movies and series.</div>
            <?php else: ?>
                <!-- Movies -->
                <h3 class="mb-3">Movies</h3>
                <div class="row">
                    <?php if(!empty($movies)):
                        foreach($movies as $movie):
                    ?>
                        <div class="col-6 col-md-4 col-lg-3 mb-4">
                             <div class="card h-100">
                                <a href="watch.php?type=movie&id=<?php echo $movie['id']; ?>">
                                    <img src="<?php echo htmlspecialchars($movie['poster']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($movie['title']); ?>">
                                </a>
                                <div class="card-body">
                                    <h5 class="card-title"><a href="watch.php?type=movie&id=<?php echo $movie['id']; ?>"><?php echo htmlspecialchars($movie['title']); ?></a></h5>
                                </div>
                            </div>
                        </div>
                    <?php 
                        endforeach;
                    else:
                        echo "<p>No movies found in this genre.</p>";
                    endif; 
                    ?>
                </div>

                <hr class="my-4">

                <!-- Series -->
                <h3 class="mb-3">Series</h3>
                <div class="row">
                    <?php if(!empty($series)):
                        foreach($series as $item):
                    ?>
                        <div class="col-6 col-md-4 col-lg-3 mb-4">
                             <div class="card h-100">
                                <a href="watch.php?type=series&id=<?php echo $item['id']; ?>">
                                    <img src="<?php echo htmlspecialchars($item['poster']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($item['title']); ?>">
                                </a>
                                <div class="card-body">
                                    <h5 class="card-title"><a href="watch.php?type=series&id=<?php echo $item['id']; ?>"><?php echo htmlspecialchars($item['title']); ?></a></h5>
                                </div>
                            </div>
                        </div>
                    <?php 
                        endforeach;
                    else:
                        echo "<p>No series found in this genre.</p>";
                    endif;
                    ?>
                </div>
            <?php endif; ?>

        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>