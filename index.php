<?php require_once 'includes/header.php'; ?>

<div class="container mt-4">
    <!-- Latest Movies -->
    <div class="row">
        <h2 class="mb-3">Latest Movies</h2>
        <?php
        $movies = $conn->query("SELECT * FROM movies ORDER BY created_at DESC LIMIT 8");
        while($movie = $movies->fetch_assoc()):
        ?>
            <div class="col-6 col-md-4 col-lg-3 mb-4">
                <div class="card h-100">
                    <a href="watch.php?type=movie&id=<?php echo $movie['id']; ?>">
                        <img src="<?php echo htmlspecialchars($movie['poster']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($movie['title']); ?>">
                    </a>
                    <div class="card-body">
                        <h5 class="card-title"><a href="watch.php?type=movie&id=<?php echo $movie['id']; ?>"><?php echo htmlspecialchars($movie['title']); ?></a></h5>
                        <p class="card-text"><small class="text-muted"><?php echo $movie['year']; ?></small></p>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>

    <hr class="my-4">

    <!-- Latest Series -->
    <div class="row">
        <h2 class="mb-3">Latest Series</h2>
        <?php
        $series = $conn->query("SELECT * FROM series ORDER BY created_at DESC LIMIT 8");
        while($item = $series->fetch_assoc()):
        ?>
            <div class="col-6 col-md-4 col-lg-3 mb-4">
                <div class="card h-100">
                    <a href="watch.php?type=series&id=<?php echo $item['id']; ?>">
                        <img src="<?php echo htmlspecialchars($item['poster']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($item['title']); ?>">
                    </a>
                    <div class="card-body">
                        <h5 class="card-title"><a href="watch.php?type=series&id=<?php echo $item['id']; ?>"><?php echo htmlspecialchars($item['title']); ?></a></h5>
                        <p class="card-text"><small class="text-muted"><?php echo $item['year']; ?></small></p>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>