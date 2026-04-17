//Used to delete photos from the databas and folder
<?php
require_once 'auth.php';
?>
<?php
session_start();
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header("Location: login.php");
    exit();
}
include("../config/database.php");
