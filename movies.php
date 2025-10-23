<?php require_once 'includes/header.php'; ?>

<div class="container mt-4">
    <h1 class="mb-4">All Movies</h1>
    
    <div class="row">
        <?php
        // You can add pagination later if needed
        $movies = $conn->query("SELECT * FROM movies ORDER BY year DESC");
        if($movies->num_rows > 0):
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
        <?php 
            endwhile;
        else:
            echo "<p>No movies found.</p>";
        endif;
        ?>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>