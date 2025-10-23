<?php
// This file centralizes the database connection.

/**
 * Establishes a connection to the database using constants from config.php
 * and returns the connection object.
 *
 * @return mysqli|false The mysqli connection object on success, or false on failure.
 */
function db_connect() {
    // Ensure config is loaded. This might be redundant if already included,
    // but require_once is safe.
    if (!defined('DB_HOST')) {
        require_once __DIR__ . '/config.php';
    }

    // Suppress the default warning with '@' and handle the error manually.
    @$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    // Check for connection errors.
    if ($conn->connect_error) {
        // In a real application, you might log this error instead of echoing.
        error_log("Database connection failed: " . $conn->connect_error);
        return false;
    }

    return $conn;
}
?>
