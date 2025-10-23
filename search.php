<?php 
require_once 'includes/header.php'; 

$query = isset($_GET['q']) ? trim($_GET['q']) : '';
$movies = [];
$series = [];

if (!empty($query)) {
    $search_term = "%{$query}%";

    // Search movies
    $m_stmt = $conn->prepare("SELECT * FROM movies WHERE title LIKE ? OR description LIKE ?");
    $m_stmt->bind_param("ss", $search_term, $search_term);
    $m_stmt->execute();
    $movies_result = $m_stmt->get_result();
    while($row = $movies_result->fetch_assoc()) {
        $movies[] = $row;
    }

    // Search series
    $s_stmt = $conn->prepare("SELECT * FROM series WHERE title LIKE ? OR description LIKE ?");
    $s_stmt->bind_param("ss", $search_term, $search_term);
    $s_stmt->execute();
    $series_result = $s_stmt->get_result();
    while($row = $series_result->fetch_assoc()) {
        $series[] = $row;
    }
}
?>

<div class="container mt-4">
    <h1 class="mb-4">Search Results for "<?php echo htmlspecialchars($query); ?>"</h1>

    <?php if (empty($query)): ?>
        <div class="alert alert-info">Please enter a search term.</div>
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
                echo "<p>No movies found matching your search.</p>";
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
                echo "<p>No series found matching your search.</p>";
            endif;
            ?>
        </div>
    <?php endif; ?>
</div>

<?php require_once 'includes/footer.php'; ?>