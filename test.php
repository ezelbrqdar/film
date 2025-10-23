<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'includes/db.php';

// 1. Establish a new, clean database connection
$conn = db_connect();

if (!$conn) {
    die("فشل الاتصال بقاعدة البيانات");
}

// We will set the header in the HTML meta tag instead.

$tableName = 'test_arabic';
$testText = 'هذا نص تجريبي باللغة العربية';

// Start HTML output
echo '<!DOCTYPE html>';
echo '<html lang="ar" dir="rtl">';
echo '<head>';
echo '    <meta charset="UTF-8">'; // <!-- THE MOST RELIABLE FIX
echo '    <title>اختبار الترميز العربي</title>';
echo '</head>';
echo '<body>';

// 2. Drop the table if it exists to ensure a fresh start
if ($conn->query("DROP TABLE IF EXISTS `$tableName`")) {
    echo "تم حذف الجدول القديم (إذا كان موجودًا).<br>";
}

// 3. Create a new table with the CORRECT charset and collation
$createQuery = "CREATE TABLE `$tableName` (
    id INT AUTO_INCREMENT PRIMARY KEY,
    content TEXT NOT NULL
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";

if ($conn->query($createQuery) === TRUE) {
    echo "تم إنشاء جدول `$tableName` بنجاح بترميز utf8mb4." . "<br>";
} else {
    die("خطأ في إنشاء الجدول: " . $conn->error);
}

// 4. Insert Arabic data using a prepared statement
$stmt = $conn->prepare("INSERT INTO `$tableName` (content) VALUES (?)");
if (!$stmt) {
    die("فشل التحضير: " . $conn->error);
}

$stmt->bind_param("s", $testText);

if ($stmt->execute() === TRUE) {
    echo "تم إدخال النص العربي بنجاح: '$testText'" . "<br>";
} else {
    die("خطأ في إدخال البيانات: " . $stmt->error);
}

$stmt->close();

// 5. Retrieve and display the data
$result = $conn->query("SELECT content FROM `$tableName` WHERE id = 1");

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $retrievedText = $row['content'];

    echo "<hr>";
    echo "<h2>النتيجة النهائية:</h2>";
    echo "<h1>" . htmlspecialchars($retrievedText, ENT_QUOTES, 'UTF-8') . "</h1>";

    if ($testText === $retrievedText) {
        echo "<p style='color:green; font-weight:bold;'>النتيجة: نجحت التجربة! النص متطابق.</p>";
    } else {
        echo "<p style='color:red; font-weight:bold;'>النتيجة: فشلت التجربة! النص غير متطابق.</p>";
    }

} else {
    echo "<p style='color:red; font-weight:bold;'>لم يتم العثور على البيانات بعد إدخالها!</p>";
}

$conn->close();

echo '</body>';
echo '</html>';

?>
