<?php
require_once 'includes/header.php';

if (!isset($_GET['type']) || !isset($_GET['id'])) {
    die("Content not found.");
}

$type = $_GET['type'];
$id = (int)$_GET['id'];

if ($type === 'movie') {
    $stmt = $conn->prepare("SELECT movies.*, genres.name AS genre_name FROM movies LEFT JOIN genres ON movies.genre_id = genres.id WHERE movies.id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $content = $stmt->get_result()->fetch_assoc();
} elseif ($type === 'series') {
    $stmt = $conn->prepare("SELECT series.*, genres.name AS genre_name FROM series LEFT JOIN genres ON series.genre_id = genres.id WHERE series.id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $content = $stmt->get_result()->fetch_assoc();

    // Fetch episodes for the series
    $episodes_stmt = $conn->prepare("SELECT * FROM episodes WHERE series_id = ? ORDER BY season_number, episode_number");
    $episodes_stmt->bind_param("i", $id);
    $episodes_stmt->execute();
    $episodes_result = $episodes_stmt->get_result();
    $episodes_by_season = [];
    while ($episode = $episodes_result->fetch_assoc()) {
        $episodes_by_season[$episode['season_number']][] = $episode;
    }
} else {
    die("Invalid content type.");
}

if (!$content) {
    die("Content not found.");
}

// Determine which embed code to show
$embed_code = '';
if ($type === 'movie') {
    $embed_code = $content['embed_code'];
} elseif ($type === 'series') {
    $first_season_key = !empty($episodes_by_season) ? array_key_first($episodes_by_season) : null;
    if ($first_season_key && !empty($episodes_by_season[$first_season_key])) {
        $embed_code = $episodes_by_season[$first_season_key][0]['embed_code']; // Default to first episode
    }
    // Check if a specific episode is requested
    if (isset($_GET['episode_id'])) {
        $episode_id_to_play = (int)$_GET['episode_id'];
        foreach ($episodes_by_season as $season) {
            foreach ($season as $episode) {
                if ($episode['id'] === $episode_id_to_play) {
                    $embed_code = $episode['embed_code'];
                    break 2;
                }
            }
        }
    }
}

?>
<div class="container mt-5">
    <div class="row">
        <div class="col-lg-8">
            <!-- Video Player -->
            <div class="ratio ratio-16x9 mb-4">
                <?php if ($embed_code): ?>
                    <?php echo $embed_code; ?>
                <?php else: ?>
                    <div class="bg-dark d-flex justify-content-center align-items-center">
                        <p class="text-white">No video available.</p>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Content Details -->
            <div class="content-details">
                <h1 class="mb-3"><?php echo htmlspecialchars($content['title']); ?></h1>
                <div class="d-flex align-items-center mb-3">
                    <span class="badge bg-primary me-3"><?php echo htmlspecialchars($content['year']); ?></span>
                    <?php if (isset($content['genre_name'])): ?>
                        <span class="badge bg-secondary"><?php echo htmlspecialchars($content['genre_name']); ?></span>
                    <?php endif; ?>
                </div>
                <p><?php echo nl2br(htmlspecialchars($content['description'])); ?></p>
            </div>
        </div>

        <!-- Episodes List (for series) -->
        <?php if ($type === 'series' && !empty($episodes_by_season)): ?>
            <div class="col-lg-4">
                <h4 class="mb-3">Episodes</h4>
                <div class="accordion" id="seasonsAccordion">
                    <?php foreach ($episodes_by_season as $season_num => $episodes): ?>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="heading-season-<?php echo $season_num; ?>">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-season-<?php echo $season_num; ?>" aria-expanded="true" aria-controls="collapse-season-<?php echo $season_num; ?>">
                                    Season <?php echo $season_num; ?>
                                </button>
                            </h2>
                            <div id="collapse-season-<?php echo $season_num; ?>" class="accordion-collapse collapse show" aria-labelledby="heading-season-<?php echo $season_num; ?>">
                                <ul class="list-group list-group-flush">
                                    <?php foreach ($episodes as $episode): ?>
                                        <a href="watch.php?type=series&id=<?php echo $id; ?>&episode_id=<?php echo $episode['id']; ?>" class="list-group-item list-group-item-action">
                                            Episode <?php echo $episode['episode_number']; ?>: <?php echo htmlspecialchars($episode['title']); ?>
                                        </a>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>