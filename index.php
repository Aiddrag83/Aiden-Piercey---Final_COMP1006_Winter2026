    // validate all form input on the server, including ensuring no empty fields are submitted, and that the proper email format is given 
    hash passwords before storing them and verify passwords using password_verify( ) during login
    use sessions to restrict access to logged-in admins only
    validate uploaded files and restrict uploads to image types
    store uploaded images in an uploads/ folder
    store image file paths in the database
    use PDO prepared statements for all database operations
    display clear success and error messages to the user //

<?php
// Include the database connection file
require_once 'config/database.php';
