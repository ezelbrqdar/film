<?php
// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in, otherwise redirect to login page
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: index.php');
    exit;
}

// Include the main database connection configuration and functions
require_once '../includes/config.php';
require_once '../includes/db.php';

// Establish a database connection
$conn = db_connect();
if (!$conn) {
    die("Database connection could not be established in admin header.");
}

?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">  <!-- THIS IS THE CRITICAL FIX -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة التحكم</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            display: flex;
            min-height: 100vh;
            flex-direction: column;
            background-color: #f8f9fa;
        }
        .sidebar {
            width: 280px;
            background-color: #343a40;
            color: white;
        }
        .sidebar a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
        }
        .sidebar a:hover, .sidebar .nav-link.active {
            color: #fff;
            background-color: #495057;
        }
        .main-content {
            flex-grow: 1;
        }
    </style>
</head>
<body>
    <div class="d-flex w-100">
        <nav class="sidebar d-flex flex-column p-3">
            <h4 class="text-center mb-4">لوحة التحكم</h4>
            <ul class="nav nav-pills flex-column mb-auto">
                <li class="nav-item">
                    <a href="dashboard.php" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'dashboard.php') ? 'active' : ''; ?>">
                        <i class="bi bi-grid-fill me-2"></i> لوحة المعلومات
                    </a>
                </li>
                <li class="nav-item">
                    <a href="add_movie.php" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'add_movie.php') ? 'active' : ''; ?>">
                       <i class="bi bi-film me-2"></i> إضافة فيلم
                    </a>
                </li>
                <li class="nav-item">
                    <a href="add_series.php" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'add_series.php') ? 'active' : ''; ?>">
                        <i class="bi bi-tv-fill me-2"></i> إضافة مسلسل
                    </a>
                </li>
                <li class="nav-item">
                    <a href="manage_genres.php" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'manage_genres.php') ? 'active' : ''; ?>">
                        <i class="bi bi-tag-fill me-2"></i> إدارة الأنواع
                    </a>
                </li>
            </ul>
            <hr>
            <a href="logout.php" class="nav-link text-center"><i class="bi bi-box-arrow-left me-2"></i> تسجيل الخروج</a>
        </nav>
        <main class="main-content p-4">
