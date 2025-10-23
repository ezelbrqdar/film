<?php
// Core files
require_once '../includes/db.php';

// Establish database connection
$conn = db_connect();
if (!$conn) {
    // Stop execution if the connection fails. A message is already handled by db_connect().
    die(); 
}

// Now, include the header
require_once 'partials/header.php';

// Fetch stats
$movies_count = $conn->query("SELECT COUNT(*) as count FROM movies")->fetch_assoc()['count'];
$series_count = $conn->query("SELECT COUNT(*) as count FROM series")->fetch_assoc()['count'];
$episodes_count = $conn->query("SELECT COUNT(*) as count FROM episodes")->fetch_assoc()['count'];
$users_count = $conn->query("SELECT COUNT(*) as count FROM users")->fetch_assoc()['count'];

?>

<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
    </div>

    <div class="row">
        <!-- Movies Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Movies</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $movies_count; ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-film fs-2 text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Series Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Series</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $series_count; ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-tv fs-2 text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Episodes Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Episodes</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $episodes_count; ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-collection-play fs-2 text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Users Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Users</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $users_count; ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-people fs-2 text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="alert alert-warning">
        Welcome to the CineStream Admin Panel. More features will be added soon.
    </div>

</div>

<?php
require_once 'partials/footer.php';
?>