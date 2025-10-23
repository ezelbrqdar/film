<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Check if config file already exists, if so, redirect.
if (file_exists('../includes/config.php')) {
    header('Location: ../');
    exit;
}

$error = '';
$success = '';

function is_writeable_config() {
    $config_path = '../includes/config.php';
    // Check if the directory is writable, or if the file itself is writable
    if (is_writable(dirname($config_path)) || (file_exists($config_path) && is_writable($config_path))) {
        return true;
    }
    return false;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $db_host = $_POST['db_host'] ?? '';
    $db_name = $_POST['db_name'] ?? '';
    $db_user = $_POST['db_user'] ?? '';
    $db_pass = $_POST['db_pass'] ?? '';
    $admin_user = $_POST['admin_user'] ?? '';
    $admin_pass = $_POST['admin_pass'] ?? '';

    if (empty($db_host) || empty($db_name) || empty($db_user) || empty($admin_user) || empty($admin_pass)) {
        $error = 'Please fill in all required fields.';
    } elseif (!is_writeable_config()) {
        $error = 'Error: The directory <code>includes/</code> is not writable. Please check permissions.';
    } else {
        // 1. Test Database Connection
        @$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
        if ($conn->connect_error) {
            $error = "Database connection failed: " . $conn->connect_error;
        } else {
            // 2. Create config.php
            $config_content = "<?php\n";
            $config_content .= "define('DB_HOST', '" . addslashes($db_host) . "');\n";
            $config_content .= "define('DB_USER', '" . addslashes($db_user) . "');\n";
            $config_content .= "define('DB_PASS', '" . addslashes($db_pass) . "');\n";
            $config_content .= "define('DB_NAME', '" . addslashes($db_name) . "');\n";
            $config_content .= "?>";

            if (file_put_contents('../includes/config.php', $config_content) === false) {
                $error = "Could not write to config.php file.";
            } else {
                // 3. Import SQL database schema
                $sql = file_get_contents('database.sql');
                if ($conn->multi_query($sql)) {
                    // To clear the results of multi_query
                    while ($conn->next_result()) {;}

                    // 4. Insert Admin User
                    $hashed_password = password_hash($admin_pass, PASSWORD_DEFAULT);
                    $stmt = $conn->prepare("INSERT INTO admin (username, password) VALUES (?, ?)");
                    $stmt->bind_param('ss', $admin_user, $hashed_password);
                    if ($stmt->execute()) {
                        $success = "Installation successful! You will be redirected shortly.";
                        // Function to delete the install directory
                        function delete_directory($dir) {
                            if (!file_exists($dir)) return true;
                            if (!is_dir($dir)) return unlink($dir);
                            foreach (scandir($dir) as $item) {
                                if ($item == '.' || $item == '..') continue;
                                if (!delete_directory($dir . DIRECTORY_SEPARATOR . $item)) return false;
                            }
                            return rmdir($dir);
                        }
                        // Try to delete the install directory, but don't show an error if it fails
                        @delete_directory(__DIR__);
                        header('Refresh: 3; URL=../index.php'); // Redirect after 3 seconds
                    } else {
                        $error = "Failed to create admin user: " . $stmt->error;
                    }
                    $stmt->close();
                } else {
                    $error = "Error importing database schema: " . $conn->error;
                }
            }
            $conn->close();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CineStream Installation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .container { max-width: 600px; }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h1 class="h3 mb-0">CineStream Installation</h1>
            </div>
            <div class="card-body">
                <?php if ($error): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>
                <?php if ($success): ?>
                    <div class="alert alert-success"><?php echo $success; ?></div>
                    <p>If you are not redirected automatically, <a href="../index.php">click here</a>.</p>
                <?php else: ?>
                    <p>Welcome! Please provide the following details to set up your application.</p>
                    <hr>
                    <form method="POST">
                        <h5 class="mb-3">Database Details</h5>
                        <div class="mb-3">
                            <label for="db_host" class="form-label">Database Host</label>
                            <input type="text" class="form-control" id="db_host" name="db_host" value="localhost" required>
                        </div>
                        <div class="mb-3">
                            <label for="db_name" class="form-label">Database Name</label>
                            <input type="text" class="form-control" id="db_name" name="db_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="db_user" class="form-label">Database User</label>
                            <input type="text" class="form-control" id="db_user" name="db_user" required>
                        </div>
                        <div class="mb-3">
                            <label for="db_pass" class="form-label">Database Password</label>
                            <input type="password" class="form-control" id="db_pass" name="db_pass">
                        </div>
                        <hr>
                        <h5 class="mb-3">Admin Account</h5>
                        <div class="mb-3">
                            <label for="admin_user" class="form-label">Admin Username</label>
                            <input type="text" class="form-control" id="admin_user" name="admin_user" required>
                        </div>
                        <div class="mb-3">
                            <label for="admin_pass" class="form-label">Admin Password</label>
                            <input type="password" class="form-control" id="admin_pass" name="admin_pass" required>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Install Now</button>
                        </div>
                    </form>
                <?php endif; ?>
            </div>
            <div class="card-footer text-muted text-center">
                CineStream
            </div>
        </div>
    </div>
</body>
</html>
