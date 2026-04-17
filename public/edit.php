//Used to edit photos in the database and folder
<?php
?>
<?php
session_start();
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header("Location: login.php");
    exit();
}
include("../config/database.php");
