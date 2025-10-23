<?php
session_start();

// Security check: Ensure admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: index.php');
    exit;
}

// Include database connection
require_once '../includes/config.php';
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CineStream Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            display: flex;
            min-height: 100vh;
            flex-direction: column;
        }
        .main-container {
            display: flex;
            flex: 1;
        }
        .sidebar {
            width: 280px;
            background: #343a40;
            color: white;
        }
        .content {
            flex: 1;
            padding: 20px;
        }
        .sidebar a {
            color: #adb5bd;
            text-decoration: none;
        }
        .sidebar a:hover, .sidebar a.active {
            color: #fff;
            background-color: #495057;
        }
    </style>
</head>
<body>
    <div class="main-container">
        <div class="sidebar p-3 d-flex flex-column">
            <h4 class="text-center">CineStream</h4>
            <hr>
            <ul class="nav nav-pills flex-column mb-auto">
                <li class="nav-item">
                    <a href="dashboard.php" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'dashboard.php') ? 'active' : ''; ?>">
                        <i class="bi bi-speedometer2"></i> Dashboard
                    </a>
                </li>
                <li>
                    <a href="movies.php" class="nav-link <?php echo (str_starts_with(basename($_SERVER['PHP_SELF']), 'movie')) ? 'active' : ''; ?>">
                        <i class="bi bi-film"></i> Movies
                    </a>
                </li>
                <li>
                     <a href="series.php" class="nav-link <?php echo (in_array(basename($_SERVER['PHP_SELF']), ['series.php', 'series_form.php', 'episodes.php', 'episode_form.php'])) ? 'active' : ''; ?>">
                        <i class="bi bi-tv"></i> Series
                    </a>
                </li>
                <li>
                    <a href="genres.php" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'genres.php') ? 'active' : ''; ?>">
                        <i class="bi bi-tag"></i> Genres
                    </a>
                </li>
                <li>
                    <a href="users.php" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'users.php') ? 'active' : ''; ?>">
                        <i class="bi bi-people"></i> Users
                    </a>
                </li>
                <li>
                    <a href="#" class="nav-link">
                       <i class="bi bi-gear"></i> Settings
                    </a>
                </li>
            </ul>
            <hr>
            <div class="dropdown">
                <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-person-circle fs-4 me-2"></i>
                    <strong><?php echo htmlspecialchars($_SESSION['admin_username']); ?></strong>
                </a>
                <ul class="dropdown-menu dropdown-menu-dark text-small shadow" aria-labelledby="dropdownUser1">
                    <li><a class="dropdown-item" href="logout.php">Sign out</a></li>
                </ul>
            </div>
        </div>
        <div class="content">
