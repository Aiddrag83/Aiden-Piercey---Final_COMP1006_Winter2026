<?php
session_start();
session_destroy();
header("Location: ../index.php");
exit();
?>

//Basic logout functionality that destroys the session and redirects the user to the home page.