<?php
require_once 'includes/header.php';

// 1. Get Episode ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid Episode ID.");
}
$episode_id = (int)$_GET['id'];

// 2. Fetch episode and series details
$stmt = $conn->prepare("
    SELECT 
        e.*, 
        s.title as series_title, 
        s.description as series_description,
        s.poster as series_poster
    FROM episodes as e
    JOIN series as s ON e.series_id = s.id
    WHERE e.id = ?
");
$stmt->bind_param("i", $episode_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Episode not found.");
}

$episode = $result->fetch_assoc();
$series_id = $episode['series_id'];
$stmt->close();

// 5. Fetch all other episodes for this series
$episodes_list_stmt = $conn->prepare("SELECT id, episode_number, title FROM episodes WHERE series_id = ? ORDER BY season_number, episode_number");
$episodes_list_stmt->bind_param("i", $series_id);
$episodes_list_stmt->execute();
$episodes_list_result = $episodes_list_stmt->get_result();


?>

<title><?php echo htmlspecialchars($episode['series_title'] . ' - الحلقة ' . $episode['episode_number']); ?> - CineStream</title>

<div class="row">
    <!-- Video Player -->
    <div class="col-12">
        <div class="ratio ratio-16x9 bg-dark">
             <?php 
                // 4. Display the sanitized video player
                echo get_sanitized_iframe($episode['embed_code']); 
            ?>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-12">
        <h2><?php echo htmlspecialchars($episode['series_title']); ?></h2>
        <p class="text-muted">الموسم <?php echo htmlspecialchars($episode['season_number']); ?> - الحلقة <?php echo htmlspecialchars($episode['episode_number']); ?>: <?php echo htmlspecialchars($episode['title']); ?></p>
        <hr>
    </div>
</div>

<div class="row">
    <!-- Episode List -->
    <div class="col-md-8">
        <h4>وصف المسلسل</h4>
        <p><?php echo nl2br(htmlspecialchars($episode['series_description'])); ?></p>
    </div>
    <div class="col-md-4">
        <h4>قائمة الحلقات</h4>
        <div class="list-group" style="max-height: 400px; overflow-y: auto;">
            <?php while($ep = $episodes_list_result->fetch_assoc()): ?>
                <a href="episode.php?id=<?php echo $ep['id']; ?>" class="list-group-item list-group-item-action <?php echo ($ep['id'] == $episode_id) ? 'active' : 'list-group-item-dark'; ?>">
                    الحلقة <?php echo htmlspecialchars($ep['episode_number']); ?>: <?php echo htmlspecialchars($ep['title']); ?>
                </a>
            <?php endwhile; ?>
        </div>
    </div>
</div>


<?php
$episodes_list_stmt->close();
require_once 'includes/footer.php';
?>