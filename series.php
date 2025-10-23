<?php require_once 'includes/header.php'; ?>

<div class="container mt-4">
    <h1 class="mb-4">All Series</h1>
    
    <div class="row">
        <?php
        // You can add pagination later if needed
        $series_list = $conn->query("SELECT * FROM series ORDER BY year DESC");
        if($series_list->num_rows > 0):
            while($series = $series_list->fetch_assoc()):
        ?>
            <div class="col-6 col-md-4 col-lg-3 mb-4">
                <div class="card h-100">
                    <a href="watch.php?type=series&id=<?php echo $series['id']; ?>">
                        <img src="<?php echo htmlspecialchars($series['poster']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($series['title']); ?>">
                    </a>
                    <div class="card-body">
                        <h5 class="card-title"><a href="watch.php?type=series&id=<?php echo $series['id']; ?>"><?php echo htmlspecialchars($series['title']); ?></a></h5>
                        <p class="card-text"><small class="text-muted"><?php echo $series['year']; ?></small></p>
                    </div>
                </div>
            </div>
        <?php 
            endwhile;
        else:
            echo "<p>No series found.</p>";
        endif;
        ?>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>