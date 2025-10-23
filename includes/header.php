<?php
// Check if config exists, otherwise redirect to installation
if (!file_exists('includes/config.php')) {
    header('Location: install/');
    exit;
}

require_once 'includes/config.php';

// Create a database connection
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($conn->connect_error) {
    die("Database connection could not be established.");
}
$conn->set_charset("utf8mb4");

// Function to check if a nav link is active
function is_active($page_name) {
    if (basename($_SERVER['PHP_SELF']) == $page_name) {
        return 'active';
    }
    return '';
}

?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CineStream</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #141414;
            color: #fff;
        }
        .navbar {
            background-color: #000 !important;
            border-bottom: 1px solid #222;
        }
        .card {
            background-color: #1a1a1a;
            border: none;
            transition: transform .2s;
            border-radius: 4px;
            overflow: hidden;
        }
        .card:hover {
            transform: scale(1.05);
            box-shadow: 0 0 15px rgba(229, 9, 20, 0.5);
        }
        .card-title a {
            text-decoration: none;
            color: #fff;
            font-size: 0.9rem;
        }
        .card-title a:hover {
            color: #e50914;
        }
        .card-body {
            padding: 0.8rem;
        }
        a, a:hover {
            text-decoration: none;
            color: inherit;
        }
        .nav-link {
            color: #ccc;
        }
        .nav-link.active, .nav-link:hover {
            color: #fff;
        }
        .btn-outline-danger {
            color: #e50914;
            border-color: #e50914;
        }
        .btn-outline-danger:hover {
            background-color: #e50914;
            color: #fff;
        }
        .accordion-button {
            background-color: #1a1a1a;
            color: white;
        }
        .accordion-button:not(.collapsed) {
            background-color: #333;
            color: white;
        }
        .list-group-item {
            background-color: #1a1a1a;
            border-color: #333;
            color: #ccc;
        }
        .list-group-item.active {
            background-color: #e50914;
            border-color: #e50914;
        }
        .accordion-button:focus {
            box-shadow: 0 0 0 0.25rem rgba(229, 9, 20, 0.25);
        }
        .ratio iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark shadow-sm">
  <div class="container-fluid">
    <a class="navbar-brand fw-bold" href="index.php">CineStream</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link <?php echo is_active('index.php'); ?>" aria-current="page" href="index.php">الرئيسية</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?php echo is_active('movies.php'); ?>" href="movies.php">أفلام</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?php echo is_active('series.php'); ?>" href="series.php">مسلسلات</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?php echo is_active('genres.php'); ?>" href="genres.php">الأنواع</a>
        </li>
      </ul>
      <form class="d-flex" action="search.php" method="GET">
        <input class="form-control me-2" type="search" name="q" placeholder="بحث..." aria-label="Search" value="<?php echo isset($_GET['q']) ? htmlspecialchars($_GET['q']) : ''; ?>">
        <button class="btn btn-outline-danger" type="submit">بحث</button>
      </form>
    </div>
  </div>
</nav>
