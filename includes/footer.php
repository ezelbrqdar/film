</div> <!-- /container -->

<footer class="py-4 mt-5 bg-dark text-center text-muted">
    <div class="container">
        <p>&copy; <?php echo date('Y'); ?> CineStream. جميع الحقوق محفوظة.</p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php
// Close the database connection
if (isset($conn)) {
    $conn->close();
}
?>
