<?php
// Database connection file

$host = "localhost";
$username = "root";
$password = "";
$database = "resume_builder_live";

// Creating connection
$conn = new mysqli($host, $username, $password, $database);

// To check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>