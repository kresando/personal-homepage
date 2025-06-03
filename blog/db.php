<?php
// Database connection configuration
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'personal-homepage';

// Create connection
$conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Set charset to utf8mb4
mysqli_set_charset($conn, "utf8mb4");

// Helper function to sanitize output
function sanitize_output($value) {
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

// Helper function to format date
function format_date($date) {
    return date('d F Y', strtotime($date));
}