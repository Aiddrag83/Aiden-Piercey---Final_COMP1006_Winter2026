    // validate all form input on the server, including ensuring no empty fields are submitted, and that the proper email format is given 
    hash passwords before storing them and verify passwords using password_verify( ) during login
    use sessions to restrict access to logged-in admins only
    validate uploaded files and restrict uploads to image types
    store uploaded images in an uploads/ folder
    store image file paths in the database
    use PDO prepared statements for all database operations
    display clear success and error messages to the user //
<?php
session_start();
require_once __DIR__ . '/config.php'; // Make sure config.php path is correct
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page</title>
</head>
<body>
    <h1>Welcome to the Exam Gallery App!</h1>
    <?php if (isset($_SESSION['username'])): ?>
        <p>Hello, <?php echo htmlspecialchars($_SESSION['username']); ?>! You are logged in.</p>
        <a href="logout.php">Logout</a>
    <?php else: ?>
        <p>You are not logged in. Please <a href="public/login.php">login</a>.</p>
    <?php endif; ?>
</body>
</html>

//PDO validation
<?php
try {session_start();
    $pdo = new PDO("mysql:host=localhost;dbname=gallery_db", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = trim($_POST['username']);
        $password = $_POST['password'];

        if (empty($username) || empty($password)) {
            throw new Exception("Username and password are required.");
        }
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['username'] = $username;
            header("Location: index.php");
            exit();
        } else {
            throw new Exception("Invalid username or password.");
        }
    }
} catch (Exception $e) {
    echo "<p>Error: " . $e->getMessage() . "</p>";
}
?>